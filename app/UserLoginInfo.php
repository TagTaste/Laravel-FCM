<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLoginInfo extends Model
{
    use SoftDeletes;

    protected $table = "user_login_info";
    protected $fillable = ['profile_id','platform','jwt_token'];

    /**
     * Store User's login information in the user_login_info table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($user_login_info)
    {
        $result = UserLoginInfo::create($user_login_info);
        return $result;
    }

    /**
     * Remove login info data at the time of logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function remove($token)
    {
        $result = UserLoginInfo::where('jwt_token', $token)->delete();
        return $result;
    }

    /**
     * Remove multiple login info data at the time of logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function removeMultiple($tokens)
    {
        $result = UserLoginInfo::whereIn('jwt_token', $tokens)->delete();
        return $result;
    }

    /**
     * Get some specific column data
     * @param  array $columns
     * @return \Illuminate\Http\Response
     */
    public static function getSpecificLoginInfo($columns)
    {
        $result = UserLoginInfo::get($columns);
        return $result;
    }

    /**
     * Get some specific column data based on some conditions
     * @param  array $condition
     * @param  array $columns
     * @return \Illuminate\Http\Response
     */
    public static function getSpecificLoginInfoBasedOnCondition($condition, $columns)
    {
        $result = UserLoginInfo::where($condition)->get($columns);
        return $result;
    }
}

