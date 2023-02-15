<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\SpecialistUser;
use App\Models\ContactInfo;
use App\Models\ContactInfoType;
use App\Models\Schedule;
use App\Models\Specialize;
use App\Models\User;
use ApplicationRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('testing');
        $this->middleware('role:Specialist')->except([
            'me',
            'update',
            'changeEmail',
            'changePassword',
            'changePhone',
        ]);
    }

    public function addSpecializes(Request $request)
    {
        $user = auth('api')->user();
        $specializes = $request->specializes;

        try {
            foreach ($specializes as $specialize) {
                $validator = Validator::make($specialize, [
                    'name' => 'required',
                    'category' => ['required', 'numeric'],
                    'price' => ['required', 'numeric'],
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Bad request',
                        'errors' => $validator->errors()
                    ], 422);
                }

                Specialize::create([
                    'name' => $specialize['name'],
                    'description' => $specialize['description'],
                    'user_id' => $user->id,
                    'price' => $specialize['price'],
                    'category_id' => $specialize['category'],
                ]);
            }
        } catch (\Throwable $ex) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'An error was thrown when creating specialize'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Specializes created successfully',
        ]);
    }

    public function addContacts(Request $request)
    {
        $user = auth('api')->user();
        $contacts = $request->contacts;

        foreach ($contacts as $contact) {
            $validator = Validator::make($contact, [
                'contact_info' => 'required',
                'type' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Bad request',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!ContactInfoType::find($contact['type'])) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'The type ' . $contact['type'] . 'not found'
                ], 400);
            }
        }

        try {
            foreach ($contacts as $contact) {
                ContactInfo::create([
                    'contact_info' => $contact['contact_info'],
                    'user_id' => $user->id,
                    'type_id' => $contact['type'],
                ]);
            }
        } catch (\Throwable $ex) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'An error was thrown when creating contacts'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Contacts Added successfully',
        ]);
    }

    public function deleteSpecialize($id)
    {
        $user = auth('api')->user();
        $specialize = Specialize::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$specialize) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Specialize not found'
            ], 404);
        }

        $specialize->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "Specialize #$id deleted successfully"
        ]);
    }

    public function deleteContact($id)
    {
        $user = auth('api')->user();
        $contact = ContactInfo::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Contact not found'
            ], 404);
        }

        $contact->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "Contact $id deleted successfully"
        ]);
    }

    public function uploadImage(Request $request)
    {
        if (!$request->has('profile_image')) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Image is required'
            ], 400);
        }

        $fields = $request->validate(['profile_image' => ['file', 'max:512']]);
        $user = User::find(auth('api')->user()->id);

        $user->profile_image = $request->file('profile_image')->store('profile', 'public');
        $user->update();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Image uploaded successfully'
        ]);
    }

    public function schedule()
    {
        $user = auth('api')->user();
        $schedule = SpecialistUser::find($user->id)->schedule;

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $schedule ? $schedule : []
        ]);
    }

    // Schedule
    public function addSchedule(Request $request)
    {
        $user = auth('api')->user();
        $schedule = $request->schedule;

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Schedule is required'
            ], 400);
        }

        foreach ($schedule as $item) {
            // Insure weekday is valid day
            if ($item['day'] < 0 || 7 < $item['day']) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid day'
                ], 400);
            }
            // Insure from < to 
            if ($item['from'] >= $item['to']) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'To time must be bigger than from time'
                ], 400);
            }

            $condition1 = Schedule::whereTime('from', '<=', $item['from'])
                ->whereTime('to', '>=', $item['to'])
                ->where('user_id', $user->id)
                ->where('day', $item['day'])
                ->count();

            $condition2 = Schedule::whereTime('from', '<=', $item['to'])
                ->whereTime('to', '>=', $item['to'])
                ->where('user_id', $user->id)
                ->where('day', $item['day'])
                ->count();

            $condition3 = Schedule::whereTime('from', '>=', $item['from'])
                ->whereTime('to', '<=', $item['to'])
                ->where('user_id', $user->id)
                ->where('day', $item['day'])
                ->count();

            if ($condition1 && $condition2) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Schedule in other schedule'
                ], 400);
            }

            if (($condition1 && !$condition2) // One in 
                || (!$condition1 && $condition2) // and other out
            ) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Schedule in between other schedule'
                ], 400);
            }

            if ($condition3) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Schedule contains other schedule'
                ], 400);
            }

            // Create schedule
            try {
                Schedule::updateOrCreate([
                    'day' => $item['day'],
                    'from' => $item['from'],
                    'to' => $item['to'],
                    'user_id' => $user->id,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'An error occurs while creating schedule',
                    'data' => $item,
                ], 400);
            }
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Schedule created successfully'
        ]);
    }

    public function deleteSchedule($id)
    {
        $user = auth('api')->user();
        $schedule = Schedule::where('user_id', '=', $user->id)->where('id', '=', $id)->first();

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Schedule not found'
            ], 404);
        }

        $schedule->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "Schedule $id deleted successfully"
        ]);
    }

    // Profile
    public function me()
    {
        $user = User::find(auth('api')->user()->id);

        if ($user->hasRole(ApplicationRoles::$specialist)) {

            $user = SpecialistUser::with(['categories' => function ($query) {
                $query->distinct('id');
            }])->with(['ratings' => function ($query) {
                $query->selectRaw('avg(rating) as average_rating, specialist_id')
                    ->groupBy('specialist_id');
            }])->with(['contacts' => function ($query) {
                $query->with('type');
            }])->with(['specializes' => function ($query) {
                $query->with('category');
            }])->with('schedule')
                ->find(auth('api')->user()->id);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $user
        ]);
    }

    // Update account info
    public function update(Request $request)
    {
        $fields = $request->validate([
            'name' => ['nullable', 'max:256', 'string'],
            'about' => ['nullable'],
        ]);

        $user = User::find(auth('api')->user()->id);
        if ($fields['name']) {
            $user->name = $fields['name'];
        }

        if ($fields['about']) {
            $user->about = $fields['about'];
        }
        
        $user->update();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Account updated successfully'
        ]);
    }

    public function changeEmail(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'max:256', 'email'],
        ]);

        $user = User::find(auth('api')->user()->id);

        $validPassword = Hash::check($request->password, $user->password);

        if (!$validPassword) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Wrong password'
            ], 400);
        }

        $emailFound = User::where('email', '=', $fields['email'])
            ->where('id', '!=',)->first();

        if ($emailFound) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'This email already used, Use another email'
            ], 400);
        }

        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Email updated successfully'
        ]);
    }

    public function changePhone(Request $request)
    {
        $fields = $request->validate([
            'phone' => ['required', 'max:256', 'email'],
        ]);

        $user = User::find(auth('api')->user()->id);

        $validPassword = Hash::check($request->password, $user->password);

        if (!$validPassword) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Wrong password'
            ], 400);
        }

        $emailFound = User::where('phone_number', '=', $fields['phone'])
            ->where('id', '!=',)->first();

        if ($emailFound) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'This phone number already used, Use another email'
            ], 400);
        }

        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Phone number updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        $fields = $request->validate([
            'password' => ['required'],
            'newPassword' => ['required'],
        ]);

        $user = User::find(auth('api')->user()->id);

        $validPassword = Hash::check($request->password, $user->password);

        if (!$validPassword) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Wrong password'
            ], 400);
        }

        $user->update(['password' => Hash::make($fields['newPassword'])]);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Password updated successfully'
        ]);
    }
}
