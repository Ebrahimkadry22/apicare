<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class UserReviewController extends Controller
{
    public function store (Request $request) {
        $clientId =auth()->guard('client')->id();
        $validator = Validator::make($request->all(),[
            'post_id' => 'required|exists:posts,id',
            'comment' => 'nullable|string',
            'rate' => 'required|integer|max:5',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ],422);
        }
        $review = Reviews::create([
            'client_id'=> $clientId,
            'post_id'=> $request->post_id,
            'comment'=>$request->comment,
            'rate'=>$request->rate
        ]);
        if($review) {
            return response()->json(
                [
                    'message' => 'comment add successfuly'
                ], 200);
        }
    }


    public function postRate ($id) {
        $reviews =Reviews::with('post','client' )->select('id','post_id','client_id')->where('post_id',$id)->get();
        if($reviews) {
            return response()->json(
                [

                    'data' => $reviews
                ], 200);
        }
        return response()->json(
            [
                'data' => 'not rate post'
            ], 200);
    }
}
