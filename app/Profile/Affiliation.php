<?php
/**
 * Created by PhpStorm.
 * User: ashok
 * Date: 19/08/17
 * Time: 4:23 PM
 */
namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $table = 'profile_affiliations';

    protected $fillable = ['title','profile_id'];

    protected $visible = ['id','title'];

}
