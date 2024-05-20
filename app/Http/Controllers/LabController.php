<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class LabController extends Controller
{

    public function store (Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'phone' => 'required|string',
            'location' => 'required|string',

        ]);
        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ],422);
        }
        $lab = Lab::create(array_merge(
            $validator->validated()
        ));
        if($lab) {
            return response()->json([
                'message' => 'Lab successfully registered'
            ],200);
        }

    }

    public function allLabs () {
        $labs = Lab::all();
        if($labs == null) {
            return response()->json([
                'data' => 'nothing lads'
            ],200);
        }
        return response()->json([
            'data' => $labs
        ],200);
    }
}
