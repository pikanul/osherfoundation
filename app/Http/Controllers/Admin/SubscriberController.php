<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    public function index()
    {
        $this->authorizeAccess('subscribers');

        if (\request()->ajax()) {
            $subscribers = Subscriber::query()->select('subscribers.*');

            return DataTables::of($subscribers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $previewButton = '';
                    if (Auth::hasP('subscribers')) {
                        $previewRoute = route('admin.subscribers.preview', $row->id);
                        $previewButton = '<button class="btn btn-sm btn-info mr-1" data-title="Subscriber Preview" data-href="' . $previewRoute . '" onclick="button_ajax(this)" data-dialog="modal-dialog-scrollable modal-md"><i class="fas fa-eye"></i></button>';
                    }

                    return $previewButton . button_g([
                        'delete' => route('admin.subscribers.destroy', $row->id),
                    ], 'Subscriber', true, 'subscribers');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.subscribers.index');
    }

    public function create()
    {
        $this->authorizeAccess('subscribers create');

        $subscriber = null;
        $html = view('admin.subscribers.create_edit', compact('subscriber'))->render();

        return response()->json(['html' => $html, 'success' => true]);
    }

    public function edit($id)
    {
        $this->authorizeAccess('subscribers edit');

        $subscriber = Subscriber::findOrFail($id);
        $html = view('admin.subscribers.create_edit', compact('subscriber'))->render();

        return response()->json(['html' => $html, 'success' => true]);
    }

    public function preview(Subscriber $subscriber)
    {
        $this->authorizeAccess('subscribers');

        $html = view('admin.subscribers.preview', compact('subscriber'))->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function store(Request $request)
    {
        $this->authorizeAccess('subscribers create');
        $request['create'] = 'Subscriber created successfully.';

        return $this->update($request);
    }

    public function update(Request $request, $id = null)
    {
        $this->authorizeAccess($id ? 'subscribers edit' : 'subscribers create');

        $validated = $request->validate([
            'name' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:20|unique:subscribers,phone,' . $id,
            'email' => 'required|email|max:150|unique:subscribers,email,' . $id,
            'status' => 'required|in:0,1,2',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email already exists.',
            'phone.unique' => 'This phone already exists.',
            'status.required' => 'Status is required.',
        ]);

        $validated['email'] = strtolower(trim((string) $validated['email']));
        $validated['status'] = (int) $validated['status'];
        $this->applyStatusDates($validated, $id ? Subscriber::find($id) : null);

        DB::beginTransaction();
        try {
            if ($id) {
                $subscriber = Subscriber::findOrFail($id);
                $subscriber->update($validated);
            } else {
                Subscriber::create($validated);
            }

            DB::commit();

            return response()->json([
                'title' => $request->create ?? 'Subscriber updated successfully.',
                'type' => 'success',
                'refresh' => 'true',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'title' => 'Something went wrong!.',
                'type' => 'error',
                'refresh' => 'false',
            ]);
        }
    }

    public function destroy($id)
    {
        $this->authorizeAccess('subscribers delete');

        try {
            $subscriber = Subscriber::findOrFail($id);
            $subscriber->delete();

            return response()->json([
                'title' => 'Subscriber deleted successfully.',
                'type' => 'success',
                'refresh' => 'true',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'title' => 'Something went wrong!.',
                'type' => 'error',
                'refresh' => 'false',
            ]);
        }
    }

    public function bulkStatusUpdate(Request $request)
    {
        $this->authorizeAccess('subscribers edit');

        $validated = $request->validate([
            'subscriber_ids' => 'required|array|min:1',
            'subscriber_ids.*' => 'required|integer|exists:subscribers,id',
            'status' => 'required|in:0,1,2',
        ]);

        $subscriberIds = collect($validated['subscriber_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
        $status = (int) $validated['status'];

        DB::beginTransaction();
        try {
            $subscribers = Subscriber::whereIn('id', $subscriberIds)->get()->keyBy('id');
            $updated = 0;

            foreach ($subscriberIds as $subscriberId) {
                $subscriber = $subscribers->get($subscriberId);
                if (!$subscriber) {
                    continue;
                }

                $payload = ['status' => $status];
                $this->applyStatusDates($payload, $subscriber);
                $subscriber->update($payload);
                $updated++;
            }

            DB::commit();

            return $this->crudSuccess($updated . ' subscriber(s) status updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->crudError('Failed to update selected subscriber status.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $this->authorizeAccess('subscribers delete');

        $validated = $request->validate([
            'subscriber_ids' => 'required|array|min:1',
            'subscriber_ids.*' => 'required|integer|exists:subscribers,id',
        ]);

        $subscriberIds = collect($validated['subscriber_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        DB::beginTransaction();
        try {
            $deleted = Subscriber::whereIn('id', $subscriberIds)->delete();
            DB::commit();

            return $this->crudSuccess($deleted . ' subscriber(s) deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->crudError('Failed to delete selected subscribers.');
        }
    }

    public function downloadImportTemplate()
    {
        $this->authorizeAccess('subscribers manage');

        $headers = ['email', 'name', 'phone', 'status'];
        $sample = ['john@example.com', 'John Doe', '01700000000', '1'];

        $callback = function () use ($headers, $sample) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fputcsv($out, $sample);
            fclose($out);
        };

        return response()->streamDownload($callback, 'subscriber_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function bulkImport(Request $request)
    {
        $this->authorizeAccess('subscribers manage');

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
        $missing = array_diff(['email'], $headers);
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
                'email' => strtolower((string) ($data['email'] ?? '')),
                'name' => $data['name'] ?? null,
                'phone' => $data['phone'] ?? null,
                'status' => $this->normalizeStatus($data['status'] ?? 1),
                'subscribed_at' => $this->normalizeStatus($data['status'] ?? 1) === 1 ? now() : null,
                'unsubscribed_at' => $this->normalizeStatus($data['status'] ?? 1) === 2 ? now() : null,
            ];

            $validator = Validator::make($payload, [
                'email' => 'required|email|max:150',
                'name' => 'nullable|string|max:150',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:0,1,2',
            ]);

            if ($validator->fails()) {
                $skipped++;
                $errors[] = 'Row ' . $rowNo . ': ' . $validator->errors()->first();
                continue;
            }

            try {
                $subscriber = Subscriber::where('email', $payload['email'])->first();

                if (!$subscriber && !empty($payload['phone'])) {
                    $subscriber = Subscriber::where('phone', $payload['phone'])->first();
                }

                if ($subscriber) {
                    $subscriber->update($payload);
                } else {
                    Subscriber::create($payload);
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

    public function export()
    {
        $this->authorizeAccess('subscribers manage');

        $fileName = 'subscribers_' . now()->format('Ymd_His') . '.csv';

        $callback = function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'name', 'phone', 'email', 'status', 'subscribed_at', 'unsubscribed_at', 'created_at']);

            Subscriber::query()->orderByDesc('id')->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id,
                        $row->name,
                        $row->phone,
                        $row->email,
                        $row->status,
                        $row->subscribed_at,
                        $row->unsubscribed_at,
                        $row->created_at,
                    ]);
                }
            });

            fclose($out);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function normalizeStatus($status): int
    {
        $v = strtolower(trim((string) $status));
        if (in_array($v, ['2', 'unsubscribe', 'unsubscribed'], true)) {
            return 2;
        }

        if (in_array($v, ['0', 'false', 'no', 'inactive'], true)) {
            return 0;
        }

        return 1;
    }

    private function applyStatusDates(array &$payload, ?Subscriber $existing = null): void
    {
        $status = (int) ($payload['status'] ?? 1);
        $now = now();

        if ($status === 1) {
            $payload['subscribed_at'] = $existing?->subscribed_at ?? $now;
            $payload['unsubscribed_at'] = null;
            return;
        }

        if ($status === 2) {
            $payload['unsubscribed_at'] = $existing?->unsubscribed_at ?? $now;
            $payload['subscribed_at'] = $existing?->subscribed_at;
            return;
        }

        $payload['subscribed_at'] = null;
        $payload['unsubscribed_at'] = null;
    }

    private function authorizeAccess(string $permission): void
    {
        if (!Auth::hasP($permission)) {
            abort(403);
        }
    }
}
