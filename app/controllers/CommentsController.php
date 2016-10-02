<?php

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;

class CommentsController extends \BaseController
{

    use CommandBus;

    /**
     * Store a newly created comment in storage.
     *
     * @return Response
     */
    public function store()
    {
        $type = Input::get('type');
        $model = $type::findOrFail(Input::get('id'));
        $body = Input::get('body');

        $validator = Validator::make(
            [
                'body' => $body,
            ],
            [
                'body' => 'required|max:1000',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        }

        $comment = $this->execute(new AddNewCommentCommand(
            $model, $this->user->id, $body
        ));

        Flash::success("Your comment has been saved.");

        return Redirect::back();
    }

}
