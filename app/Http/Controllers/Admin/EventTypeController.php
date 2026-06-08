<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EventTypeController extends Controller
{
    public function index()
    {
        if (\request()->ajax()) {
            $products = EventType::query();
            return DataTables::of($products)

                ->addColumn('action', function ($row) {
                    return button_g([
                        'edit' => route('admin.eventtypes.edit', $row->id),
                        'delete' => route('admin.eventtypes.destroy', $row->id),

                    ]);
                })

                ->addColumn('image', function ($row) {
                    return button_g([
                        'image' => $row->attachment
                    ]);
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('admin.eventtype.index');
    }

    public function create()
    {
        $eventtype = null;
        $html = view('admin.eventtype.create_edit', compact('eventtype'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function edit($id)
    {
        $eventtype = EventType::find($id);
        $html = view('admin.eventtype.create_edit', compact('eventtype'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }


    public function show(string $id)
    {

         $eventtype = EventType::find($id);
         if($eventtype){
             return view('admin.eventtype.show', compact('eventtype'));
         }else{
             return redirect()->route('admin.eventtypes.index');
         }
    }


    public function store(Request $request)
    {
        $request['create'] = 'Successfully Created';
        return $this->update($request);
    }

    public function update(Request $request, $id = null)
    {
        $valided = $request->validate([
            'name' => 'required',
            'color' => 'nullable',
            'status' => 'required',
            'type' => 'nullable',
        ],[
            'name.required' => ' Name is required',
            'status.required' => 'Status is required',
        ]);

        try {
            if ($id) {
                $eventtpe = EventType::findOrFail($id);
                $eventtpe->update($valided);
                return $this->crudSuccess('Successfully updated event type.');
            }

            EventType::create($valided);
            return $this->crudSuccess('Successfully created event type.');
        } catch (\Throwable $e) {
            return $this->crudError();
        }
    }

    public function destroy($id)
    {
        try {
            $eventtpe = EventType::findOrFail($id);
            $eventtpe->delete();
            return $this->crudSuccess('Successfully deleted event type.');
        } catch (\Throwable $e) {
            return $this->crudError();
        }
    }



    public function select (Request $request)
    {
        $data_result = EventType::where('status', 1)->where(function($query) use ($request) {
            if ($request->has('q')) {
                $query->where('name', 'LIKE', '%' . $request->q . '%');
                $query->orWhere('color', 'LIKE', '%' . $request->q . '%');
            }
        })->select('id', 'name as text')->get();

        $result_make = [];
        $result_make['items']=array_merge([['id' => '', 'text' => 'Select Event']],$data_result->toArray());//$data_result;

        return response()->json($result_make);

    }

    public function downloadImportTemplate()
    {
        $headers = ['name', 'type', 'color', 'status'];
        $sample = ['Holiday', 'general', '#16a34a', '1'];

        $callback = function () use ($headers, $sample) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fputcsv($out, $sample);
            fclose($out);
        };

        return response()->streamDownload($callback, 'event_type_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'import_file.required' => 'Please upload a CSV file.',
            'import_file.mimes' => 'Only CSV file is supported.',
        ]);

        $handle = fopen($request->file('import_file')->getRealPath(), 'r');
        if (!$handle) {
            return redirect()->back()->with('import_report', [
                'type' => 'danger',
                'message' => 'Could not read file.',
            ]);
        }

        $headerRow = fgetcsv($handle);
        if (!$headerRow) {
            fclose($handle);
            return redirect()->back()->with('import_report', [
                'type' => 'danger',
                'message' => 'CSV header row is missing.',
            ]);
        }

        $headers = array_map(fn($h) => strtolower(trim((string) $h)), $headerRow);
        $missing = array_diff(['name'], $headers);
        if (!empty($missing)) {
            fclose($handle);
            return redirect()->back()->with('import_report', [
                'type' => 'danger',
                'message' => 'Missing required columns: ' . implode(', ', $missing),
            ]);
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNo = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNo++;
            if (count(array_filter($row, fn($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = array_pad($row, count($headers), null);
            $data = array_combine($headers, $row);
            $data = array_map(fn($v) => is_string($v) ? trim($v) : $v, $data);

            $payload = [
                'name' => $data['name'] ?? null,
                'type' => $data['type'] ?? null,
                'color' => $data['color'] ?? null,
                'status' => $this->normalizeStatus($data['status'] ?? 1),
            ];

            $validator = Validator::make($payload, [
                'name' => 'required|string|max:125',
                'type' => 'nullable|string|max:100',
                'color' => 'nullable|string|max:125',
                'status' => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                $skipped++;
                $errors[] = 'Row ' . $rowNo . ': ' . $validator->errors()->first();
                continue;
            }

            try {
                $existing = EventType::query()->where('name', $payload['name'])->first();
                if ($existing) {
                    $existing->update($payload);
                } else {
                    EventType::create($payload);
                }
                $imported++;
            } catch (\Throwable $e) {
                $skipped++;
                $errors[] = 'Row ' . $rowNo . ': ' . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "Import finished. Imported/Updated: {$imported}, Skipped: {$skipped}.";
        if (!empty($errors)) {
            $message .= ' First errors: ' . implode(' | ', array_slice($errors, 0, 5));
        }

        return redirect()->back()->with('import_report', [
            'type' => $skipped > 0 ? 'warning' : 'success',
            'message' => $message,
        ]);
    }

    private function normalizeStatus($status): int
    {
        $v = strtolower(trim((string) $status));
        return in_array($v, ['0', 'false', 'no', 'inactive'], true) ? 0 : 1;
    }

}
