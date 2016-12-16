<?php
/**
 * Created by PhpStorm.
 * User: amitabh
 * Date: 16/12/16
 * Time: 2:58 PM
 */

namespace App\Exceptions\Auth;

use Exception;

class SocialProviderAccountNotFound extends Exception
{
    public function __construct($provider)
    {
        parent::__construct("Social Account for $provider Provider Not Found for User.");
    }
}