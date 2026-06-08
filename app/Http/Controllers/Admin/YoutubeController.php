<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateYoutubeRequest;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class YoutubeController extends Controller
{
    public function index()
    {
        if (\request()->ajax()) {
            $youtubess = Youtube::latest();
            return DataTables::of($youtubess)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                   return button_g([
                       'edit' => route('admin.youtubes.edit', $row->id),
                       'delete' => route('admin.youtubes.destroy', $row->id),
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
                ->rawColumns(['action','created_at', 'image'])
                ->make(true);
        }
        return view('admin.youtubes.index');
    }
    public function create()
    {
        $youtubes = null;
        $html = view('admin.youtubes.create_edit',compact('youtubes'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function store(Request $request)
    {
      $request['create'] = 'Successfully Created';
      return $this->update($request);
    }
    public function edit($id)
    {
        $youtubes = Youtube::findOrFail(($id));
        $html = view('admin.youtubes.create_edit',compact('youtubes'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'video_url' => 'required',
            'title' => 'required',
            'upload_id' => 'required',
            'description' => 'nullable',
            'status' => 'required',
        ],[
            'video_url.required' => 'Video ID is required',
            'title.required' => 'Title is required',
            'upload_id.required' => 'Image is required',
            'status.required' => 'Status is required',
        ]);

        $validated['video_url'] = Youtube::normalizeYoutubeUrl($validated['video_url']) ?? $validated['video_url'];

        try{
            if($id){
                $youtubes = Youtube::findOrFail($id);
                $youtubes->update($validated);
            }else{
                Youtube::create($validated);
            }
            return $this->crudSuccess($request->create ?? 'Successfully updated youtube.');

        }catch(\Exception $e){
            return $this->crudError();
        }
    }

    public function destroy($id)
    {
        try {
            $youtubes = Youtube::findOrFail($id);
            $youtubes->delete();
            return $this->crudSuccess('Successfully deleted youtube.');
        } catch (\Throwable $e) {
            return $this->crudError();
        }
    }


}
