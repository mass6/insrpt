@extends($layout)

@section('links')
    @parent
    <link href="{{ asset('metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css') }}" rel="stylesheet" type="text/css">
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'User Administration', 'subheading' => 'New'])
@stop

@section('content')

    @include('layouts.default.partials.errors')

    {{ Form::open(['route' => 'admin.users.store', 'class' => 'form-horizontal form-groups-bordered']) }}

        <?php $submit = 'Submit'; ?>
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