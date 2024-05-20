<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
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
        $lab = Pharmacy::create(array_merge(
            $validator->validated()
        ));
        if($lab) {
            return response()->json([
                'message' => 'Pharmacy successfully registered'
            ],200);
        }

    }

    public function allPharmacy () {
        $pharmacy = Pharmacy::all();
        if($pharmacy == null) {
            return response()->json([
                'data' => 'nothing Pharmacy'
            ],200);
        }
        return response()->json([
            'data' => $pharmacy
        ],200);
    }
}
