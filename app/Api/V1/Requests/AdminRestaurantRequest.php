<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;
use JWTAuth;

class AdminRestaurantRequest extends FormRequest
{

    public function authorize()
    {
        // Check if admin
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser->role == Config::get('constants.USER_ROLES.ADMIN'))
            return true;

        return false;
    }
}
