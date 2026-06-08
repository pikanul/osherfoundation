<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionDataTable;
use App\Repository\Interfaces\PermissionInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permission;

    public function __construct(PermissionInterface $permission)
    {
        $this->permission = $permission;

        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('admin.access_control.permission.index');
    }

    public function create()
    {
        return view('admin.access_control.permission.create', [
            'model' => new Permission,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('permissions')->where('guard_name', 'admin'),
            ],
        ]);

        Permission::create([
            'name' => $request->input('name'),
            'group_name' => $request->input('group_name'),
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission information created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return view('admin.access_control.permission.edit', [
            'model' => Permission::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->group_name = $request->input('group_name');
        $permission->save();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission information updated successfully.');
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully.');
    }
}
