<?php 

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
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
     * Set the role name with small letter and underscore separated
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
}