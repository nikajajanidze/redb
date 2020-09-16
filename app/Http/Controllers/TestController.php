<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
  public function api()
  {
    $api = Http::withHeaders([
        'Content-Type' => 'application/json',
        'x-app-id' => '0528336c',
        'x-app-key' => '32ba7f34137c35a33d9ff2063d4881a8'
    ])->post('https://trackapi.nutritionix.com/v2/natural/nutrients', [
        'query' => '5 egg and 6 bread',
        'timezone' => 'Asia/Tbilisi'
    ]);

    return $api;//$api['foods'];
    
  }
}