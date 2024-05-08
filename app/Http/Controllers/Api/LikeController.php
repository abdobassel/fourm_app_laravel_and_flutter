<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    // like post 1 postid  2 user_id 

    public function likePost($post_id)
    {
        // هناك احتمالات 
        // ان البوست مش موجود اصلا يبقى نتحقق من وجوده 
        // لقيناه موجود هنعل الشغل اما انه فيه لايك معمول يبقى نحذفه 
        // او مفيش لايك يبقى نضع اللايك
        // تفكير منطقي واحتمالات منطقية 
        $post = Post::whereId($post_id)->first();

        if (!$post) {
            return response()->json([
                'message' => 'not found post id'
            ], 500);
        }
        // ببحث الاول هل فيه لايك سابق نحذفه ولو مفيش ننشئ لايك الخطوة الجاية
        $unlike = Like::where('user_id', auth()->user()->id)->where('post_id', $post_id)->delete();
        if ($unlike) {
            return response()->json([
                'message' => 'unliked',
            ], 200);
        }
        $likepost = Like::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post_id
        ]);
        if ($likepost) {
            return response()->json([
                'message' => 'Liked Post',
                'user' => auth()->user(),
            ], 200);
        }
    }
}
