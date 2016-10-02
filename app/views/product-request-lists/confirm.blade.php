@extends($layout)

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Request Upload Wizard', 'subheading' => 'Confirm Data'])
@stop

@section('content')

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-speech"></i>
                <span class="caption-subject bold uppercase"> Step 2.</span>
                <span class="caption-helper">Confirm data to upload</span>
            </div>
            <div class="actions">
                <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""></a>
            </div>
        </div>
        <div class="portlet-body">

            <div class="panel panel-info" data-collapsed="0">

                <!-- panel head -->
                <div class="panel-heading">
                    <div class="panel-title">Import Summary</div>
                </div>

                <!-- panel body -->
                <div class="panel-body">
                    <div class="form-group">

                        <p>List name to be created: <span class="text-info">{{ $uploadData['name'] }}</span></p>

                        <p>Number of Product Requests to created: <span class="text-info">{{ $uploadData['numRequests'] }}</span></p>

                        <p>Requester: <span class="text-info">{{ $uploadData['requesterName'] }}</span></p>

                    </div>
                    <hr/>
                    <div>
                        <p class="lead">
                            Your file is ready to be processed!
                        </p>
                        <p>
                            To create and submit all requests at the same time, click the <span class="text text-success">Process & Submit</span>.
                        </p>
                        <p>
                            If you wish to create the requests but not submit them yet, click <span class="text text-info">Process & Create Drafts</span>.
                        </p>
                    </div>
                    <br/>

                    <div class="form-group">
                        {{ Form::open(['route' => ['product-request-lists.store'], 'method' => 'POST']) }}
                                <!-- Submit button -->
                        {{--{{ Form::submit('Process File', ['class' => 'btn btn-success', 'id' => 'Process_button']) }}--}}
                        {{ Form::submit('Process & Submit', ['class' => 'btn btn-' . transitionButtonColor('submit_request'),'name' => "transition[submit_request]"]) }}
                        {{ Form::submit('Process & Create Drafts', ['class' => 'btn btn-' . transitionButtonColor('save_draft'),'name' => "transition[save_draft]"]) }}
                        <a href="{{ route('product-request-lists.create')}}" class="btn btn-default"
                           style="margin-left: 5px">Back</a>
                        {{ Form::close() }}
                    </div>


                </div>

            </div>

        </div>
    </div>


@stop