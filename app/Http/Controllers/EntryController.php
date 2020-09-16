<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use Auth;
use Validator;
use Illuminate\Support\Facades\Http;

class EntryController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $data = Entry::byRole(auth()->user())->get();

    return response()->json([
      'status' => true,
      'data' => $data
    ]);
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
      'user_id' => $user->role == 'admin' ? 'required|int' : 'nullable',
      'meal' => 'required|string',
      'calories' => 'numeric|nullable'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $calories = $request->calories ? $request->calories : $this->calories($request->meal);
    $extra_field_condition = ($user->expected_calories > $request->calories) ? true : false;

    $input = [
      'user_id' => $user->role == 'admin' ? $request->user_id : $user->id,
      'meal' => $request->meal,
      'calories' => $calories,
      'extra_field' => $extra_field_condition
    ];

    $entry = Entry::create($input);

    return response()->json(['entry' => $entry], 201);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $entry = Entry::find($id);

    if(!$entry) {
      return response()->json(['error' => 'Entry Not Found'], 404); 
    }

    if(auth()->user()->role == 'manager') {
      if($entry->user->id !== auth()->id() && $entry->user->role !== 'user') {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    if(auth()->user()->role == 'user') {
      if($entry->user->id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    return response()->json(['entry' => $entry], 200);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    $entry = Entry::find($id);

    if(!$entry) {
      return response()->json(['error' => 'Entry Not Found'], 404); 
    }

    if(auth()->user()->role == 'manager') {
      if($entry->user->id !== auth()->id() && $entry->user->role !== 'user') {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    if(auth()->user()->role == 'user') {
      if($entry->user->id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    $calories = $request->calories ? $request->calories : $this->calories($request->meal);
    $extra_field_condition = (auth()->user()->expected_calories > $entry->calories) ? true : false;

    $entry->update([
      'meal' => $request->meal,
      'calories' => $calories,
      'extra_field' => $extra_field_condition
    ]);

    return response()->json(['entry' => $entry], 200);
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $entry = Entry::find($id);

    if(!$entry) {
      return response()->json(['error' => 'Entry Not Found'], 404); 
    }

    if(auth()->user()->role == 'manager') {
      if($entry->user->id !== auth()->id() && $entry->user->role !== 'user') {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    if(auth()->user()->role == 'user') {
      if($entry->user->id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
    }

    $entry->destroy($id);
  }

  protected function calories($meal)
  {
    $api = Http::withHeaders([
        'Content-Type' => 'application/json',
        'x-app-id' => '0528336c',
        'x-app-key' => '32ba7f34137c35a33d9ff2063d4881a8'
    ])->post('https://trackapi.nutritionix.com/v2/natural/nutrients', [
        'query' => $meal,
        'timezone' => 'Asia/Tbilisi'
    ]);

    $calories = 0;
    foreach($api['foods'] as $food) {
      $calories += $food['nf_calories'];
    }

    return $calories;
  }
}
