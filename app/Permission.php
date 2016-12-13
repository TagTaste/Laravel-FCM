<?php 

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name', 'description',
    ];

	/**
     * Set the permission name with small letter and underscore separated
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower(str_replace(' ', '_', $value));
    }

    /**
     * Set the display name with first letter in capital
     *
     * @param  string  $value
     * @return void
     */
    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = ucwords($value);
    }

    /**
     * Set the description with first letter in capital
     *
     * @param  string  $value
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }

    /**
     * return all permission name
     */
    public static function getAllPermissionName(){
        $permissions = Permission::all();
        $result = [];
        foreach ($permissions as $permission) {
            $result[$permission->id] = $permission->name;
        }
        return $result;
    }
}