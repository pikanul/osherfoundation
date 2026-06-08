<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Repository\Interfaces\RoleInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $role;

    public function __construct(RoleInterface $role)
    {
        $this->role = $role;
    }

    public function index(Request $request)
    {
        if (\request()->ajax()) {
            $roles = $this->role->allRoleList([], '*', []);
            return DataTables::of($roles)
            ->filter(function ($roles) use ($request) {
                if ($request->has('name')) {
                    $roles->where('name', 'like', "%{$request->get('name')}%");
                }
            })
                ->addIndexColumn()
                ->addColumn('role_name', function ($role) {
                    return ucfirst($role->name);
                })
                ->addColumn('guard_name', function ($role) {
                    return ucfirst($role->guard_name);
                })
                ->addColumn('action', function ($role) {
                    return view('admin.access_control.role.action-button', compact('role'));
                })
                ->rawColumns(['status', 'permission_name', 'guard_name', 'action', 'group_name'])
                ->tojson();
        }
        return view('admin.access_control.role.index');
    }

    public function create()
    {

        // if (is_null($this->user) || !$this->user->can('role.create')) {
        //     abort(403, 'Sorry !! You are Unauthorized to create any role !');
        // }

        $data = [
            'model' => new Role,
            'all_permissions' => Permission::get(),
            'permission_groups' => User::getpermissionGroups(),
        ];
//dd($data);

        return view('admin.access_control.role.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('user.roles.index')->with('success', 'Role information created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit(Role $role)
    {
        $data = [
            'model' => $role,
            'all_permissions' => Permission::get(),
            'permission_groups' => User::getpermissionGroups(),

        ];
        return view('admin.access_control.role.edit', $data);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('user.roles.index')->with('success', 'Role information updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully.');

    }
}
