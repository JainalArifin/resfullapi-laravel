<?php

namespace App\Http\Controllers;

use App\Http\Resources\post\PostCollection;
use App\Http\Resources\post\PostResource;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $data = Post::with(['user'])->paginate(5);
        return new PostCollection($data);
    }

    public function show($id)
    {
        $data = Post::find($id);
        // return response()->json($data);
        if(is_null($data))
        {
            return response()->json([
                "message" => "Resource not found"
            ], 404);
        }
        return new PostResource($data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = validator::make($data, [
            "title" => ["required", "min:5"]
        ]);

        if($validator->fails())
        {
            return response()->json([
                "error" => $validator->errors()
            ]);
        }

        // $response = Post::create($data);
        $response = request()->user()->posts()->create($data);
        return response()->json($response, 201);
    }

    public function update($id, Request $request)
    {
       $post = Post::where('id', $id)->first();
       if($post)
       {
            $post->update($request->all());
            return response()->json([
                "data" => $post
            ], 200);
       }
       return response()->json([
            "message" => "Resource not found"
        ], 404); 
    }

    public function destroy($id)
    {
        $post = Post::where('id', $id)->first();
        if($post)
        {
            $post->delete();
            return response()->json([
                "message" => "Delete success"
            ], 200);
        }
        return response()->json([
            "message" => "Resource not found"
        ], 404);
    }
}
