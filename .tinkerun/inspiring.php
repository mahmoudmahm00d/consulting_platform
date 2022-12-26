<?php

use App\Models\Api\SpecialistUser;
use App\Models\Specialize;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;

// Specialize::with('category')->get()->first()->category;

User::with('contacts.type')->find(2);
