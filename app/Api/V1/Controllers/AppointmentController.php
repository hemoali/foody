<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use App\Book;
use App\User;
use Dingo\Api\Routing\Helpers;
use App\Api\V1\Requests\AddAppointmentRequest;
use App\Appointment;

class AppointmentController extends Controller
{
    use Helpers;

    public function store(AddAppointmentRequest $request)
    {
        $appointment = new Appointment($request->all());
        $appointment->restaurant()->associate($request->get('restaurant_id'));
        $appointment->user()->associate(JWTAuth::parseToken()->authenticate());


        if ($appointment->save()) {
            //Invite others
            $usersIDsArray = explode(',', $request->get('users'));
            foreach ($usersIDsArray as $email) {
                if (strlen(trim($email)) > 0) {
                    $user = User::where('email', $email)->first();
                    $appointment->users()->save($user);
                }
            }
            return $this->response->created();
        } else
            return $this->response->error('could_not_add_appointment', 500);
    }

}
