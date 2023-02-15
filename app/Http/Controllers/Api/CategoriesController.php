<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index($page = 1, $count = 10)
    {
        $categories = Category::all()->skip(($page - 1) * $count)->take($count);
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $categories
        ]);
    }
}
