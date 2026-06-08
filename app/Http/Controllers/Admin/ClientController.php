<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Business;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) {
            $clients = Client::latest();
            return DataTables::of($clients)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                   return button_g([
                       'edit' => route('admin.clients.edit', $row->id),
                       'delete' => route('admin.clients.destroy', $row->id),
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
                ->rawColumns(['action','created_at', 'image'])
                ->make(true);
        }
        return view('admin.client.index');
    }

   
    public function create()
    {
        $client = null;
        $html = view('admin.client.create_edit', compact('client'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

 
    public function store(Request $request)
    {
       $request['create'] = 'Successfully Created';
       return $this->update($request);
    }

   
    public function edit($id)
    {
        $client = Client::findOrFail(($id));
        $html = view('admin.client.create_edit',compact('client'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function update(Request $request, $id =null)
    {
        $validated = $request->validate ([
            'name' => 'required',
            'upload_id' => 'required|numeric',
            'status' => 'required',
            'description' => 'nullable',
            'company_name' => 'nullable',
        ],[
            'name.required' => 'Name is required',
            'upload_id.required' => 'Image is required',
            'status.required' => 'Status is required',
        ]);
       try{
            if($id){
                $client = Client::findOrFail($id);
                $client->update($validated);
            }else{
                Client::create($validated);
            }
            return $this->crudSuccess($request->create ?? 'Successfully updated client.');
        }catch(\Exception $e){
            return $this->crudError();
        }
    }

    
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return $this->crudSuccess('Successfully deleted client.');
        } catch (\Throwable $e) {
            return $this->crudError();
        }
    }
}
