<?php

namespace App\Services;

use App\Enums\ErrorCode;
use App\Exceptions\AuthException;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  // REGISTER
  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:105',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:8',
    ]);

    $role = Role::where('name', 'user')->first();

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role_id' => $role?->id
    ]);

    return response()->json([
      'status' => true,
      'message' => 'Register success',
      'data' => $user
    ]);
  }


  // LOGIN
  public function login(array $data)
  {
    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
      throw new AuthException(ErrorCode::AUTH_INVALID_CREDENTIALS);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return [
      'access_token' => $token,
      'user' => $user
    ];
  }


  // LOGOUT
  public function logout(Request $request)
  {
    if ($request->user()?->currentAccessToken()) {
      $request->user()->currentAccessToken()->delete();
    }

    return response()->json([
      'status' => true,
      'message' => 'Logout success'
    ]);
  }

  // GET USER
  public function me(Request $request)
  {
    return response()->json([
      'status' => true,
      'data' => [
        'id' => $request->user->id,
        'name' => $request->user->name,
        'email' => $request->user->email,
      ]
    ]);
  }

  public function loginWithGoogle($googleUser)
  {
    // xử lý google login
  }

  public function sendOtp($email)
  {
    // gửi OTP
  }

  public function verifyOtp($email, $otp)
  {
    // verify OTP
  }
}
