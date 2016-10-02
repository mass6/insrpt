@extends($layout)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-5 col-sm-offset-2">
                <h2>New Supplier Company</h2>
                <br />
            </div>
        </div>

        <div class="row">
            @include('layouts.default.partials.errors')

            {{ Form::open(['route' => 'admin.companies.add_supplier', 'class' => 'form-horizontal form-groups-bordered']) }}
            <?php $submit = 'Submit'; ?>
            @include('admin.companies._supplier_form')

            {{ Form::close() }}
        </div>
    </div>
@stop