@extends('layouts.default.layout')

@section('content')

<h2>New Sourcing Request</h2>

<hr/>


@include('layouts.default.partials.errors')

    {{ Form::model($sourcing_request = new \Insight\Sourcing\SourcingRequest, ['route' => ['sourcing-requests.store'], 'method' => 'POST', 'id' => 'create-form', 'name' => 'create-form']) }}

        <?php $submit = 'Submit'; ?>
        @include('sourcing-requests.partials._form')

    {{ Form::close() }}

@stop