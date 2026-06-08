<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectCategoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $projectCategories = ProjectCategory::latest();
            return DataTables::of($projectCategories)

                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.project-categories.edit', $row->id),
                        'delete' => route('admin.project-categories.destroy', $row->id),
                    ], 'project Category', true, 'project.categories');
                })


                ->rawColumns(['action',])
                ->make(true);
        }
        return view('admin.project.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $projectCategory = null;
        $html = view('admin.project.category.create_edit', compact('projectCategory', 'request'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *

     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request['create'] = 'project Category Create Successfully';
        return $this->update($request);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectCategory  $projectCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $projectCategory = ProjectCategory::findOrFail(($id));
        $html = view('admin.project.category.create_edit', compact('projectCategory'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ProjectCategory  $projectCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required|in:0,1',
            
        ], [
            'name.required' => 'Name Required',
            'slug.required' => 'Slug Required',
            'status.required' => 'Status Required',
            'status.in' => 'Invalid status value',
        ]);
        DB::beginTransaction();
        try {
            if ($id) {
                $projectCategory = ProjectCategory::findOrFail($id);
                $projectCategory->update($validated);
            } else {
                ProjectCategory::create($validated);
            }
            DB::commit();
            return $this->crudSuccess($request->create ?? 'Project category updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectCategory  $projectCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            ProjectCategory::findOrFail(($id))->delete();
            DB::commit();
            return $this->crudSuccess('Successfully deleted project category.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

    public function select(Request $request)
    {
        $data_result = ProjectCategory::where(function ($query) use ($request) {
            if ($request->has('q')) {
                $query->where('name', 'LIKE', '%' . $request->q . '%');

            }
        })->select('id', 'name as text')->get();

        $result_make = [];
        $result_make['items'] = $data_result;

        return response()->json($result_make);

    }
}
