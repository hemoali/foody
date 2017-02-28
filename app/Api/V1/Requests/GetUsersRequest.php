<?php

namespace App\Api\V1\Requests;

use Config;
use JWTAuth;
use Dingo\Api\Http\FormRequest;

class GetUsersRequest extends FormRequest
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
