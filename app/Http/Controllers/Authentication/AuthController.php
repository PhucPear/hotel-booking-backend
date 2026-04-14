<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
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
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:8'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !$user->password) {
      return response()->json([
        'status' => false,
        'message' => 'Invalid credentials'
      ], 401);
    }

    if (!Hash::check($request->password, $user->password)) {
      return response()->json([
        'status' => false,
        'message' => 'Invalid credentials'
      ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
      'status' => true,
      'data' => [
        'access_token' => $token,
        'user' => $user
      ]
    ]);
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
}
