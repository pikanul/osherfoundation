<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class TeamController extends Controller
{

    public function index()
    {
        if (\request()->ajax()) {
            $teams = Team::latest();
            return DataTables::of($teams)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.teams.edit', $row->id),
                        'delete' => route('admin.teams.destroy', $row->id),
                    ]);
                })
                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row
                    ]);
                })
                ->addColumn('image', function ($row) {
                    return button_g([
                        'image' => $row->upload_id
                    ]);
                })
                ->rawColumns(['action', 'created_at', 'image'])
                ->make(true);
        }
        return view('admin.team.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $team = null;
        $html = view('admin.team.create_edit', compact('team'))->render();
        return response()->json(['html' => $html, 'success' => true]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request['create'] = 'Team Create Successfully';
        return $this->update($request);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $team = Team::find(($id));
        $html = view('admin.team.create_edit', compact('team'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param    $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'type' => 'required|in:leader,general',
            'upload_id' => 'required',
            'short_des' => 'nullable',
            'email' => 'nullable',
            'phone' => 'nullable',
            'status' => 'required',
        ], [
            'name.required' => 'Name is required',
            'designation.required' => 'Designation is required',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be either leader or general',
            'upload_id.required' => 'Image is required',
            'status.required' => 'Status is required',
        ]);





        try {
            if ($id) {
                $team = Team::findOrFail($id);
                $team->update($validated);
            } else {
                Team::create($validated);
            }
            return $this->crudSuccess($request->create ?? 'Successfully updated team.');

        } catch (\Exception $e) {
            return $this->crudError();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete();
            return $this->crudSuccess('Successfully deleted team.');
        } catch (\Exception $e) {
            return $this->crudError();
        }
    }
}
