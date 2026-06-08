<?php

namespace App\Http\Controllers\User;

use App\DataTables\PermissionDataTable;
use App\Repository\Interfaces\PermissionInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
//        $data = [
//            'permissions' => Permission::all(),
//        ];
        // if (\request()->ajax()) {
        // $permissions = $this->permission->allPermissionList([], '*', []);
        //     return DataTables::of($permissions)
        //         ->addIndexColumn()

        //         ->addColumn('permission_name', function ($permission) {
        //             return ucfirst(str_replace('-', ' ', $permission->name));
        //         })
        //         ->addColumn('guard_name', function ($permission) {
        //             return ucfirst($permission->guard_name);
        //         })
        //         ->addColumn('group_name', function ($permission) {
        //             return ucfirst(str_replace('-', ' ', $permission->group_name));
        //         })
        //         ->addColumn('action', function ($permission) {
        //             return view('admin.access_control.permission.action-button', compact('permission'));
        //         })
        //         ->rawColumns(['status', 'permission_name', 'guard_name', 'action', 'group_name'])
        //         ->tojson();
        // }
       // return view('admin.access_control.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'model' => new Permission,
        ];

        return view('admin.access_control.permission.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           // 'name' => 'required|unique:permissions,name',
            'name' => [
                'required',
                'string',
                Rule::unique('permissions')->where('guard_name', 'admin')
            ],
        ]);

        $permission = Permission::create(['name' => $request->input('name'),'group_name' => $request->input('group_name')]);


        return redirect()->route('admin.permissions.index')->with('success', 'Permission information created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'model' => Permission::find($id),

        ];
        return view('admin.access_control.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully.');
    }
}
