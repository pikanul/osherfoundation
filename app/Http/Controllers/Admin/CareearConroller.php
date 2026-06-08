<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CareearConroller extends Controller
{



    public function index()
    {
        if(!Auth::hasP('careear')) return abort(403);

        if (\request()->ajax()) {
            $categories = Career::latest();
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return button_g([
                        'created_at' => $row
                    ]);
                })
                ->addColumn('action', function ($row) {
                    return button_g([
                        'delete' => route('admin.careears.destroy', $row->id),
                    ]);
                })
                ->rawColumns(['action', 'created_at'])
                ->make(true);
        }
        return view('admin.careear.index');
    }


    public function change_stauts(Request $request)
    {

        $data = Career::find($request->id);
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
            Career::findOrFail($id)->delete();
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
             Career::where('read_status', 0)->where('id',  $request->id)->update(['read_status' => 1]);
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
