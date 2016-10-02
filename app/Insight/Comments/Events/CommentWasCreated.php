<?php 

namespace Insight\Comments\Events; 

use Insight\Comments\Comment;

class CommentWasCreated
{

    /**
     * @var Comment
     */
    public $comment;

    /**
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
 