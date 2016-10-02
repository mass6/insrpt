@extends('layouts.default.layout')

@section('content')

<h2>Sourcing Request</h2>
 <h3 class="text text-info">{{ $sourcing_request->customer_sku . ': ' . $sourcing_request->customer_product_description }}</h3>
<hr/>


@include('layouts.default.partials.errors')

    {{ Form::model($sourcing_request, ['route' => ['sourcing-requests.update', $sourcing_request->id], 'method' => 'PATCH', 'id' => 'edit-form', 'name' => 'edit-form']) }}

        <?php $submit = 'Update'; ?>
        @include('sourcing-requests.partials._form')

    {{ Form::close() }}


    <!-- History &Comments -->
    <div id="comments" class="row">

        <div class="col-md-10">

            <div class="well well-lg">
                <h3>History & Comments</h3>
                @if(count($sourcing_request->comments))
                <div class="clear"></div>
                <hr/>
                <div class="comment-history">
                    @foreach ($sourcing_request->comments->reverse() as $comment)
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="{{ route('profiles.show', $comment->user->id) }}" class="profile-picture">
                                <img src="{{ $comment->user->profile ? $comment->user->profile->avatar->url('thumb') : URL::asset('images/user.jpeg') }}" class="img-responsive img-circle"  />
                            </a>
                        </div>
                        <div class="col-sm-10 comment-block">
                            <h5>{{ profileLink($comment->user) }} on <span style="text-decoration: underline;">{{ $comment->created_at->format('l, d M Y g:i:s a') }}</span></h5><br/>
                            <div>{{ formatComment($comment->body) }}</div>
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

    </div>


@stop