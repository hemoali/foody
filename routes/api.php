<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
    });

    $api->get('restaurant/search/{q}', 'App\\Api\\V1\\Controllers\\RestaurantController@search');
    $api->get('restaurant', 'App\\Api\\V1\\Controllers\\RestaurantController@fetch');
    $api->get('reviews/{id}', 'App\\Api\\V1\\Controllers\\RestaurantController@reviews');


    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->post('restaurant', 'App\\Api\\V1\\Controllers\\RestaurantController@store');
        $api->delete('restaurant/{id}', 'App\\Api\\V1\\Controllers\\RestaurantController@destroy');
        $api->put('restaurant/{id}', 'App\\Api\\V1\\Controllers\\RestaurantController@update');
        $api->get('myappointments', 'App\\Api\\V1\\Controllers\\UserController@fetchMyAppointments');
        $api->get('othersappointments', 'App\\Api\\V1\\Controllers\\UserController@fetchOthersAppointments');
        $api->put('accept/{invitation_id}', 'App\\Api\\V1\\Controllers\\UserController@acceptAppointment');
        $api->put('decline/{invitation_id}', 'App\\Api\\V1\\Controllers\\UserController@declineAppointment');

        $api->get('users', 'App\\Api\\V1\\Controllers\\UserController@getUsers');

        $api->post('review', 'App\\Api\\V1\\Controllers\\ReviewController@store');

        $api->post('appointment', 'App\\Api\\V1\\Controllers\\AppointmentController@store');

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

});
