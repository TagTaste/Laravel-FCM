<?php
/**
 * Created by PhpStorm.
 * User: ashok
 * Date: 19/08/17
 * Time: 4:23 PM
 */
namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $table = 'affiliations';

    protected $fillable = ['title'];

    protected $visible = ['id','title'];

}
