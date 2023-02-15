<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Specialize;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use ApplicationRoles;
use AppointmentStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use TransactionTypes;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:User')->only('book');
    }

    public function show($id)
    {
        $userId = auth('api')->user()->id;
        $appointment = Appointment::with('specialist')
            ->where('user_id', $userId)
            ->where('id', $id)->first();

        if (!$appointment) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Appointment not found',
            ], 404);
        }

        return response()->json([
            'status' => 'error',
            'code' => 200,
            'data' => $appointment
        ]);
    }

    public function mine($page = 1, $count = 10)
    {
        $user = User::find(auth('api')->user()->id);
        $appointments = [];

        if ($user->hasRole(ApplicationRoles::$specialist)) {
            $appointments = Appointment::where('specialist_id', $user->id)
                ->skip(($page - 1) * $count)
                ->take($count)
                ->get();
        } else {
            $appointments = Appointment::where('user_id', $user->id)
                ->skip(($page - 1) * $count)
                ->take($count)
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $appointments
        ]);
    }

    public function book(Request $request)
    {
        $userId = auth('api')->user()->id;

        $fields = $request->validate([
            'date' => ['date', 'required'],
            'specialist_id' => ['required', 'numeric'],
            'schedule_id' => ['required', 'numeric'],
            'specialize_id' => ['required', 'numeric'],
            'password' => ['required', 'string']
        ]);

        if ($fields['date'] < Carbon::now()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid date',
            ], 400);
        }

        $specialist = User::has('specializes')->with('wallet')->find($fields['specialist_id']);
        if (!$specialist) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Specialist not found',
            ], 404);
        }

        $specialize = Specialize::where('user_id', $specialist->id)->where('id', $fields['specialize_id'])->first();
        if (!$specialize) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Specialize not found',
            ], 404);
        }

        $schedule = Schedule::where('user_id', $specialist->id)->where('id', $fields['schedule_id'])->first();
        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Schedule not found',
            ], 404);
        }

        $date = Carbon::createFromDate($fields['date']);
        if ($date->weekday() != $schedule->day) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Date does not match schedule day',
            ], 400);
        }

        $appointmentAlreadyBooked = Appointment::whereDate('date',$fields['date'])->where('schedule_id','schedule_id')->count();
        // $appointmentAlreadyBooked = Appointment::whereDate('date', $fields['date'])
        //     ->whereTime('start_at', $schedule->from)
        //     ->whereTime('finish_at', $schedule->to)->count();

        if ($appointmentAlreadyBooked) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Appointment already booked',
            ], 400);
        }


        $wallet = Wallet::where('user_id', $userId)->first();
        if ($wallet->amount < $specialize->price) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Wallet does not have enough amount',
            ], 400);
        }

        $isValidPassword = Hash::check($fields['password'], User::find($userId)->password);
        if (!$isValidPassword) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid password',
            ], 400);
        }

        $appointment = Appointment::create([
            'user_id' => $userId,
            'specialist_id' => $specialist->id,
            'specialize_id' => $specialize->id,
            'schedule_id' => $schedule->id,
            'date' => $fields['date'],
            'start_at' => $schedule->from,
            'finish_at' => $schedule->to,
            'status' => AppointmentStatus::$upcoming
        ]);

        Transaction::create([
            'appointment_id' => $appointment->id,
            'from_wallet' => $wallet->id,
            'to_wallet' => $specialist->wallet->id,
            'amount' => $specialize->price,
            'type' => TransactionTypes::$transfer
        ]);

        $wallet->amount -= $specialize->price;
        $wallet->update();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Appointment booked successfully',
        ]);
    }

    public function checkAppointment(Request $request)
    {
        $fields = $request->validate([
            'date' => ['date', 'required'],
            'specialist_id' => ['required', 'numeric'],
            'schedule_id' => ['required', 'numeric'],
        ]);

        $specialist = User::has('specializes')->with('wallet')->find($fields['specialist_id']);
        if (!$specialist) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Specialist not found',
            ], 404);
        }

        $schedule = Schedule::where('user_id', $specialist->id)->where('id', $fields['schedule_id']);
        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Schedule not found',
            ], 404);
        }

        $appointmentAlreadyBooked = Appointment::whereDate('date', $fields['date'])
            ->whereTime('start_at', $schedule->from)
            ->whereTime('finish_at', $schedule->to)->count();

        if ($appointmentAlreadyBooked) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Appointment already booked',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Appointment available',
        ]);
    }
}
