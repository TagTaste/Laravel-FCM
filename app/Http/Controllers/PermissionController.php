<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Permission;
use Session;

class PermissionController extends Controller
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
     * Show the application permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permission.view', ['pageTitle' => 'View / Edit Permissions', 'permissions' => $permissions]);
    }

    /**
     * create function to show add permission form.
     */
    public function create()
    {
        return view('admin.permission.add', ['pageTitle' => 'Add Permission', 'buttonLabel' => 'Create']);
    }

    /**
     * store function to add permission and store into database.
     *
     * @param Request $request role form data
     */
    public function store(Request $request)
    {
        $permission = Permission::create([
                'name' => $request['permission_name'],
                'display_name' => $request['permission_name'],
                'description' => $request['permission_description'],
            ]);
        if ($permission) {
            return redirect('/admin/permission/view')->with("success", "Permission added successfully.");
        } else {
            return redirect('/admin/permission/view')->with('error', 'Permission not added successfully.');
        }
    }

    /**
     * show function to show details of particular permission and editable form.
     *
     * @param int $id permission_id
     *
     * @return [null] [description]
     */
    public function show($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            return view('admin.permission.add', ['pageTitle' => 'Edit Permission', 'permission' => $permission, 'buttonLabel' => 'Update']);
        } else {
            return redirect('/admin/permission/view')->with('error', 'Permission not found.');
        }
    }

    /**
     * Update permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->name = $request['permission_name'];
            $permission->display_name = $request['permission_name'];
            $permission->description = $request['permission_description'];
            $permission->save();

            return redirect('/admin/permission/view')->with('success', 'Permission updated successfully.');
        } else {
            return redirect('/admin/permission/view')->with('error', 'Permission not updated!');
        }
    }

    /**
     * Remove the specified permission resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        if ($permission) {
            $permission->delete();
            Session::flash("success", "Permission deleted successfully.");
            return 1;
        } else {
            Session::flash("error", "Something went wrong. Please try again.");
            return 0;
        }
    }
}
