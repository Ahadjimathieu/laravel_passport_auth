<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'DESC')->with('user:id,name,image')->withCount('comments','likes')
            ->with('likes',function($like){
                return $like->where('user_id',auth()->user()->id)
                ->select('id','user_id','post_id')->get();
            })
            ->get()
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image,'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return response([
            'message' => 'Post created',
            'post' => $post
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response([
            'posts' =>Post::where('id',$id)->withCount('comments','likes')->get()
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'Post not found'
            ],403);
        }

        if ($post->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied'
            ],403);
        }
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $attrs['body']

        ]);

        return response([
            'message' => 'Post updated',
            'post' => $post
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'Post not found'
            ],403);
        }

        if ($post->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied'
            ],403);
        }
    $post->comments()->delete();
    $post->likes()->delete();
    $post->delete();

    return response([
        'message' => 'Post deleted',
    ],200);
    }
}
