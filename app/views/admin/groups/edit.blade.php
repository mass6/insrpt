@extends($layout)

@section('links')
    @parent
    <link href="{{ asset('metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css') }}" rel="stylesheet" type="text/css">
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Permissions Group Administration', 'subheading' => 'Edit'])
@stop

@section('content')

    {{ Form::model($group, ['route' => ['admin.groups.update', $group->id], 'method' => 'PATCH', 'class' => 'form-horizontal form-groups-bordered']) }}

        <?php $submit = 'Update'; ?>
        @include('admin.groups._form')

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




