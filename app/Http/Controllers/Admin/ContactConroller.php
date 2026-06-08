<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ContactConroller extends Controller
{
    public function index()
    {
        if(!Auth::hasP('contact')) return abort(403);

        if (\request()->ajax()) {
            $categories = Contact::select('contacts.*');
            return DataTables::of($categories)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    return button_g([
                        'delete' => route('admin.contacts.destroy', $row->id),
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.contact.index');
    }


    public function change_stauts(Request $request)
    {

        $data = Contact::find($request->id);
        $data->read_status = $request->status;
        $data->save();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Status Change Successfully',
                'type' => 'success'

            ]
        );
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Contact::findOrFail($id)->delete();
            DB::commit();
            return $this->crudSuccess('Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crudError();
        }
    }

   public  function mark_as_read(Request $request)
    {
        try{
             Contact::where('read_status', 0)->where('id',  $request->id)->update(['read_status' => 1]);
            return response()->json([
                'status' => 'success',
                'message' => 'Status Change Successfully',
                'type' => 'success'
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'type' => 'error'
            ]);
        }
    }

}
