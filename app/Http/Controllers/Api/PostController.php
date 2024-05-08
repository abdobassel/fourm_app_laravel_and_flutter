<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Mailables\Content;

class PostController extends Controller
{

    public function index()
    {
        $posts =  Post::where('user_id', auth()->user()->id)->get();
        $userPosted = auth()->user();

        // Post::with('user')->latest->get;

        if (empty($posts)) {
            return response()->json([

                'message' => 'There are no posts yet',

            ], 404);
        }
        return response()->json([
            'user' => $userPosted,
            'posts' => $posts,

        ], 200);
    }

    public function store(PostRequest $request)
    {
        $request->validated();
        $user_id = auth()->user()->id;
        // طريقة صحيحة مني 
        // $post =    Post::create([
        //     'content' => $request->content,
        //     'user_id' => $user_id
        // ]);
        // طريقة الشرح وايضا صحيحة لمنها اكثر اختصارا
        $post = auth()->user()->posts()->create([
            'content' => $request->content
        ]);
        return response([
            'message' => 'success', 'post' => $post
        ], 201);
    }


    // comments 
    public function comment(Request $request, $post_id)
    {
        $request->validate([
            'body' => 'required|min:3',
        ]);
        $post = Post::where('id', $post_id)->first();
        if (!$post) {
            return response()->json(['message' => 'not found'], 404);
        }

        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post_id,
            'body' => $request->body,
        ]);
        if ($comment) {
            return response()->json(['message' => 'success'], 201);
        }
    }
    // getComments 
    public function getComments($post_id)
    {

        $comments = Comment::with('post')->with('user')->wherePostId($post_id)->latest()->get();
        if ($comments) {
            return response()->json([
                'comments' => $comments,
            ]);
        }
        return response()->json([
            'message' => 'no comments here',
        ]);
    }
}
