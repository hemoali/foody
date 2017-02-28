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

        // Get all Users
        $users = $this->getAllUsers();
        if ($users != "Error") {
            $users = json_decode($users, true);
        }

        return view('welcome')->with("users", (($users != "Error") ? $users['users']:[]))->with("invitations", $invitationsData)->with("appointments", $appointmentsData)->with("restaurants", $restaurantsData)->with("currentPage", $currentPage)->with("lastPage", $lastPage);
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
                session(['role' => $result['role']]);
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
        curl_close($ch);
        if ($http_status == 200) {
            return $result;
        } else {
            session()->flash('error-msg', "Please try again later!");
        }
    }

    public function getUserAppointments()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/myappointments?token=" . session('token'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status == 200) {
            return $result;
        } else {
            session()->flash('error-msg', "Please try again later!");
        }
    }

    public function getUserInvitations()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/othersappointments?token=" . session('token'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status == 200) {
            return $result;
        } else {
            session()->flash('error-msg', "Please try again later!");
        }
    }

    public function accept(Request $request)
    {
        $this->validate($request, [
            'invitation' => 'required'
        ]);
        // Fetch data
        $invitation = $request->get('invitation');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/accept/$invitation?token=" . session('token'));
        curl_setopt($ch, CURLOPT_PUT, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        curl_close($ch);
        return back();
    }

    public function reject(Request $request)
    {
        $this->validate($request, [
            'invitation' => 'required'
        ]);
        // Fetch data
        $invitation = $request->get('invitation');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/decline/$invitation?token=" . session('token'));
        curl_setopt($ch, CURLOPT_PUT, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        curl_close($ch);
        return back();
    }

    public function getAllUsers()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/users?token=" . session('token'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status == 200) {
            return $result;
        } else {
            return "Error";
        }
    }


    public function inviteUsers(Request $request)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/appointment?token=" . session('token'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($request->all()));

        curl_exec($ch);
        curl_close($ch);
        return back();
    }

    public function editRestaurant(Request $request)
    {
        $this->validate($request, [
            'restaurant_id' => 'required',
            'name' => 'required',
            'location' => 'required',
            'link' => 'required',
            'phone_number' => 'required',
            'desc' => 'required'
        ]);

        $restID = $request->get('restaurant_id');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/restaurant/$restID?token=" . session('token'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($request->all()));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        (curl_exec($ch));
        curl_close($ch);
        return back();
    }

    public function addRestaurant(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'link' => 'required',
            'phone_number' => 'required',
            'desc' => 'required'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8001/api/restaurant?token=" . session('token'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($request->all()));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        (curl_exec($ch));
        curl_close($ch);
        return back();
    }

    public function deleteRestaurant(Request $request, $restID)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8001/api/restaurant/$restID?token=" . session('token'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return back();
    }

}
