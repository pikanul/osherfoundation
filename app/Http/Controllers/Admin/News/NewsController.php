<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Jobs\SendContentUpdateChunkJob;
use App\Jobs\SendContentUpdateJob;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    private const BULK_CHUNK_SIZE = 150;
    private const BULK_CHUNK_DELAY_SECONDS = 30;
    private const BULK_PER_EMAIL_PAUSE_MS = 200;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $newses = News::leftJoin('news_categories', 'news.news_category_id', '=', 'news_categories.id');
            if($request->has('news_category_id') && $request->news_category_id != null){
                $newses = $newses->where('news_category_id', $request->news_category_id);
            }
            $newses->select('news.*', 'news_categories.name as category_name');;
            return DataTables::of($newses)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.news.newses.edit', $row->id),
                        'preview' => route('admin.news.newses.newsletter_preview', $row->id),
                        'delete' => route('admin.news.newses.destroy', $row->id),
                    ], 'News', true, 'news.newses');
                })
                ->addColumn('image', function ($row) {
                    return  button_g([
                        'image' => ($row->news_image),
                    ]);
                })



                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        $category = null;
        if($request->has('news_category_id') && $request->news_category_id != null){
            $category = NewsCategory::find($request->news_category_id);
        }
        return view('admin.news.news.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $news = null;
        $html =  view('admin.news.news.create_edit',compact('news', 'request'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request['create'] = 'News Create Successfully';
        return $this->update($request);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id)
    {
        $news = News::findOrFail(($id));
        $html = view('admin.news.news.create_edit',compact('news', 'request'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'publish_date' => 'required',
            'news_image' => 'nullable|numeric',
            'pdf_file_id' => 'nullable|numeric',


            'short_descripiton' => 'nullable',
            'long_description' => 'nullable',
            'news_category_id' => 'required',
        ]);
        $validated['pdf_file_id'] = $this->normalizeUploadId($request->input('pdf_file_id'));
        $validated['use_pdf_after_cover'] = $request->boolean('use_pdf_after_cover') ? 1 : 0;

        $sendUpdate = $request->boolean('send_update');
        $shouldQueueNewsletter = false;
        $queuedNewsId = null;

        DB::beginTransaction();
        try {
            if($id){
                $news = News::findOrFail($id);
                if($news){
                    $news->update($validated);
                }
                $shouldQueueNewsletter = $sendUpdate;
                $queuedNewsId = $news->id;
                $s_data = [
                'title' => 'News Update Successfully',
                'type' => 'success',
                'refresh' => 'true',
            ];
            }else{
                $news = News::create($validated);
                $shouldQueueNewsletter = $sendUpdate;
                $queuedNewsId = $news->id;
                $s_data = [
                'title' => $request->create,
                'type' => 'success',
                'refresh' => 'true',
            ];
            }
        DB::commit();

        if ($shouldQueueNewsletter && $queuedNewsId) {
            SendContentUpdateJob::dispatch('news', $queuedNewsId);
        }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News create/update failed', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
            $s_data = [
                'title' => 'Something went wrong!.',
                'type' => 'error',
                'refresh' => 'false',
            ];
        }


        return response()->json($s_data);
    }

    private function normalizeUploadId($value): ?int
    {
        $id = (int) $value;
        return $id > 0 ? $id : null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            $image = str_replace('\\', '/', public_path('/upload/' . $news->news_image));
            if (is_file($image)) {
                @unlink($image);
            }

            $news->delete();

            return $this->crudSuccess('Successfully deleted news.');
        } catch (\Throwable $e) {
            return $this->crudError();
        }
    }


    public function newsSettingStore(Request $request)
    {
        $news =  $request->news;
        DB::beginTransaction();
        try {
            foreach($news as $key=>$value){

                if($key == 'news_banner' && $request->hasFile('news.news_banner')){
                    $image = $news['news_banner'];
                    $value = getFileNameAfterImageUpload($image);
                }
                storeValue($key, $value);
            }
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong!.');
        }

        return redirect()->back()->with('success', 'Data created successfully.');
    }

    public function newsletterPreview($id)
    {
        $news = News::with('category')->findOrFail($id);
        $payload = $this->buildNewsNewsletterPayload($news);

        return view('mail.news_alert', [
            'payload' => $payload,
            'md5email' => base64_encode('preview@example.com'),
        ]);
    }

    public function bulkNewsletterPreview(Request $request)
    {
        $newsIds = $this->parseRequestedNewsIds($request);
        if (empty($newsIds)) {
            abort(422, 'Please select at least one news item for preview.');
        }

        $payload = $this->buildBulkSelectedNewsletterPayload($newsIds);
        if (!$payload) {
            abort(404, 'Selected news items not found.');
        }

        return view('mail.news_alert', [
            'payload' => $payload,
            'md5email' => base64_encode('preview@example.com'),
        ]);
    }

    public function bulkNewsletter(Request $request)
    {
        $newsIds = $this->parseRequestedNewsIds($request);
        if (empty($newsIds)) {
            return $this->crudError('Please select at least one news item.');
        }

        $newsletterTitle = $this->parseNewsletterTitle($request);
        $payload = $this->buildBulkSelectedNewsletterPayload($newsIds, $newsletterTitle);
        if (!$payload) {
            return $this->crudError('Selected news items not found.');
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

            return $this->crudSuccess('Combined newsletter queued successfully for ' . $emails->count() . ' subscriber(s).');
        } catch (\Throwable $e) {
            Log::error('Bulk newsletter queue failed', [
                'news_ids' => $newsIds,
                'message' => $e->getMessage(),
            ]);

            return $this->crudError('Failed to queue selected news for newsletter.');
        }
    }

    private function parseRequestedNewsIds(Request $request): array
    {
        $rawIds = $request->input('news_ids', []);

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

    private function parseNewsletterTitle(Request $request): ?string
    {
        $title = trim((string) $request->input('newsletter_title', ''));
        if ($title === '') {
            return null;
        }

        return Str::limit($title, 255, '');
    }

    private function buildBulkSelectedNewsletterPayload(array $orderedNewsIds, ?string $newsletterTitle = null): ?array
    {
        $orderedNewsIds = collect($orderedNewsIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values()
            ->all();

        if (empty($orderedNewsIds)) {
            return null;
        }

        $newsItems = News::with('category')->whereIn('id', $orderedNewsIds)->get()->keyBy('id');
        $existingOrderedIds = collect($orderedNewsIds)->filter(fn ($id) => $newsItems->has($id))->values();
        if ($existingOrderedIds->isEmpty()) {
            return null;
        }

        $headId = (int) $existingOrderedIds->last();
        /** @var News $headNews */
        $headNews = $newsItems->get($headId);
        if (!$headNews) {
            return null;
        }

        $subIds = $existingOrderedIds->filter(fn ($id) => (int) $id !== $headId)->values();
        $subItems = $subIds->map(fn ($id) => $newsItems->get((int) $id))->filter()->values();

        $previousNewsByCategory = $subItems
            ->groupBy(fn ($item) => $item->category?->name ?? 'Other')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'summary' => Str::limit((string) ($item->short_descripiton ?? ''), 140),
                        'image_url' => function_exists('dynamic_asset') ? dynamic_asset($item->news_image ?? 0) : null,
                        'publish_date' => $item->publish_date ? Carbon::parse($item->publish_date)->format('d M Y') : null,
                        'url' => route('news.show', ['id' => $item->id]),
                    ];
                })->values()->all();
            })
            ->toArray();

        return [
            'type' => 'news',
            'title' => $newsletterTitle ?: $headNews->title,
            'category' => $headNews->category?->name ?? 'News',
            'summary' => $headNews->short_descripiton ?: 'A new news update has been published.',
            'image_url' => function_exists('dynamic_asset') ? dynamic_asset($headNews->news_image ?? 0) : null,
            'publish_date' => $headNews->publish_date ? Carbon::parse($headNews->publish_date)->format('d M Y') : null,
            'newsletter_banner' => function_exists('settings') ? settings('newslater_banner_image', 27) : null,
            'publish_label' => now()->format('F Y'),
            'url' => route('news.show', ['id' => $headNews->id]),
            'previous_news_by_category' => $previousNewsByCategory,
        ];
    }

    private function buildNewsNewsletterPayload(News $news): array
    {
        $previousNewsByCategory = News::with('category')
            ->where('id', '!=', $news->id)
            ->orderByDesc('publish_date')
            ->orderByDesc('id')
            ->take(20)
            ->get()
            ->groupBy(fn ($item) => $item->category?->name ?? 'Other')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    $url = route('news.show', ['id' => $item->id]);

                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'summary' => Str::limit((string) ($item->short_descripiton ?? ''), 140),
                        'image_url' => function_exists('dynamic_asset') ? dynamic_asset($item->news_image ?? 0) : null,
                        'publish_date' => $item->publish_date ? Carbon::parse($item->publish_date)->format('d M Y') : null,
                        'url' => $url,
                    ];
                })->values()->all();
            })
            ->toArray();

        $url = route('news.show', ['id' => $news->id]);

        return [
            'type' => 'news',
            'title' => $news->title,
            'category' => $news->category?->name ?? 'News',
            'summary' => $news->short_descripiton ?: 'A new news update has been published.',
            'image_url' => function_exists('dynamic_asset') ? dynamic_asset($news->news_image ?? 0) : null,
            'publish_date' => $news->publish_date ? Carbon::parse($news->publish_date)->format('d M Y') : null,
            'newsletter_banner' => function_exists('settings') ? settings('newslater_banner_image', 27) : null,
            'publish_label' => now()->format('F Y'),
            'url' => $url,
            'previous_news_by_category' => $previousNewsByCategory,
        ];
    }
}
