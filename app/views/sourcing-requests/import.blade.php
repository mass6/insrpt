@extends($layout)

@section('links')
    @parent
    <link rel="stylesheet" href="{{ URL::asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
@stop

@section('content')

@if(Session::has('errorDetails'))
    @include('sourcing-requests.partials._error-details')
@endif

    <h2>Sourcing Request Import Wizard</h2>
    <br/>

    <h3 class="text-info">Step 1: Upload your file and enter common request information</h3>


    <p>
        <a href="javascript:;" onclick="jQuery('#modal-1').modal('show');" class="btn btn-success">See File Requirements</a>
    </p>
    <br/>

    <div class="row" style="min-height: 200px;">
        {{ Form::open(['route' => ['sourcing-requests.import.store'],'id'=>'upload_form','files'=>true,'method' => 'POST']) }}

        <div class="col-md-8">
            <div class="panel panel-info" data-collapsed="0">

                <!-- panel head -->
                <div class="panel-heading">
                    <div class="panel-title">General Information</div>
                </div>

                <!-- panel body -->
                <div class="panel-body">


                    <div class="form-group col-md-6">
                        <!-- Customer id Form Input -->
                        {{ Form::label('customer_id', 'Select Customer:') }}
                        {{ Form::select('customer_id', $customers, null, ['class' => 'form-control', 'id' => 'customer_id_input']) }}
                        {{ $errors->first('customer_id', '<span class="text text-danger">* :message</span>') }}
                    </div>
                    <div class="btn-group col-md-6">
                        <!-- File Upload -->
                        {{ Form::label('importfile', 'Import File:') }}
                        {{ Form::file('importfile', array('id' => 'importfile', 'class' => 'form-control', 'required')) }}
                        {{ $errors->first('importfile', '<span class="text text-danger">* :message</span>') }}
                    </div>

                </div>

            </div>
        </div>
        <div class="clear"></div>

        <div class="col-md-8">
            <div class="panel panel-info" data-collapsed="0">

                <!-- panel head -->
                <div class="panel-heading">
                    <div class="panel-title">Optional Information:</div>

                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>

                <!-- panel body -->
                <div class="panel-body">
                    <blockquote>
                        <p>The below information is optional. If no values are provided, then default values will be assigned.</p>
                    </blockquote>
                    <br/>
                    <div class="col-md-6">

                        <div class="form-group">
                            <!-- Batch Form Input -->
                            {{ Form::label('batch', 'Batch Reference:') }}
                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="A reference code or number used to group the imported Sourcing Requests togther.">
                            </i>
                            {{ Form::text('batch', null, ['class' => 'form-control', 'id' => 'batch_input', 'placeholder' => $currentUser->first_name[0] . $currentUser->last_name[0] . '-' . Carbon::now(getenv('APP_TIMEZONE'))->format('ymdhi')]) }}
                            {{ $errors->first('batch', '<span class="text text-danger">* :message</span>') }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('received_on', 'Received on:') }}
                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Date requests was received from customer.">
                            </i>
                            <div class="input-group date">
                                {{ Form::text('received_on', null,
                                ['id'=>'received_on_input','class'=>'form-control datepicker', 'data-format' => "dd-mm-yyyy", "placeholder" => Carbon::today()->format('d-m-Y')]) }}
                                <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                            {{ $errors->first('received_on', '<span class="text text-danger">* :message</span>') }}
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <!-- Status Form Input -->
                            {{ Form::label('status', 'Status:') }}
                            {{ Form::select('status', $statuses, null, ['class' => 'form-control', 'id' => 'status_input']) }}
                            {{ $errors->first('status', '<span class="text text-danger">* :message</span>') }}
                        </div>
                        <div class="form-group">
                            <!-- Assigned To Form Input -->
                            {{ Form::label('assigned_to_id', 'Assign to:') }}
                            {{ Form::select('assigned_to_id', $assignableUsers, $currentUser->id, ['class' => 'form-control', 'id' => 'assigned_to_id_input']) }}
                            {{ $errors->first('assigned_to_id', '<span class="text text-danger">* :message</span>') }}
                        </div>


                    </div>
                    <div class="clear"></div>
                    <hr/>
                    <div class="col-md-12">
                        {{ Form::submit('Import', ['class' => 'btn btn-primary', 'id' => 'import_button']) }}
                    </div>

                </div>

            </div>
        </div>
        <div class="clear"></div>
        {{ Form::close() }}


    </div>

    <script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>

@stop

@section('subfooter')

        <!-- Modal 1 (Basic)-->
    <div class="modal fade" id="modal-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">File Requirements</h4>
                </div>

                <div class="modal-body">

                    <p>The file must be in spreadsheet format(.xlsx, .xls, or .csv), containing the following column headers in the first row.
                    <ul>
                        <li>line_number: &nbsp;<em>(sequential number</em></li>
                        <li>customer_sku: &nbsp;<em>(optional, unique if provided)</em></li>
                        <li>customer_product_description: &nbsp;<strong><em>(required)</em></strong></li>
                        <li>customer_price:</li>
                        <li>customer_price_currency: &nbsp;<em>(required with customer_price)</em></li>
                        <li>customer_uom:</li>
                    </ul>
                    <hr/>

                    <p>
                        <a href="{{ URL::asset('documents/sample-sourcing-request-import-file.xlsx') }}" class="btn btn-primary btn-sm">Download sample file</a>
                    </p>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop