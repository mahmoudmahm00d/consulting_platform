<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Schedule;
use App\Models\Api\SpecialistUser;
use App\Models\Appointment;
use App\Models\Rating;
use App\Models\Specialize;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SpecialistsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api')->only(['rate']);
        $this->middleware('role:User')->only(['rate']);
    }

    public function index($page = 1, $count = 10)
    {
        $specialists = SpecialistUser::has('specializes')
            ->with(['categories' => function ($query) {
                $query->distinct('id');
            }])
            ->with(['ratings' => function ($query) {
                $query->selectRaw('avg(rating) as average_rating, specialist_id')
                    ->groupBy('specialist_id');
            }])
            ->skip(($page - 1) * $count)
            ->take($count)
            ->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $specialists
        ]);
    }

    public function byCategory($id, $page = 1, $count = 10)
    {
        $specialists = SpecialistUser::has('specializes')
            ->with(['categories' => function ($query) {
                $query->distinct('id');
            }])
            ->with(['ratings' => function ($query) {
                $query->selectRaw('avg(rating) as average_rating, specialist_id')
                    ->groupBy('specialist_id');
            }])
            ->whereIn('id', Specialize::where('category_id', $id)->get('user_id'))
            ->skip(($page - 1) * $count)
            ->take($count)
            ->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $specialists
        ]);
    }

    public function byId($id)
    {
        // Get specialist 
        $user = SpecialistUser::has('specializes')
            ->with(['categories' => function ($query) {
                $query->distinct('id');
            }])
            ->with(['ratings' => function ($query) {
                $query->selectRaw('avg(rating) as average_rating, specialist_id')
                    ->groupBy('specialist_id');
            }])->with([
                'contacts' => function ($query) {
                    $query->with('type');
                }
            ])->with([
                'specializes' => function ($query) {
                    $query->with('category');
                }
            ])->with('schedule')->find($id);

        if (!$user) {
            return  response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Specialist not found'
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function schedule($id)
    {
        $specialist = User::find($id);

        if (!$specialist) {
            return  response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Specialist not found'
            ], 404);
        }

        // Get all available appointments in range 7 days 
        $schedule = Schedule::where('user_id', $id)
            ->whereNotIn('id', Appointment::where('specialist_id', 3)
                ->whereBetween('date', [Carbon::now(), Carbon::now()->addDays(7)])
                ->get('schedule_id'))
            ->get();

        return  response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $schedule
        ]);
    }

    public function rate(Request $request)
    {
        $fields = $request->validate([
            'specialist_id' => 'required',
            'rating' => ['required', 'numeric', 'min:1', 'max:5']
        ]);

        Rating::updateOrCreate([
            'user_id' => auth('api')->user()->id,
            'specialist_id' => $fields['specialist_id'],
            'rating' => $fields['rating']
        ]);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Specialist rated successfully'
        ]);
    }
}
