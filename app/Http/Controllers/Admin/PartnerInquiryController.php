<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PartnerInquiryController extends Controller
{
    public function index()
    {
        if (!Auth::hasP('contact')) {
            abort(403);
        }

        if (request()->ajax()) {
            return DataTables::of(PartnerInquiry::query()->latest())
                ->addIndexColumn()
                ->addColumn('interests', fn ($row) => implode(', ', $row->partnership_interests ?? []))
                ->addColumn('status', function ($row) {
                    if (!$row->read_status) {
                        return '<span class="badge badge-danger">Unread</span> <button class="btn btn-sm btn-primary mark_as_read" data-id="' . $row->id . '" onclick="mark_as_read(this)">Mark as read</button>';
                    }

                    return '<span class="badge badge-success">Read</span>';
                })
                ->addColumn('action', function ($row) {
                    return button_g([
                        'view' => route('admin.partner-inquiries.show', $row->id),
                        'delete' => route('admin.partner-inquiries.destroy', $row->id),
                    ], '', false);
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.partner-inquiries.index');
    }

    public function show(PartnerInquiry $partnerInquiry)
    {
        if (!Auth::hasP('contact')) {
            abort(403);
        }

        if (!$partnerInquiry->read_status) {
            $partnerInquiry->update(['read_status' => true]);
        }

        return view('admin.partner-inquiries.show', compact('partnerInquiry'));
    }

    public function mark_as_read(Request $request)
    {
        try {
            PartnerInquiry::where('read_status', false)->where('id', $request->id)->update(['read_status' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'Status Change Successfully',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'type' => 'error',
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            PartnerInquiry::findOrFail($id)->delete();
            DB::commit();

            return $this->crudSuccess('Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->crudError();
        }
    }
}
