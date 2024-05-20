<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\PhotoPost;
use App\Models\Post;
use App\Notifications\AdminPost;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function adminpercent ($price) {
        $dicount =$price * 0.05 ;
        $priceAfterDiscount = $price - $dicount;
        return $priceAfterDiscount ;
    }

    public function store (Request $request) {
        $id = $request['user_id']=Auth()->guard('user')->id();

        $validator = Validator::make($request->all(),[
            'content' => 'required|string',
            'price' => 'required',
            'photos*'=>"nullable|array|image|mimes:png,jpg,jpeg"
        ]);

        if($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors(),
                ],422
            );
        }
        $price = $this->adminpercent($request->price);
        $post = Post::create([
            'user_id' => $id,
            'content'=>$request->content,
            'price'=> $price
        ]);
        $admins = Admin::get();
            Notification::send($admins, new AdminPost(auth()->guard('user')->user(), $post));


        if($request->hasFile('photos')) {
            foreach($request->file('photos') as $photo ) {
                $photoPost = new PhotoPost();
                $photoPost->post_id = $post->id;
                $photoPost->photo = $photo->store('photos');
                $photoPost->save();
            }
        }

        return response()->json([
            'message'=> "Post has been created successfuly , your price after discount {$price}"
        ],201);


    }


    public function show ($id) {
        $post = Post::where(['id'=>$id , 'status' => 'approved'])->select('id','user_id','content','price','created_at')->get();
        return response()->json([
            'data'=> $post
        ],200);
    }


    public function delete ($id) {
        $post = Post::where('id', $id)->delete();;
        if($post) {

            return response()->json([
                'message' => 'delete post successfuly'
            ],200);
        }
        return response()->json([
            'error' => 'This post does not exist'
        ],400);

    }
    public function approved() { {
        $posts = Post::where('status','approved')->select('id','user_id','content','price','created_at')->get();
        return response()->json([
            'data'=> $posts
        ],200);

    }

    }
}
