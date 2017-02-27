<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Config;
use App\Book;
use Dingo\Api\Routing\Helpers;
use App\Api\V1\Requests\GetUserAppointmentsRequest;
use App\Api\V1\Requests\AcceptAppointmentRequest;

class UserController extends Controller
{
    use Helpers;

    public function fetchMyAppointments(GetUserAppointmentsRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->appointments()->get();
    }

    public function fetchOthersAppointments(GetUserAppointmentsRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->user_appointments()->get();
    }

    public function acceptAppointment(AcceptAppointmentRequest $request, $invitation_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $appointment = $user->user_appointments()->find($invitation_id)->pivot;
        $appointment->status = Config::get('constants.APPOINTMENT_STATUS.ACCEPTED');
        $appointment->save();
        return $this->response->noContent();
    }
    public function declineAppointment(AcceptAppointmentRequest $request, $invitation_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $appointment = $user->user_appointments()->find($invitation_id)->pivot;
        $appointment->status = Config::get('constants.APPOINTMENT_STATUS.REJECTED');
        $appointment->save();
        return $this->response->noContent();
    }
}
