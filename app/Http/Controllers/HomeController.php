<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class HomeController extends Controller
{
    public function paginate($page)
    {
        return $this->showMainPage($page);
    }

    public function showMainPage($page = 1)
    {
        // Retreive data
        // Get restaurants
        $restaurants = $this->getRestaurants($page);
        $restaurantsData = [];
        if ($restaurants != "Error") {
            $restaurants = json_decode($restaurants, true);
            $currentPage = $restaurants["current_page"];
            $lastPage = $restaurants["last_page"];
            $restaurantsData = $restaurants["data"];
        }

        // Get user appointments
        $appointments = $this->getUserAppointments();
        $appointmentsData = [];
        if ($appointments != "Error") {
            $appointments = json_decode($appointments, true);
            $appointmentsData = $appointments["appointments"];
        }
        // Get user invitations
        $invitations = $this->getUserInvitations();
        $invitationsData = [];
        if ($invitations != "Error") {
            $invitations = json_decode($invitations, true);
            $invitationsData = $invitations;
        }

        return view('welcome')->with("invitations", $invitationsData)->with("appointments", $appointmentsData)->with("restaurants", $restaurantsData)->with("currentPage", $currentPage)->with("lastPage", $lastPage);
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Fetch data
        $email = $request->get('email');
        $pass = $request->get('password');
        // Call API to Login
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/auth/login");
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('email' => $email, 'password' => $pass)));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_status == 200) {
            $result = json_decode($result, true);
            if (array_key_exists('status', $result) && $result['status'] == 'ok') {
                session(['token' => $result['token']]);
                curl_close($ch);
                return redirect('/');
            }
        }
        curl_close($ch);
        session()->flash('error-msg', json_encode($result));
        return back();
    }

    public function showSignupForm()
    {
        return view('signup');
    }

    public function doSignup(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password1' => 'required',
            'password2' => 'required|same:password1',
            'name' => 'required',
        ]);
        // Fetch data
        $email = $request->get('email');
        $pass = $request->get('password1');
        $name = $request->get('name');
        $role = Config::get('constants.USER_ROLES.USER');


        // Call API to Signup
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/auth/signup");
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('email' => $email, 'password' => $pass, 'name' => $name, 'role' => $role)));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_status == 201) {
            $result = json_decode($result, true);
            if (array_key_exists('status', $result) && $result['status'] == 'ok') {
                session(['token' => $result['token']]);
                curl_close($ch);
                return redirect('/');
            }
        }
        curl_close($ch);
        session()->flash('error-msg', $result);
        return back();
    }

    public function getRestaurants($page)
    {
        // Call API to get restaurants
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/restaurant?page=$page");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_status == 200) {
            return $result;
        } else {
            return "Error";
        }
    }

    public function getUserAppointments()
    {
        // Call API to get restaurants
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/myappointments?token=".session('token'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_status == 200) {
            return $result;
        } else {
            return "Error";
        }
    }
    public function getUserInvitations()
    {
        // Call API to get restaurants
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/othersappointments?token=".session('token'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_status == 200) {
            return $result;
        } else {
            return "Error";
        }
    }

    public function accept(Request $request){
        $this->validate($request, [
            'invitation' => 'required'
        ]);
        // Fetch data
        $invitation = $request->get('invitation');
        // Call API to Login
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/accept/$invitation?token=".session('token'));
        curl_setopt($ch, CURLOPT_PUT, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        return back();
    }
    public function reject(Request $request){
        $this->validate($request, [
            'invitation' => 'required'
        ]);
        // Fetch data
        $invitation = $request->get('invitation');
        // Call API to Login
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/decline/$invitation?token=".session('token'));
        curl_setopt($ch, CURLOPT_PUT, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        return back();
    }
}
