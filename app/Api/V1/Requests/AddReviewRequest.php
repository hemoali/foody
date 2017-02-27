<?php

namespace App\Api\V1\Requests;

use Config;
use JWTAuth;
use Dingo\Api\Http\FormRequest;

class AddReviewRequest extends FormRequest
{
    public function rules()
    {
        // Add review request
        return Config::get('boilerplate.review.store_validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
