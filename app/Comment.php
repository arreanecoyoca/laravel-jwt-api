<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['article_id', 'user_id', 'parent_id', 'body'];

    public function replies()
    {
    	return $this->hasMany(Comment::class, 'parent_id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public static function buildCommentTree($comments, $parentId = 0) {
        $tree = array();

        foreach ($comments as $comment) {
            if ($comment->parent_id == $parentId) {
                $replies = static::buildCommentTree($comments, $comment->id);
                if ($replies) {
                    $comment['replies'] = $replies;
                }
                $tree[] = $comment;
            }
        }

        return $tree;
    }
}
