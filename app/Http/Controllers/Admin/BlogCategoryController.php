<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\BlogCategory;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) {
            $categories = BlogCategory::query();;
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return button_g(
                        [

                            'edit' => route('admin.blog-categories.edit', $row->id),
                            'delete' => route('admin.blog-categories.destroy', $row->id)
                        ], 'Category');
   
                })
                ->addColumn('image', function ($row) {
                    return button_g(['image' => $row->upload_id]);
                })
                ->addColumn('created_at', function ($row) {
                    return button_g(['created_at' => $row ]);
                })
                ->rawColumns(['image','action', 'created_at'])
                ->make(true);
        }

        return view('admin.blog-category.index');
    }


    public function create()
    {
        $category = null;
        $html = view('admin.blog-category.create_edit', compact('category'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function store(Request $request)
    {
      
        $request['create'] = 'Successfully  Created Category';
        return $this->update($request);
    }


    public function edit($id)
    {
        $category = BlogCategory::findOrFail(($id));
        $html = view('admin.blog-category.create_edit', compact('category'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }


    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'name' => 'required',  
            'upload_id' => 'required|numeric',
            'slug' => 'nullable',
            'status' => 'required',
            'description' => 'required',
        ]);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?? null, $validated['name'], $id);
        
        DB::beginTransaction();
        try {
            if($id){
                $category = BlogCategory::findOrFail($id);
                $category->update($validated);    
            }else{
                BlogCategory::create($validated);
            }
            DB::commit();
            return $this->crudSuccess($request->create ?? 'Successfully updated category.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

    private function makeUniqueSlug(?string $slugInput, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug(trim((string) ($slugInput ?: $name)));
        if ($base === '') {
            $base = 'category';
        }

        $slug = $base;
        $counter = 1;

        while (
            BlogCategory::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            BlogCategory::findOrFail(($id))->delete();
            DB::commit();
            return $this->crudSuccess('Successfully deleted category.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }
    public function select (Request $request)
    {
        $data_result = BlogCategory::where(function($query) use ($request) {
            if ($request->has('q')) {
                $query->where('name', 'LIKE', '%' . $request->q . '%');

            }
        })->select('id', 'name as text')->get();

        $result_make = [];
        $result_make['items']=$data_result;

        return response()->json($result_make);

    }
}
