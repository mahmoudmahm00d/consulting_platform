<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use ApplicationRoles;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function amount()
    {
        $wallet = Wallet::where('user_id', auth('api')->user()->id)->first();

        return response([
            'status' => 'success',
            'code' => '200',
            'data' => $wallet
        ]);
    }

    public function transactions($page = 1, $count = 10)
    {
        $user = User::with('wallet')->find(auth('api')->user()->id);
        $transactions = [];
        if ($user->hasRole(ApplicationRoles::$specialist)) {
            $transactions = Transaction::where('to_wallet', $user->wallet->id)
                ->skip(($page - 1) * $count)
                ->take($count)->get();
        } else {
            $transactions = Transaction::where('from_wallet', $user->wallet->id)
                ->orWhere('to_wallet', $user->wallet->id)
                ->skip(($page - 1) * $count)
                ->take($count)->get();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $transactions
        ]);
    }
}
