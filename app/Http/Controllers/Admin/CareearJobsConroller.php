<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CareearJobsConroller extends Controller
{
    public function index(): Response
    {
        return response('Career jobs module is not implemented yet.', 501);
    }

    public function create(): Response
    {
        return response('Career jobs module is not implemented yet.', 501);
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->back()->with('error', 'Career jobs module is not implemented yet.');
    }

    public function show(string $id): Response
    {
        return response('Career jobs module is not implemented yet.', 501);
    }

    public function edit(string $id): Response
    {
        return response('Career jobs module is not implemented yet.', 501);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        return redirect()->back()->with('error', 'Career jobs module is not implemented yet.');
    }

    public function destroy(string $id): RedirectResponse
    {
        return redirect()->back()->with('error', 'Career jobs module is not implemented yet.');
    }
}

