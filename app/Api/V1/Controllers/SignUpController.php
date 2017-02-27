<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        try {
            $user->save();
        } catch ( \Illuminate\Database\QueryException $e) {
            return($e->errorInfo);
        }
        if (!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }
}
