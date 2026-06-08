<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Repository\Interfaces\UserInterface;

class UserController extends Controller
{
    protected $userRepo;
    public function __construct(UserInterface $user)
    {
        $this->userRepo = $user;
    }
    public function index(Request $request)
    {
        $users = User::all();
        if (\request()->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()

                ->addColumn('role_info', function ($user) {
                    return view('admin.access_control.admin.role', compact('user'));
                })
                ->addColumn('status', function ($user) {
                    switch ($user->is_active) {
                        case 1:
                            return '<div class="badge badge-success">Active</div>';
                        case 0:
                            return '<div class="badge badge-danger">Inactive</div>';
                    }
                })
                ->addColumn('action', function ($user) {
                    return view('admin.access_control.user.action-button', compact('user'));
                })

                ->rawColumns(['status', 'role_info', 'action'])
                ->make(true);
        }
        return view('admin.access_control.user.index');
    }


    public function create()
    {
        $data = [

            'roles' => Role::where('name', '!=', 'Super Admin')->where('name', '!=', 'Developer')->pluck('name', 'id'),
        ];

        return view('admin.access_control.user.create', $data);
    }

    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->only(['name','email', 'mobile']);
            $data['password'] = bcrypt($request->password);
            $user = $this->adminRepo->createAdmin($data);
            $user->assignRole($request->get('roles'));
            DB::commit();
            return response()->successRedirect('Admin Created Successful!', 'admin.admins.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return response()->errorRedirect('Opps Something wrong!', 'admin.admins.index');
        }
    }

    public function show(User $user)
    {
        $data = [
            'model' => $user,
        ];
        return view('admin.access_control.user.show', $data);
    }


    public function edit(User $user)
    {
        $data = [
            'admin' => $user,
            'roles' => Role::where('name', '!=', 'Super Admin')->pluck('name', 'id'),
            'selected_roles' => Role::whereIn('name', $user->getRoleNames())->pluck('id')
        ];
        return view('admin.access_control.user.edit', $data);
    }


    public function update(UserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['password', 'roles']);
            $data['password'] = bcrypt($request->password);
            $this->adminRepo->updateAdmin($data, $user);
            $user->syncRoles($request->get('roles'));
            DB::commit();
            return response()->successRedirect('Info Updated!', 'admin.admins.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return response()->errorRedirect('Opps Something wrong!', 'admin.admin.index');
        }
    }

    public function destroy(User $user)
    {
        $this->userRepo->deleteUser($user);
        return response()->successRedirect('Info Deleted!', 'user.users.index');
    }

    public function login($userId)
    {
        $data['admin'] = \auth('admin')->loginUsingId($userId);
        session(['loggedIn-from-admin' => true]);
        return redirect()->route('admin.dashboard');
    }

    public function passwordReset($userId)
    {
      
        $user = $this->userRepo->getAnInstance($userId);  
       // dd($user);
        $this->userRepo->updateUser(['password' => bcrypt('12345678')], $user);
        return response()->successRedirect('Password Reset', 'user.users.index');
    }
}
