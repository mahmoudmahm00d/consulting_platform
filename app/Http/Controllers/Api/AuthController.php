<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
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

        Wallet::create([
            'amount' => 0,
            'user_id' => $user->id
        ]);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Account created successfully'
        ]);
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
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Wrong email or password'
            ], 401);
        }

        $user = User::with('roles')->where('email', '=', $credentials['email'])->first();
        // Add user role claim
        $token = auth('api')
            ->claims(['role' => $user->roles[0]->name])
            ->tokenById($user->id);

        return $this->respondWithToken($token, $user->roles[0]->name);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        $user = User::with('roles')->find(auth('api')->id);
        // Add user role claim
        $token = auth('api')
            ->claims(['role' => $user->roles[0]->name])
            ->tokenById($user->id);

        return $this->respondWithToken($token, $user->roles[0]->name);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $role = 'User')
    {

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'role' => $role,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 1,
            ]
        ]);
    }
}
