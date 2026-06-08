<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{

    public function index()
    {
        if (\request()->ajax()) {
            $sliders = Slider::query();
            return DataTables::of($sliders)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                   return button_g([
                       'edit' => route('admin.sliders.edit', $row->id),
                       'delete' => route('admin.sliders.destroy', $row->id)
                   ]);
                })
                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row,
                    ]);
                })
                ->addColumn('image', function ($row) {
                    return button_g([
                        'image' => $row->upload_id
                    ]);
                })
                ->rawColumns(['action','created_at','image'])
                ->make(true);
        }

        return view('admin.slider.index');
    }

  
    
    public function create()
    {
        $slider = null;
        $html = view('admin.slider.create_edit', compact('slider'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function store(Request $request)
    {
       $request['create'] = 'Successfully Created Slider';
       return $this->update($request);
    }

    
    public function edit($id)
    {
        $slider = Slider::findOrFail(($id));
        $html = view('admin.slider.create_edit',compact('slider'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }


    public function update(Request $request, $id = null)
    {
        $validated = $request->validate([
            'title' => 'required',
            'sub_title' => 'nullable',
            'upload_id' => 'required',
            'status' => 'required',
            'link_text' => 'nullable',
        ],[
            'title.required' => 'Please enter title',
            'upload_id.required' => 'Please upload image',
            'status.required' => 'Please select status',
        ]);

        try{
            if($id){
                $slider = Slider::findOrFail($id);
                $slider->update($validated);
            }else{
                Slider::create($validated);
            }
            return $this->crudSuccess($request->create ?? 'Successfully updated slider.');
        }
        catch(\Exception $e){
            return $this->crudError();
        }
        
    }

    
    public function destroy($id)
    {
        try{
            $slider = Slider::findOrFail($id);
            $slider->delete();
            return $this->crudSuccess('Successfully deleted slider.');
        }
        catch(\Exception $e){
            return $this->crudError();
        }
    }
}
