<?php

namespace App\Observers;

use App\Events\CommentWritten;
use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        event(new CommentWritten($comment->user));
    }

}
