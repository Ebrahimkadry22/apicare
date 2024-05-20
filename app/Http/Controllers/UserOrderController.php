<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserOrderController extends Controller
{
    public function userOrder ( ) {
        $orders = ClientOrder::with('post','client')->where('status','pending')
        ->whereHas('post',function ($quary){
            $quary->where('user_id',auth()->guard('user')->id());
        })->select('post_id','client_id','status')->get();

        return response()->json(
            [
                'oredr' => $orders
            ], 200);
    }

    public function statusOrder ( Request  $request) {
        $clientId = Auth::guard('user')->id();
        $vaildator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'status'=> 'required|in:approved,rejected',
        ]);

        if($vaildator -> fails()) {
            return response()->json([
                'error' => $vaildator->errors()
            ],400);
        }
        $order = ClientOrder::where(['id'=> $request->id , 'client_id' =>$clientId]);
        if($order) {
            $order->update([
                'status' => $request->status,
            ]);
            return response()->json([
                'message' => 'successfuly status order'
            ],200);
        }

        return response()->json([
            'message' => 'This order does not exist'
        ],400);

    }
}
