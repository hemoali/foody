<?php

namespace App\Api\V1\Requests;

use Config;
use JWTAuth;
use Dingo\Api\Http\FormRequest;

class AddAppointmentRequest extends FormRequest
{
    public function rules()
    {
        // Add review request
        return Config::get('boilerplate.appointment.store_validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
