<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
       $search= $request->query('search');

           $centers = Center::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->get();

        return response()->json([
            'data' => $centers,
        ]);
    }
}
