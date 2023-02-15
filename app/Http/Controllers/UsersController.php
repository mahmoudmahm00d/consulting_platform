<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TransactionTypes;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', ['users' => $users]);
    }

    public function transaction($id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return view('users.transaction', [
            'id' => $id
        ]);
    }

    public function deposit(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $wallet = Wallet::where('user_id', $id)->first();
        if (!$wallet) {
            throw new NotFoundException();
        }

        $fields = $request->validate([
            'amount' => 'required'
        ]);

        Transaction::create([
            'to_wallet' => $wallet->id,
            'amount' => $fields['amount'],
            'type' => TransactionTypes::$deposit
        ]);

        return redirect('/users');
    }
}
