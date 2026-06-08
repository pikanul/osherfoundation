<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminRequest;
use Auth;
use Spatie\Permission\Models\Role;

use App\Models\Admin;
use App\Repository\Interfaces\AdminInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        if(!Auth::hasP('users')){ return abort(403);}

        $admins = Admin::query();
        if (\request()->ajax()) {
            return DataTables::of($admins)



                ->addColumn('status', function ($admin) {
                    switch ($admin->is_active) {
                        case 1:
                            return '<div class="badge badge-success">Active</div>';
                        case 0:
                            return '<div class="badge badge-danger">Inactive</div>';
                    }
                })
                ->addColumn('action', function ($admin) {
                    return button_g([
                        'edit' => route('admin.admin.edit', $admin->id),
                        'delete' => route('admin.admin.destroy', $admin->id),
                        'login' => route('admin.admin.login', $admin->id),
                        'permission' => route('admin.admin.show', $admin->id)

                    ]);

                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.access_control.user.index');
    }


    public function create()
    {
        $data = [
            'admin' => null
        ];

        $html =  view('admin.access_control.user.create_edit', $data)->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function store(Request $request)
    {
        $request['create'] = 'Admin Create Successfully';
        return $this->update($request);
    }

    public function show(Admin $admin)
    {

        $permissions = config('permission', []);
        $grouped = [];

        foreach ($permissions as $perm) {
            // split at first space
            $parts = explode(' ', $perm, 2);
            $group = $parts[0];
            $grouped[$group][] = $perm;
        }

        if($admin->id == 1){
            $current_permission = $permissions;
        }else{
            $current_permission = $admin->permissions ? explode(',', $admin->permissions) : [];
            $current_permission = array_values(array_intersect($current_permission, $permissions));
        }
        $data = [
            'admin' => $admin,
            'permissions' => $grouped,
            'current_permission' => $current_permission

        ];
        $html = view('admin.access_control.user.show', $data)->render();
        return response()->json(['html' => $html, 'success' => true]);
    }

    public function update_permission(Request $request, Admin $admin)
    {
        $themePermissions = config('permission', []);
        $requestedPermissions = (array) $request->input('permission', []);
        $allowedPermissions = array_values(array_intersect($requestedPermissions, $themePermissions));

        $admin->permissions = implode(',', $allowedPermissions);
        $admin->save();
        return response()->json([
            'status' => true,
            'title' => 'Permission Updated Successfully',
            'type' => 'success',
            'refresh' => 'true',
        ]);
    }


    public function edit(Admin $admin)
    {
        $data = [
            'admin' => $admin,
        ];
        $html = view('admin.access_control.user.create_edit', $data)->render();
        return response()->json(['html' => $html, 'success' => true]);
    }


    public function update(Request $request, $admin = null){


        $validated = $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin),
            ],
            'mobile' => 'required',
            'password' => 'nullable|confirmed',
            'degination' => 'required',
        ], [
            'name.required' => 'Name is required',
            'email.unique' => 'Email already exists',
            'email.required' => 'Email is required',
            'mobile.required' => 'Phone is required',
            'password.confirmed' => 'Password not matched',
        ]);



        $s_data = [];
        try {
        DB::beginTransaction();

        if ($request->password && $request->password != '' && $request->password == $request->password_confirmation) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
            if (!$admin) {
                return response()->json(['title' => 'Password not matched', 'type' => 'error']);
            }
        }

        if ($admin) {
            $admin = Admin::find($admin);
            $admin->update($validated);
        } else {
            $admin = Admin::create($validated);
        }

        $s_data = [
            'title' => isset($request->create) ? $request->create : 'Successfully Updated Admin',
            'type' => 'success',
            'refresh' => 'true',
        ];

        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $s_data = [
                'title' => isset($request->create) ? 'Something went wrong!.' : 'Something went wrong!.',
                'type' => 'error',
                'refresh' => 'true',
            ];


        }

        return response()->json($s_data);
    }

    public function destroy(Admin $admin)
    {

        // return $admin;
        $admin->delete();
        return response()->json([
            'title' => 'Admin Deleted Successfully',
            'type' => 'success',
            'refresh' => 'true',
        ]);
    }

    public function login($adminId)
    {
        $data['admin'] = \auth('admin')->loginUsingId($adminId);
        session(['loggedIn-from-admin' => true]);
        return redirect()->route('admin.dashboard');
    }

    public function profile()
    {
        $admin = auth('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }


    public function profileUpdate(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'mobile' => 'nullable|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Name is required',
            'email.unique' => 'Email already exists',
            'email.required' => 'Email is required',
            'profile_image.image' => 'Profile picture must be an image',
            'profile_image.mimes' => 'Profile picture must be jpg, jpeg, png, or webp',
            'profile_image.max' => 'Profile picture size must be below 2MB',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('profile_image')) {
                if ($admin->profile_image) {
                    $oldImagePath = public_path('upload/' . $admin->profile_image);
                    if (is_file($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $profileImageFile = $request->file('profile_image');
                $uploadDirectory = public_path('upload/admin');
                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }
                $profileImageName = 'admin/profile_' . time() . '_' . uniqid() . '.' . $profileImageFile->getClientOriginalExtension();
                $profileImageFile->move($uploadDirectory, basename($profileImageName));
                $validated['profile_image'] = $profileImageName;
            }

            $admin->update($validated);

            DB::commit();

            return $this->profileResponse($request, true, 'Profile updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update failed: ' . $e->getMessage());

            return $this->profileResponse($request, false, 'Something went wrong! Please try again.');
        }
    }

    public function profilePasswordUpdate(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Current password is required',
            'password.required' => 'New password is required',
            'password.min' => 'New password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.different' => 'New password must be different from current password',
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return $this->profileResponse($request, false, 'Current password is incorrect.');
        }

        $admin->update([
            'password' => bcrypt($validated['password']),
        ]);

        return $this->profileResponse($request, true, 'Password updated successfully.');
    }

    private function profileResponse(Request $request, bool $isSuccess, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'title' => $message,
                'type' => $isSuccess ? 'success' : 'error',
                'refresh' => $isSuccess ? 'true' : 'false',
            ]);
        }

        return redirect()->back()->with($isSuccess ? 'success' : 'error', $message);
    }



    public function select (Request $request)
    {
        $data_result = Admin::where(function($query) use ($request) {
            if ($request->has('q')) {
                $query->where('name', 'LIKE', '%' . $request->q . '%');

                $query->orWhere('email', 'LIKE', '%' . $request->q . '%');
                $query->orWhere('mobile', 'LIKE', '%' . $request->q . '%');
                $query->orWhere('degination', 'LIKE', '%' . $request->q . '%');
            }

        })->select('admins.*')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'text' => $student->name . ' - ' . $student->mobile . ' (' . $student->degination . ')',
            ];
        });

        $result_make = [];
       $result_make['items'] = array_merge(
            [
                ['id' => '', 'text' => 'Select Admin']
            ],
            $data_result->toArray()
        );



        return response()->json($result_make);

    }





}
