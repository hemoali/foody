<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;
use JWTAuth;

class ReviewRestaurantRequest extends FormRequest
{
    public function rules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

}
