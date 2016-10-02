@extends($layout)

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Permissions'])
@stop

@section('content')
    @include('layouts.default.partials.errors')

    {{ Form::open(['route' => 'admin.permissions.store', 'class' => 'form-horizontal form-groups-bordered']) }}

        <?php $submit = 'Submit'; ?>
        @include('admin.permissions._form')

    {{ Form::close() }}


@stop