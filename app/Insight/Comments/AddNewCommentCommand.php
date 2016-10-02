<?php namespace Insight\Comments;

/**
 * Class AddNewCommentCommand
 * @package Insight\Comments
 */
class AddNewCommentCommand
{

    /**
     * @var
     */
    public $commentable;
    /**
     * @var
     */
    public $user_id;
    /**
     * @var
     */
    public $body;

    /**
     * @param $commentable
     * @param $user_id
     * @param $body
     */
    public function __construct($commentable, $user_id, $body)
    {
        $this->commentable = $commentable;
        $this->user_id = $user_id;
        $this->body = $body;
    }

}