<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffRole;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('user_type', 'admin')->with('staffRoles')->latest()->paginate(20);
        return Inertia::render('Admin/Staff/Index', ['staff' => $staff]);
    }

    public function create()
    {
        $roles = StaffRole::all();
        return Inertia::render('Admin/Staff/Create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:staff_roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'user_type' => 'admin',
        ]);

        if (!empty($validated['role_id'])) {
            $user->staffRoles()->attach($validated['role_id']);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->back()->with('success', 'Staff member removed.');
    }

    // Roles
    public function roles()
    {
        $roles = StaffRole::withCount('users')->latest()->get();
        $allPermissions = [
            'products', 'categories', 'brands', 'attributes', 'colors',
            'orders', 'pos', 'customers', 'sellers', 'refunds',
            'flash_deals', 'coupons', 'newsletter', 'blog', 'support',
            'staff', 'reports', 'settings', 'pages', 'shipping', 'file_manager',
        ];
        return Inertia::render('Admin/Staff/Roles', ['roles' => $roles, 'allPermissions' => $allPermissions]);
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        StaffRole::create($validated);
        return redirect()->back()->with('success', 'Role created.');
    }

    public function destroyRole(StaffRole $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted.');
    }
}
