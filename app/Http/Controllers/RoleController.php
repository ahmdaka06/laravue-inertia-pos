<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        //get roles
        $roles = Role::when(request()->q, function($roles) {
            $roles = $roles->where('name', 'like', '%'. request()->q . '%');
        })->with('permissions')->latest()->paginate(5);

        //render with inertia
        return inertia('Apps/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        //get permission all
        $permissions = Permission::all();

        //render with inertia
        return inertia('Apps/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        /**
         * Validate request
         */
        $this->validate($request, [
            'name'          => 'required',
            'permissions'   => 'required',
        ]);

        //create role
        $role = Role::create(['name' => $request->name]);

        //assign permissions to role
        $role->givePermissionTo($request->permissions);

        //redirect
        return redirect()->route('apps.roles.index')->with([
            'success' => 'Role created successfully!.',
        ]);
    }

    public function edit($id)
    {
        //get role
        $role = Role::with('permissions')->findOrFail($id);

        //get permission all
        $permissions = Permission::all();

        //render with inertia
        return inertia('Apps/Roles/Edit', [
            'role'          => $role,
            'permissions'   => $permissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        /**
         * validate request
         */
        $this->validate($request, [
            'name'          => 'required',
            'permissions'   => 'required',
        ]);

        //update role
        $role->update(['name' => $request->name]);

        //sync permissions
        $role->syncPermissions($request->permissions);

        //redirect
        return redirect()->route('apps.roles.index')->with([
            'success' => 'Role updated successfully!.',
        ]);
    }

    public function destroy($id)
    {
        //find role by ID
        $role = Role::findOrFail($id);

        //delete role
        $role->delete();

        //redirect
        return redirect()->route('apps.roles.index');
    }
}
