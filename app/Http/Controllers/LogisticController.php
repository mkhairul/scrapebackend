<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

use App\Courier;
use App\Location;

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
        $courier->save();
        
        $conditions = json_decode($courier->conditions, TRUE);
        if(is_array($conditions)){
            foreach($conditions as $cond)
            {
                $search_location = Location::where('name', 'LIKE', $cond['location'])->first();
                if(!$search_location){
                    $location = new Location;
                    $location->name = $cond['location'];
                    $location->save();
                }
            }
        }
        
        return response()->json(['status' => 'ok', 'id' => $courier->id]);
    }
    
    public function countries()
    {
        $result = Location::get();
        return response()->json($result);
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
        $courier->save();
        
        $conditions = json_decode($courier->conditions, TRUE);
        if(is_array($conditions)){
            foreach($conditions as $cond)
            {
                $search_location = Location::where('name', 'LIKE', $cond['location'])->first();
                if(!$search_location){
                    $location = new Location;
                    $location->name = $cond['location'];
                    $location->save();
                }
            }
        }
        
        return response()->json(['status' => 'ok']);
    }
    
    public function remove(Request $request)
    {
        $result = Courier::find($request->input('id'))->delete();
        return response()->json(['status' => 'ok']);
    }
    
    public function getAll()
    {
        $result = Courier::get();
        return response()->json($result);
    }
}