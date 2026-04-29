<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseApiController
{
  protected $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

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
  public function login(LoginRequest $request)
  {
    $data = $this->authService->login($request->validated());

    return $this->success($data, __('messages.auth.login_success'));
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
    $user = $request->user();

    if (!$user) {
      return response()->json([
        'status' => false,
        'message' => 'Unauthenticated'
      ], 401);
    }

    return response()->json([
      'status' => true,
      'data' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
      ]
    ]);
  }
}
