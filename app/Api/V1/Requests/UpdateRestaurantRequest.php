<?php

namespace App\Api\V1\Requests;

use Config;
use JWTAuth;

class UpdateRestaurantRequest extends AdminRestaurantRequest
{
    public function rules()
    {
        // Add restaurant request
        return Config::get('boilerplate.restaurant.store_validation_rules');
    }
}
