<?php

namespace App\Users;

use JWTAuth;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function setPasswordAttribute($password)
    {
        return $this->attributes['password'] = bcrypt($password);
    }

    public function authAttempt($credentials) 
    {
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return ['payload' => ['error' => 'invalid_credentials'], 'status' => 401];
            }
        } catch (JWTException $e) {
            return ['payload' => ['error' => 'could_not_create_token'], 'status' => 500];
        }

        return ['payload' => ['token' => $token], 'status' => 200];
    }

    public function register($request)
    {
        $credentials = $this->create($request->all());

        return ['payload' => ['token' => JWTAuth::fromUser($credentials)], 'status' => 200];
    }

    public function auth()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return ['payload' => ['error' => 'user_not_found'], 'staus' => 404];
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return ['payload' => ['error' => 'token_expired'], 'status' => $e->getStatusCode()];
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return ['payload' => ['error' => 'token_invalid'], 'status' => $e->getStatusCode()];
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return ['payload' => ['error' => 'token_absent'], 'status' => $e->getStatusCode()];
        }
        return $user;
    }
}
