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
        $loggedInProfileId = $request->user()->profile->id;
        $path = $request->getPathInfo();   
        if($request->is('*/v1/*'))
        {
            $path = preg_replace('/v1/', 'v', $path);
        }
        else if($request->is('*/v2/*'))
        {
            $path = preg_replace('/v2/', 'v', $path);
        }
       $ids = preg_split('#([/a-zA-Z]+)#', $path);
       $ids = array_reverse($ids);
       $collabId = null;
       foreach ($ids as $id) {
           if($id == '')
               continue;
            $collabId = $id;
       }
        $collab = \DB::table('collaborates')
            // ->whereNull('deleted_at')
            // ->where('state',1)
            ->where('id',$collabId)
            ->first();
        if(is_null($collab))
        {
            return response()->json(['data'=>null,'errors'=>'Invalid Collaboration Project.','messages'=>null], 200);
        }
        
        if($collab->collaborate_type != 'product-review' || (isset($collab->profile_id) && $collab->profile_id == $loggedInProfileId)) {
            return $next($request);
        }
        $companyId = $collab->company_id;
        
        $path = preg_replace('#([0-9]+)#','id',$path); 
        $checkPermissionExist = \DB::table('collaborate_permissions')->where('route',$path)->where('method',$request->method())->count();
        // if(!$checkPermissionExist) {
        //     \DB::table('collaborate_permissions')->insert(['route'=>$path,'method'=>$request->method()]);
        // }//This part is used jus to store information we should comment this after a while  
        
        $checkAdmin = CompanyUser::where('company_id', $companyId)->where('profile_id', $loggedInProfileId)->exists();
        if($checkAdmin) {
            return $next($request);
        }
        if($request->is('*/v1/*'))
        {
            $path = preg_replace('/v/', 'v1', $path);
        }
        else if($request->is('*/v2/*'))
        {
            $path = preg_replace('/v/', 'v2', $path);
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
