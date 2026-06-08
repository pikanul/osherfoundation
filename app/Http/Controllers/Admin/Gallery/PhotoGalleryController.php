<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class PhotoGalleryController extends Controller
{
    
    public function index()
    {
        if (\request()->ajax()) {
            $galleries = Gallery::latest();
            return DataTables::of($galleries)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.gallery.photo.edit', $row->id),
                        'delete' => route('admin.gallery.photo.destroy', $row->id),
                    ],'Gallery', true, 'gallery.photo');
                })
                ->addColumn('image', function ($row) {
                    return button_g([
                        'image' => $row->upload_id
                    ]);
                })
                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row
                    ]);
                })
                ->rawColumns(['action','created_at', 'image'])
                ->make(true);
        }
        return view('admin.gallery.photo.index');
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $gallery = null;
        $html = view('admin.gallery.photo.create_edit', compact('gallery'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function store(Request $request)
    {
       $request['create']= 'Gallery Create Successfully';
       return $this->update($request);
    }

    
    
   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function edit($id)
    {
        $gallery = Gallery::findOrFail(($id));
        $html = view('admin.gallery.photo.create_edit',compact('gallery'))->render();
        return response()->json(['html' => $html, 'success' => true]);

    }

  
      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id=null)
    {
        $validated = $request->validate([
            'upload_id' => 'required',
            'name' => 'nullable',
         
        ],[
            'upload_id.required' => 'Image Required',
            
        ]);
        
        if(isset($request->uploads_id)){
            $validated['upload_ids'] = implode(',',$request->uploads_id);
        }

        try{

            if($id){
                $gallery = Gallery::findOrFail($id);
                $gallery->update($validated);
            }else{
                Gallery::create($validated);
            }
            return $this->crudSuccess($request->create ?? 'Successfully updated gallery.');
           
        }catch(\Exception $e){
            return $this->crudError();
          
        }
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            $gallery = Gallery::findOrFail($id);
            $gallery->delete();
            return $this->crudSuccess('Successfully deleted gallery.');
        }catch(\Exception $e){
            return $this->crudError();
        }
    }
}
