<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Article $article)
    {
        $response = array();
        $comments = $article->comments->where('is_active', true);
        $tree = Comment::buildCommentTree($comments);
        return $tree;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'integer',
            'body' => 'required|min:3|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        Comment::create([
            'article_id' => $article->id,
            'user_id' => user()->id,
            'parent_id' => request('parent_id'),
            'body' => request('body')
        ]);

        return response()->json('created_successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article, Comment $comment)
    {
        if(!$comment->is_active){
            return response()->json(['unable to update. comment has already been deleted'], 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|min:3|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $comment->body = request('body');
        $comment->save();

        return response()->json('updated_successfully', 201);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article, Comment $comment)
    {
        $comment->is_active = false;
        $comment->save();

        return response()->json('deleted_successfully', 201);
    }
}
