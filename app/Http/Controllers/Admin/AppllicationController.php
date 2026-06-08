<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppllicationController extends Controller
{
    public function index(): View
    {
        return view('admin.application.index');
    }

    public function create(): View
    {
        $application = null;

        return view('admin.application.create_edit', compact('application'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('traning_apply_lists')->insert($data);

        return redirect()->route('admin.application.index')
            ->with('success', 'Application created successfully.');
    }

    public function edit(string $id): View
    {
        $application = DB::table('traning_apply_lists')->where('id', $id)->first();
        abort_if(!$application, 404);

        return view('admin.application.create_edit', compact('application'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['updated_at'] = now();

        DB::table('traning_apply_lists')->where('id', $id)->update($data);

        return redirect()->route('admin.application.index')
            ->with('success', 'Application updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        DB::table('traning_apply_lists')->where('id', $id)->delete();

        return redirect()->route('admin.application.index')
            ->with('success', 'Application deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'si' => ['required'],
            'roll' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'father' => ['required', 'string', 'max:255'],
            'mother' => ['required', 'string', 'max:255'],
            'nid' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date'],
            'phone' => ['required', 'string', 'max:50'],
            'course_id' => ['required'],
            'grade' => ['nullable', 'string', 'max:50'],
            'attendance' => ['nullable', 'string', 'max:50'],
            'written' => ['nullable', 'string', 'max:50'],
            'practical' => ['nullable', 'string', 'max:50'],
            'total' => ['nullable', 'string', 'max:50'],
            'application_status' => ['required', 'in:pending,approved,rejected'],
        ]);
    }
}

