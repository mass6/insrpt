@extends($layout)

@section('content')

    <h2>Sourcing Request Import Wizard</h2>
    <br/>
    <h3>Step 2: Confirm import data</h3>
    <hr/>

    <div class="col-md-6">
        <div class="panel panel-info" data-collapsed="0">

            <!-- panel head -->
            <div class="panel-heading">
                <div class="panel-title">Import Summary</div>
            </div>

            <!-- panel body -->
            <div class="panel-body">
                <div class="form-group">

                    <p>Customer: <span class="text-info">{{ $sheetData['customer']->name }}</span></p>
                    <p>Received On: <span class="text-info">{{ $sheetData['received_on'] }}</span></p>
                    <p>Batch Reference: <span class="text-info">{{ $sheetData['batch'] }}</span></p>
                    <p>Status: <span class="text-info">{{ $sheetData['statusName'] }}</span></p>
                    <p>Assign To: <span class="text-info">{{ $sheetData['assignToName'] }}</span></p>
                    <p>Num of Requests to Import: <span class="text-info">{{ $sheetData['numRequests'] }}</span></p>

                </div>
                <hr/>

                <div class="form-group">
                    {{ Form::open(['route' => ['sourcing-requests.import.process'], 'method' => 'POST']) }}
                    <!-- Submit button -->
                    {{ Form::submit('Process File', ['class' => 'btn btn-success', 'id' => 'Process_button']) }}
                    <a href="{{ route('sourcing-requests.import.create')}}" class="btn btn-info" style="margin-left: 5px">Back</a>
                    {{ Form::close() }}
                </div>


            </div>

        </div>
    </div>
    <div class="clear"></div>



@stop