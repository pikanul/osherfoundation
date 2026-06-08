<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendContentUpdateChunkJob;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Subscriber;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    private const BULK_CHUNK_SIZE = 150;
    private const BULK_CHUNK_DELAY_SECONDS = 30;
    private const BULK_PER_EMAIL_PAUSE_MS = 200;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\request()->ajax()) {
            $blogs = Blog::leftJoin('blog_categories', 'blog_categories.id', 'blogs.category_id')

                    ->select('blogs.*', 'blog_categories.name as cat_name');
            return DataTables::of($blogs)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                   return button_g([
                       'edit' => route('admin.blogs.edit', $row->id),
                       'delete' => route('admin.blogs.destroy', $row->id)
                   ], 'Blog');
                })
                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row,
                    ]);
                })
                ->addColumn('image', function ($row) {
                   return button_g([
                       'image' => $row->upload_id,
                   ]);
                })
                ->rawColumns(['action','created_at', 'image'])
                ->make(true);
        }

        return view('admin.blog.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $blog = null;
        $html = view('admin.blog.create_edit',compact('blog', 'request'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request['create'] = 'Blog created successfully.';
       return $this->update($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $blog = Blog::findOrFail(($id));
        $html = view('admin.blog.create_edit', compact('blog', 'request'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $blog = null)
    {
        $validated = $request->validate([
            'title' => 'required',
            'upload_id' => 'required',
            'attachment_id' => 'nullable',
            'category_id' => 'nullable',
            'publish_date' => 'required',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'slug' => 'required|unique:blogs,slug,'.$blog,
            'status' => 'required',
        ],[
            'title.required' => 'Title is required',
            'upload_id.required' => 'Image is required',
            'category_id.required' => 'Category is required',
            'publish_date.required' => 'Publish Date is required',
            'status.required' => 'Status is required',
            'slug.required' => 'Slug is required',
        ]);
        $isUpdate = (bool) $blog;

        try {

            if($blog){
                $blog = Blog::find($blog);
                if($blog){
                    $blog->update($validated);
                    return $this->crudSuccess($request->update ?? 'Blog updated successfully.');
                }else{
                    return $this->crudError('Blog not found.', false, 404);
                }
            }else{
                $blog = Blog::create($validated);
                return $this->crudSuccess($request->create ?? 'Blog created successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Blog create/update failed', [
                'id' => $blog,
                'message' => $e->getMessage(),
            ]);
            return $this->crudError($e->getMessage() ?: 'Something went wrong!.', false, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        DB::beginTransaction();
        try {
            $blog = Blog::findOrFail(($id));
            $blog->delete();
            DB::commit();
            return $this->crudSuccess('Successfully deleted blog.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

    public function bulkNewsletterPreview(Request $request)
    {
        $blogIds = $this->parseRequestedBlogIds($request);
        if (empty($blogIds)) {
            abort(422, 'Please select at least one blog item for preview.');
        }

        $payload = $this->buildBulkSelectedBlogNewsletterPayload($blogIds);
        if (!$payload) {
            abort(404, 'Selected blog items not found.');
        }

        return view('mail.content_update', [
            'payload' => $payload,
            'md5email' => base64_encode('preview@example.com'),
        ]);
    }

    public function bulkNewsletter(Request $request)
    {
        $blogIds = $this->parseRequestedBlogIds($request);
        if (empty($blogIds)) {
            return $this->crudError('Please select at least one blog item.');
        }

        $payload = $this->buildBulkSelectedBlogNewsletterPayload($blogIds);
        if (!$payload) {
            return $this->crudError('Selected blog items not found.');
        }

        try {
            $emails = Subscriber::query()
                ->where('status', 1)
                ->whereNotNull('email')
                ->pluck('email')
                ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->values();

            if ($emails->isEmpty()) {
                return $this->crudError('No active subscribers found.');
            }

            $chunks = $emails->chunk(self::BULK_CHUNK_SIZE)->values();
            foreach ($chunks as $index => $chunk) {
                SendContentUpdateChunkJob::dispatch(
                    $payload,
                    $chunk->values()->all(),
                    self::BULK_PER_EMAIL_PAUSE_MS
                )->delay(now()->addSeconds($index * self::BULK_CHUNK_DELAY_SECONDS));
            }

            return $this->crudSuccess('Combined blog newsletter queued successfully for ' . $emails->count() . ' subscriber(s).');
        } catch (\Throwable $e) {
            Log::error('Bulk blog newsletter queue failed', [
                'blog_ids' => $blogIds,
                'message' => $e->getMessage(),
            ]);

            return $this->crudError('Failed to queue selected blog newsletter.');
        }
    }

    private function parseRequestedBlogIds(Request $request): array
    {
        $rawIds = $request->input('blog_ids', []);

        if (is_string($rawIds)) {
            $rawIds = array_filter(array_map('trim', explode(',', $rawIds)));
        }

        if (!is_array($rawIds)) {
            return [];
        }

        return collect($rawIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function buildBulkSelectedBlogNewsletterPayload(array $orderedBlogIds): ?array
    {
        $orderedBlogIds = collect($orderedBlogIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values()
            ->all();

        if (empty($orderedBlogIds)) {
            return null;
        }

        $blogs = Blog::with('category')->whereIn('id', $orderedBlogIds)->get()->keyBy('id');
        $existingOrderedIds = collect($orderedBlogIds)->filter(fn ($id) => $blogs->has($id))->values();
        if ($existingOrderedIds->isEmpty()) {
            return null;
        }

        $headId = (int) $existingOrderedIds->last();
        $headBlog = $blogs->get($headId);
        if (!$headBlog) {
            return null;
        }

        $subIds = $existingOrderedIds->filter(fn ($id) => (int) $id !== $headId)->values();
        $subItems = $subIds->map(fn ($id) => $blogs->get((int) $id))->filter()->values();

        $subItemsPayload = $subItems
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'summary' => Str::limit((string) ($item->short_description ?? ''), 140),
                    'image_url' => function_exists('dynamic_asset') ? dynamic_asset($item->upload_id ?? 0) : null,
                    'publish_date' => $item->publish_date ? Carbon::parse($item->publish_date)->format('d M Y') : null,
                    'url' => route('blog.details', ['slug' => $item->slug]),
                    'category' => $item->category?->name ?? 'Blog',
                ];
            })
            ->values()
            ->all();

        return [
            'type' => 'blog',
            'title' => $headBlog->title,
            'summary' => $headBlog->short_description ?: 'A new blog update has been published.',
            'image_url' => function_exists('dynamic_asset') ? dynamic_asset($headBlog->upload_id ?? 0) : null,
            'publish_date' => $headBlog->publish_date ? Carbon::parse($headBlog->publish_date)->format('d M Y') : null,
            'category' => $headBlog->category?->name ?? 'Blog',
            'url' => route('blog.details', ['slug' => $headBlog->slug]),
            'sub_items' => $subItemsPayload,
        ];
    }
}
