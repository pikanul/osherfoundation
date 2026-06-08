<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Page;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View | \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (\request()->ajax()) {
            $pages = Page::latest();
            return DataTables::of($pages)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.pages.edit', $row->id),
                        'delete' => route('admin.pages.destroy', $row->id),
                    ], 'Page', false, 'pages');
                })
                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row
                    ]);
                })
              
                ->rawColumns(['action', 'created_at', 'image'])
                ->make(true);
        }
        return view('admin.pages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\view\View
     */
    public function create()
    {
        $pages = null;
       return  view('admin.pages.create_edit', compact('pages'));
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request['create'] = 'Page Create Successfully';
        return $this->update($request);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $pages = Page::find(($id));
         return  view('admin.pages.create_edit', compact('pages'));
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
            'slug' => 'required',
            'status' => 'required',
            'description' => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            if($id){
                $category = Page::findOrFail($id);
                $category->update($validated);    
            }else{
                Page::create($validated);
            }
            DB::commit();
            return $this->crudSuccess($request->create ?? 'Successfully updated page.');
        } catch (\Exception $e) {
            DB::rollBack();
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
            $pages = Page::findOrFail($id);
            $pages->delete();
            return $this->crudSuccess('Successfully deleted page.');
        } catch (\Exception $e) {
            return $this->crudError();
        }
    }
}
