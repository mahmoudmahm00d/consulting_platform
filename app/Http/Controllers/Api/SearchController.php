<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\SpecialistUser;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('q');
        $data = [
            'specialists' => [],
            'categories' => [],
        ];

        if (strlen($query) < 2) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ]);
        }
        
        $data['categories'] = Category::whereRaw('Lower(name) like' . "'%" . strtolower($query) . "%'")->get();
        $data['specialists'] = SpecialistUser::has('specializes')->whereRaw('Lower(name) like' . "'%" . strtolower($query) . "%'")->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $data
        ]);
    }
}
