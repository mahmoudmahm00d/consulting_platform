<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'gender' => ['required', 'regex:(^((MALE)|(FEMALE))$)'],
            'email' => ['required', 'email', 'unique:users'],
            'phone_number' => ['required', 'regex:(^9639[0-9]{8}$)', 'unique:users'],
            'password' => ['required', 'min:8']
        ]);

        $role = request()->validate(
            ['role' => ['required', 'regex:(^((Specialist)|(User))$)'],]
        );

        $fields['password'] = Hash::make($fields['password']);
        $user = User::create($fields);
        $user->assignRole($role['role']);
        return response()->json('', 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        $userExists = Auth::attempt($credentials);

        if (!$userExists) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::with('roles')->where('email', '=', $credentials['email'])->first();
        $token = auth()->claims(['role' => $user->roles[0]->name])->tokenById($user->id);
        return $this->respondWithToken($token, $user->roles[0]->name);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $role)
    {

        return response()->json([
            'access_token' => $token,
            'role' => $role,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
