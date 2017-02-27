<?php

namespace App\Api\V1\Requests;

use Config;
use JWTAuth;

class DeleteRestaurantRequest extends AddRestaurantRequest
{
    public function rules()
    {
        return [];
    }
}
