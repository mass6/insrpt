<?php namespace Insight\Comments;

use Laracasts\Commander\CommandHandler;

class AddNewCommentCommandHandler implements CommandHandler
{

    public function handle($command)
    {

        // Create the Comment
        try {
            $commentable = $command->commentable;
            $type = get_class($commentable);

            $comment = Comment::create([
                'commentable_id'   => $commentable->id,
                'commentable_type' => $type,
                'user_id'          => $command->user_id,
                'body'             => $command->body
            ]);

        } catch (Exception $e) {
            return 'Could not create comment.';
        }

        return $comment;
    }

}