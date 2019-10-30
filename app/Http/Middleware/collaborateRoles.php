<?php

namespace App\Http\Middleware;

use App\CompanyUser;
use Closure;

class collaborateRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->getPathInfo();
       $ids = preg_split('#([/a-zA-Z]+)#', $path);
       $ids = array_reverse($ids);
       $collabId = null;
       foreach ($ids as $id) {
           if($id == '')
               continue;
            $collabId = $id;
       }
        $collab = \DB::table('collaborates')
            ->whereNull('deleted_at')
            ->where('state',1)
            ->where('id',$collabId)
            ->first();
        $companyId = $collab->company_id;
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = CompanyUser::where('company_id', $companyId)->where('profile_id', $loggedInProfileId)->exists();
        if($checkAdmin) {
            return $next($request);
        }
        $path = preg_replace('#([0-9]+)#','id',$path);
        $permission  = \DB::table('collaborate_user_roles')
            ->where('profile_id',$loggedInProfileId)
            ->where('collaborate_id',$collabId)
            ->leftJoin('collaborate_role_permissions','collaborate_user_roles.role_id','=','collaborate_role_permissions.role_id')
            ->join('collaborate_permissions','collaborate_role_permissions.permission_id','=','collaborate_permissions.id')
            ->where('collaborate_permissions.route',$path)
            ->where('collaborate_permissions.method',$request->method())
            ->count();
        if($permission) {
            return $next($request);
        } else {
            return response()->json(['error'=>'permission_denied'], 401);
        }

    }
}
