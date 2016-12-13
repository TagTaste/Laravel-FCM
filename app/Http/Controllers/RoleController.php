<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Role;
use App\Permission;
use Session;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = Permission::getAllPermissionName();
        $roles = Role::with('perms')->get();
        return view('admin.role.view', ['pageTitle' => 'View / Edit Roles', 'permission' => $permission, 'roles' => $roles]);
    }

    /**
     * create function to show add role form.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.role.add', ['pageTitle' => 'Add Role', 'buttonLabel' => 'Create', 'permissions' => $permissions]);
    }

    /**
     * store function to add role and store into database.
     *
     * @param Request $request role form data
     */
    public function store(Request $request)
    {
        $role = Role::create([
                'name' => $request['role_name'],
                'display_name' => $request['role_name'],
                'description' => $request['role_description'],
            ]);
        if ($role) {
            $role->attachPermissions($request['role_permission']);
            return redirect('/admin/role/view')->with("success", "Role added successfully.");
        } else {
            return redirect('/admin/role/view')->with('error', 'Role not added successfully.');
        }   
    }

    /**
     * show function to show details of particular role and editable form.
     *
     * @param int $id role_id
     *
     * @return [null] [description]
     */
    public function show($id)
    {
        $permissions = Permission::all();
        $role = Role::with('perms')->where('id', '=', $id)->first();
        $role_permission = array();
        foreach ($role->perms as $key => $perm) {
            $role_permission[] = $perm->pivot->permission_id;
        }
        if ($role) {
            return view('admin.role.add', ['pageTitle' => 'Edit Role', 'role' => $role, 'permissions' => $permissions, 'role_permission' => $role_permission, 'buttonLabel' => 'Update']);
        } else {
            return redirect('/admin/role/view')->with('error', 'Role not found.');
        }
    }

    /**
     * Update role
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->name = $request['role_name'];
            $role->display_name = $request['role_name'];
            $role->description = $request['role_description'];
            $role->save();

            $role->perms()->sync($request['role_permission']);
            return redirect('/admin/role/view')->with('success', 'Role updated successfully.');
        } else {
            return redirect('/admin/role/view')->with('error', 'Role not updated!');
        }
    }

    /**
     * Remove the specified role resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role) {
            Role::where('id', '=', $id)->delete();
            Session::flash("success", "Role deleted successfully.");
            return 1;
        } else {
            Session::flash("error", "Something went wrong. Please try again.");
            return 0;
        }
    }
}
