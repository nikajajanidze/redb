<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function settings(Request $request)
    {
      auth()->user()->update([
          'expected_calories' => $request->expected_calories
      ]);

      return response()->json([
        'expected_calories' => $request->expected_calories
      ]);
    }
}