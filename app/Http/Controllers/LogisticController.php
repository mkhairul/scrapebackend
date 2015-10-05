<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

use App\Courier;

class LogisticController extends Controller
{
    public function __construct()
    {
    }
    
    public function createCourier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'conditions' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 500);
        }
        
        $courier = new Courier;
        $courier->name = $request->input('name');
        $courier->conditions = $request->input('conditions');
        $courier->price_per_unit = $request->input('price_per_unit');
        $courier->save();
        
        return response()->json(['status' => 'ok']);
    }
    
    public function updateCourier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'integer',
            'name'      => 'required|max:255',
            'conditions' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 500);
        }
        
        $courier = Courier::find($request->input('id'));
        $courier->name = $request->input('name');
        $courier->conditions = $request->input('conditions');
        $courier->price_per_unit = $request->input('price_per_unit');
        $courier->save();
        
        return response()->json(['status' => 'ok']);
    }
    
    public function getAll()
    {
        $result = Courier::get();
        return response()->json($result);
    }
}