<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostStatusController extends Controller
{
    public function postsStatus (Request $request) {
        $vaildator = Validator::make($request->all(),[
            'post_id'=> 'required|exists:posts,id',
            'status'=> 'required|in:approved,rejected',
            'rejected_reason'=> 'required_if:status,rejected',
        ]);
        if($vaildator->fails()) {
            return response()->json([
                'error'=>$vaildator->errors()
            ],400);
        }

        $post = Post::find($request->post_id);
        if($post) {
            $post->update([
                'status' => $request->status,
                'rejected_reason'=> $request->rejected_reason
            ]);
            return response()->json([
                'message' => 'successfuly status post'
            ],200);
        }
        return response()->json([
            'message' => 'This post does not exist'
        ],400);


    }
    public function postStatusAll () {
        $posts = DB::table('posts')->update(['status' => 'approved']);;
       if($posts) {
        return response()->json([
            'message' => 'successfuly status post'
        ],200);
       }
       return response()->json([
        'message' => 'This process error'
    ],400);
    }
}
