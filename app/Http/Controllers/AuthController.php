<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\User;
use Validator;

class AuthController extends Controller
{
  /**
  * Create a new AuthController instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth.api', ['except' => ['login', 'register']]);
  }

  /**
  * Get a JWT token via given credentials.
  *
  * @param  \Illuminate\Http\Request  $request
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if ($token = $this->guard()->attempt($credentials)) {
      return $this->respondWithToken($token);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
  }

  /**
  * Get the authenticated User
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function me()
  {
    return response()->json($this->guard()->user());
  }

  /**
  * Log the user out (Invalidate the token)
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function logout()
  {
    $this->guard()->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
  * Refresh a token.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function refresh()
  {
    return $this->respondWithToken($this->guard()->refresh());
  }

  /**
  * User registration
  *
  * @param  \Illuminate\Http\Request  $request
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|min:2|regex:/^[\pL\s\-]+$/u',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:6|confirmed'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    return User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password)
    ]);
  }

  /**
  * Get the token array structure.
  *
  * @param  string $token
  *
  * @return \Illuminate\Http\JsonResponse
  */
  protected function respondWithToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => $this->guard()->factory()->getTTL() * 60
    ]);
  }

  /**
  * Get the guard to be used during authentication.
  *
  * @return \Illuminate\Contracts\Auth\Guard
  */
  protected function guard()
  {
    return Auth::guard();
  }
}