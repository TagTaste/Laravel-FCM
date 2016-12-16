<?php
/**
 * Created by PhpStorm.
 * User: amitabh
 * Date: 16/12/16
 * Time: 3:21 PM
 */

namespace App\Exceptions\Auth;

use Exception;

class SocialAccountUserNotFound extends Exception
{
    public function __construct($provider)
    {
        parent::__construct("Social Account for $provider Provider Not Found for User.");
    }
}