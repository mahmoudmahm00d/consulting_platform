<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.refresh', ['only' => ['refresh']]);
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
        $token = auth('api')->claims(['role' => $user->roles[0]->name])->tokenById($user->id);
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

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh()
    // {
        // return $this->respondWithToken(auth('api'));
        // try {
        //     $token = $this->auth->parseToken()->refresh();
        // } catch (JWTException $e) {
        //     return response()->json($e);
        //     throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        // }

        // try {
        //     $payload = auth('api')->payload();
        //     $token = auth('api')->refresh();
        //     $user = User::with('roles')->find($payload['sub']);
        //     return $this->respondWithToken($token, $user->roles[0]->name);
        // } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $th) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Token expired'
        //     ]);
        // }
    // }

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
            'access_token' => $token,
            'role' => $role,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL(),
        ]);
    }
}
