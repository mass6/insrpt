@extends($layout)

@section('links')
    @parent
    <link href="{{ asset('metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css') }}" rel="stylesheet" type="text/css">
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'User Administration', 'subheading' => 'Edit'])
@stop

@section('content')

    @if(Session::has('is_updated') && !Session::get('is_updated'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p>Cannot change this user's company because this user is designated a primary contact for the current company.
        </div>
        <?php Session::forget('is_updated');?>
    @endif

    {{ Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'PATCH', 'class' => 'form-horizontal form-groups-bordered']) }}

        <?php $submit = 'Update'; ?>
        @include('admin.users._form')

    {{ Form::close() }}


@stop

@section('bottomlinks')
    @parent
    <script src="{{ asset('metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.multi-select').multiSelect();
        });
    </script>
@stop