<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\SpecialistUser;
use App\Models\Api\ContactInfo as ApiContactInfo;
use App\Models\Api\Specialize as ApiSpecialize;
use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\ContactInfoType;
use App\Models\Specialize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AccountController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->middleware('auth.jwt');
    }

    public function addSpecializes(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $specializes = $request->specializes;
        try {
            foreach ($specializes as $specialize) {
                Specialize::create([
                    'name' => $specialize['name'],
                    'description' => $specialize['description'],
                    'user_id' => $user->id,
                    'category_id' => $specialize['category'],
                ]);
            }
        } catch (\Throwable $ex) {
            return response()->json(['message' => 'An error was thrown when creating specialize'], 400);
        }

        return response()->json([
            'message' => 'success',
        ]);
    }

    public function addContacts(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $contacts = $request->contacts;

        foreach ($contacts as $contact) {
            if (!ContactInfoType::find($contact['type'])) {
                return response()->json([
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
            return response()->json(['message' => 'An error was thrown when creating contacts'], 400);
        }

        return response()->json([
            'message' => 'success',
        ]);

        return response()->json();
    }

    public function deleteSpecialize($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $specialize = Specialize::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$specialize) {
            return response()->json([], 404);
        }

        $specialize->delete();
        return response()->json([], 204);
    }

    public function deleteContact($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $contact = ContactInfo::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$contact) {
            return response()->json([], 404);
        }

        $contact->delete();
        return response()->json([], 204);
    }

    public function uploadImage(Request $request)
    {
        if (!$request->has('profile_image')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Image is required'
            ], 400);
        }

        $fields = $request->validate(['profile_image' => ['file', 'max:512']]);
        $user = User::find(auth('api')->user()->id);
        $user->profile_image = $request->file('profile_image')->store('profile', 'public');
        $user->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Image uploaded successfully'
        ]);
    }

    // Schedule
    public function addSchedule(Request $request)
    {
        $schedules = $request->schedules;
        if (!$schedules) {
            return response()->json(['message' => 'Schedule is required'], 400);
        }

        foreach ($schedules as $item) {
            if ($item['day'] < 0 || 7 < $item['day']) {
                return response()->json(['message' => 'Invalid day'], 400);
            }
        }

        return response()->json([], 204);
    }

    // Profile
    public function me()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = SpecialistUser::find($user->id);

        $specializes = ApiSpecialize::with('category')->where('user_id', '=', $user->id)->get();
        $contacts = ApiContactInfo::with('type')->where('user_id', '=', $user->id)->get();
        $categories = User::join('specializes', 'specializes.user_id', '=', 'users.id')
            ->join('categories', 'categories.id', '=', 'specializes.category_id')
            ->where('user_id', '=', $user->id)
            ->distinct()
            ->get(['categories.name']);

        $user['categories'] = $categories;
        $user['specializes'] = $specializes;
        $user['contacts'] = $contacts;
        return response()->json(['message' => 'success', 'data' => $user]);
    }

    // Update account info
    public function update(Request $request)
    {
        if (!auth('api')->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $fields = $request->validate([
            'name' => ['required', 'max:256', 'string'],
            'about' => ['nullable'],
        ]);

        $user = User::find(auth('api')->user()->id);
        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully'
        ]);
    }

    public function changeEmail(Request $request)
    {
        if (!auth('api')->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $fields = $request->validate([
            'email' => ['required', 'max:256', 'email'],
        ]);

        $user = User::find(auth('api')->user()->id);

        $validPassword = Hash::check($request->password, $user->password);

        if (!$validPassword) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong password'
            ]);
        }

        $emailFound = User::where('email', '=', $fields['email'])
            ->where('id', '!=',)->first();

        if ($emailFound) {
            return response()->json([
                'status' => 'error',
                'message' => 'This email already used, Use another email'
            ]);
        }

        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'message' => 'Email updated successfully'
        ]);
    }

    public function changePhone(Request $request)
    {
        if (!auth('api')->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $fields = $request->validate([
            'phone' => ['required', 'max:256', 'email'],
        ]);

        $user = User::find(auth('api')->user()->id);

        $validPassword = Hash::check($request->password, $user->password);

        if (!$validPassword) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong password'
            ]);
        }

        $emailFound = User::where('phone_number', '=', $fields['phone'])
            ->where('id', '!=',)->first();

        if ($emailFound) {
            return response()->json([
                'status' => 'error',
                'message' => 'This phone number already used, Use another email'
            ]);
        }

        $user->update($fields);

        return response()->json([
            'status' => 'success',
            'message' => 'Phone number updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        if (!auth('api')->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $fields = $request->validate([
            'password' => ['required'],
            'newPassword' => ['required'],
        ]);

        $user = User::find(auth('api')->user()->id);

        $validPassword = Hash::check($request->password, $user->password);

        if (!$validPassword) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong password'
            ]);
        }

        $user->update(['password' => Hash::make($fields['newPassword'])]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully'
        ]);
    }
}
