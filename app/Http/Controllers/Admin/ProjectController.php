<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{

    public function index()
    {
        if (\request()->ajax()) {
            // join with project category and provide stage of project
            $projects = Project::query()->leftjoin('project_categories', 'projects.project_category_id', '=', 'project_categories.id')->select('projects.*', 'project_categories.name as project_category_name');
            return DataTables::of($projects)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.projects.edit', $row->id),
                        'delete' => route('admin.projects.destroy', $row->id)
                    ], 'Project', true, 'projects');
                })


                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row,
                    ], 'Project');
                })
                ->addColumn('image', function ($row) {
                   return button_g([
                       'image' => $row->upload_id,
                   ]);
                })
                ->rawColumns(['action','created_at', 'description', 'image'])
                ->make(true);
        }
        return view('admin.project.index');
    }


    public function create()
    {
        $project  = null;
       $html = view('admin.project.edit_create',compact('project'))->render();
         return response()->json(['html' => $html, 'success' => true]);

    }


    public function store(Request $request)
    {
       $request['create'] = 'Project Create Successfully';
        return $this->update($request);
    }


    public function show($id)
    {
        $project = Project::findOrFail(($id));
        $html = view('admin.project.show',compact('project'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }


    public function edit($id)
    {
        $project = Project::find(($id));
        $html = view('admin.project.edit_create',compact('project'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }


    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'name' => 'required',
            'project_category_id' => 'required|exists:project_categories,id',
            'funded_by' => 'required',
            'duration' => 'required',
            'status' => 'required',

        ],[
            'name.required' => 'Name is required',
            'funded_by.required' => 'Funded by is required',
            'project_category_id.required' => 'Project category is required',
            'project_category_id.exists' => 'Invalid project category',
            'duration.required' => 'Duration is required',
            'status.required' => 'Status is required',
        ]);


        DB::beginTransaction();
        try {
            if($id){
                $project = Project::findOrFail($id);
                $project->update($validated);
            }else{
                Project::create($validated);
            }
            DB::commit();
            return $this->crudSuccess($request->create ?? 'Successfully updated project.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();

        }
    }


    public function destroy($id)
    {
        try {
            $delete = Project::findOrFail($id);
            $delete->delete();
            return $this->crudSuccess('Successfully deleted project.');
        } catch (\Throwable $e) {
            return $this->crudError();
        }
    }

}
