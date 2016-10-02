<!-- History &Comments -->
@if(count($model->comments))
    <div id="comments" class="row">

        <div class="col-md-8">

            <div class="well well-lg">
                <h3>History & Comments</h3>

                    <div class="clear"></div>
                    <hr/>
                    <div class="comment-history">
                        @foreach ($model->comments->reverse() as $comment)
                            <div class="row">
                                <div class="col-sm-2">
                                    <a href="{{ route('profiles.show', $comment->user->id) }}" class="profile-picture">
                                        <img src="{{ $comment->user->profile ? $comment->user->profile->avatar->url('thumb') : URL::asset('images/user.jpeg') }}"
                                             class="img-responsive img-circle"/>
                                    </a>
                                </div>
                                <div class="col-sm-10 comment-block">
                                    <h5>{{ profileLink($comment->user) }} on <span
                                                style="text-decoration: underline;">{{ $comment->created_at->format('l, d M Y g:i:s a') }}</span>
                                    </h5><br/>

                                    <div>{{ formatComment($comment->body) }}</div>
                                </div>
                            </div>
                            <hr/>
                        @endforeach
                    </div>

            </div>

        </div>

    </div>
@endif