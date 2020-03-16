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
        // if($request->isMethod("GET")){
        //     return $next($request);
        // }
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
        if($collab->collaborate_type != 'product-review') {
            return $next($request);
        }
        $companyId = $collab->company_id;
        $loggedInProfileId = $request->user()->profile->id;
        
        $path = preg_replace('#([0-9]+)#','id',$path); 
        $checkPermissionExist = \DB::table('collaborate_permissions')->where('route',$path)->where('method',$request->method())->count();
        // if(!$checkPermissionExist) {
        //     \DB::table('collaborate_permissions')->insert(['route'=>$path,'method'=>$request->method()]);
        // }//This part is used jus to store information we should comment this after a while  
        
        $checkAdmin = CompanyUser::where('company_id', $companyId)->where('profile_id', $loggedInProfileId)->exists();
        if($checkAdmin) {
            return $next($request);
        }
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
            return response()->json(['data'=>null,'error'=>'permission_denied','status'=>'403'], 403);
        }

    }
}
