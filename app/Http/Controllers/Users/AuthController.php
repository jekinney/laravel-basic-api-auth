<?php

namespace App\Http\Controllers\Users;

use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterForm;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected $user;

    function __construct(User $user) 
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
    	$response = $this->user->authAttempt($request->only('email', 'password'));

    	return response()->json($response['payload'], $response['status']);
    }

    public function register(RegisterForm $request)
    {
    	$response = $this->user->register($request);

    	return response()->json($response['payload'], $response['status']);
    }

    public function user()
    {
        $user = $this->user->auth();

        return response()->json($user);
    }
}
