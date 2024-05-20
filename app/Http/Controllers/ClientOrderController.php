<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientOrderController extends Controller
{

    public function addOrder(Request $request)
    {
        $clientId = Auth::guard('client')->id();
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }
        $order = ClientOrder::where(['client_id' => $clientId, 'post_id' => $request->post_id])->exists();

        if ($order) {
            return response()->json([
                'message' => 'The service was requested by an action'
            ], 200);
        }

        $clientOrder = ClientOrder::create([
            'post_id' => $request->post_id,
            'client_id' => $clientId
        ]);
        if ($clientOrder) {
            return response()->json([
                'message' => 'The request has been submitted successfully'
            ], 200);
        }
    }


    public function showOrderClient()
    {
        $clientId = Auth::guard('client')->id();
        $orders = ClientOrder::with('post')->where('client_id', $clientId)
        ->select('id','post_id')->get();
        return response()->json([
            'data' => $orders
        ], 200);
    }

    public function deleteOrder ($id) {
        $clientId = Auth::guard('client')->id();
        $order = ClientOrder::where(['id'=>$id , 'client_id'=> $clientId])->delete();
        if($order) {
            return response()->json([
                'message' => 'Delete order successfully'
            ], 200);
        }
        return response()->json([
            'error' => 'This order does not exist'
        ], 400);
    }

    public function deleteAllOrder () {
        $clientId = Auth::guard('client')->id();
        $order = ClientOrder::where('client_id', $clientId)->delete();
        if($order) {
            return response()->json([
                'message' => 'Delete All order successfully'
            ], 200);
        }
        elseif ($order == null) {
            return response()->json([
                'message' => 'Nothing orders'
            ], 200);
        }

        return response()->json([
            'error' => 'This order does not exist'
        ], 400);
    }
}
