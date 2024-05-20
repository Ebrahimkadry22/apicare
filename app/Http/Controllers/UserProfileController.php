<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function userProfile() {

        $user = auth()->guard('user')->id();
        $post = User::with('posts.reviews')->find($user)->makeHidden(['status','verification_token','verified_at']);
        $review = Reviews::whereIn('post_id',$post->posts()->pluck('id'))->get();
        return response()->json(
            [
                'data'=>$post,
                'review'=>$review
            ], 200);
        return response()->json(auth()->guard('user')->user());
    }


    function update(Request $request) {
        $id = auth()->guard('user')->id();
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'location' => 'required|string|between:2,200',
            'phone' => 'required|string|max:19',
            'photo' => 'nullable|image',
            'email' => 'required|string|email|max:100|unique:users,email'.auth()->guard('user')->id(),
            'password' => 'nullable|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->has('passsword')) {
            $request['password'] = bcrypt($request['password']);
        }

        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];
        $user->location = $request['location'];
        $user->phone = $request['phone'];
        $user->photo = $request['photo'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->update();
        return response()->json(
            [
                'message'=> 'succesfully update profile'
            ], 200);

    }

    function delete () {
        $id = auth()->guard('user')->id();
        Post::where('user_id',$id)->delete();
        return response()->json(
            [
                'message' => 'delete succesfully'
            ], 200);
    }
}
