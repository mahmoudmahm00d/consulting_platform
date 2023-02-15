<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ContactInfoType;

class ContactInfoTypeController extends Controller
{
    public function index()
    {
        $contactInfoTypes = ContactInfoType::all();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $contactInfoTypes
        ]);
    }
}
