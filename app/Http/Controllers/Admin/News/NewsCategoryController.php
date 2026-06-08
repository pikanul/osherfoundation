<?php

namespace App\Http\Controllers\Admin\News;
use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NewsCategoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $newsCategories = NewsCategory::latest();
            return DataTables::of($newsCategories)

                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.news.categories.edit', $row->id),
                        'delete' => route('admin.news.categories.destroy', $row->id),
                    ], 'News Category', true, 'news.categories');
                })
                ->editColumn('image', function ($row) {
                    return  button_g([
                        'image' => ($row->upload_id),
                    ]);
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '';
                })

                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('admin.news.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $newsCategory = null;
        $html = view('admin.news.category.create_edit', compact('newsCategory', 'request'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *

     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request['create'] = 'News Category Create Successfully';
        return $this->update($request);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $newsCategory = NewsCategory::findOrFail(($id));
        $html = view('admin.news.category.create_edit', compact('newsCategory'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'upload_id' => 'required',
        ], [
            'name.required' => 'Name Required',
            'slug.required' => 'Slug Required',
        ]);
        DB::beginTransaction();
        try {
            if ($id) {
                $newsCategory = NewsCategory::findOrFail($id);
                $newsCategory->update($validated);
                DB::commit();

                return $this->crudSuccess($request->create ?? 'News category updated successfully.');
            }

            NewsCategory::create($validated);
            DB::commit();

            return $this->crudSuccess($request->create ?? 'News category created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsCategory  $newsCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            NewsCategory::findOrFail(($id))->delete();
            DB::commit();
            return $this->crudSuccess('Successfully deleted news category.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

    public function select(Request $request)
    {
        $data_result = NewsCategory::where(function ($query) use ($request) {
            if ($request->has('q')) {
                $query->where('name', 'LIKE', '%' . $request->q . '%');

            }
        })->select('id', 'name as text')->get();

        $result_make = [];
        $result_make['items'] = $data_result;

        return response()->json($result_make);

    }
}
