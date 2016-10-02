@extends($layout)

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Request Upload Wizard', 'subheading' => 'Upload File'])
@stop

@section('content')

@if(Session::has('errorDetails'))
    @include('product-requests.partials._error-details')
@endif


<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-speech"></i>
            <span class="caption-subject bold uppercase"> Step 1.</span>
            <span class="caption-helper">Upload your file containing product request information</span>
        </div>
        <div class="actions">
            <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""></a>
        </div>
    </div>
    <div class="portlet-body">

            {{ Form::open(['route' => ['product-request-lists.confirm'],'id'=>'upload_form','files'=>true,'method' => 'POST']) }}

                @if($currentUser->hasAccess('product-requests.create-on-behalf'))
                    @include('product-request-lists.partials._requester-chooser')
                @endif

                <p>
                    <a href="javascript:;" onclick="jQuery('#modal-1').modal('show');" class="btn btn-sm btn-success">See File Requirements</a>
                </p>
                <br/>

                <div class="row" style="min-height: 200px;">

                    <div class="col-md-8">
                        <div class="panel panel-info" data-collapsed="0">

                            <!-- panel head -->
                            <div class="panel-heading">
                                <div class="panel-title">File Upload</div>
                            </div>

                            <!-- panel body -->
                            <div class="panel-body">
                                <div class="btn-group col-md-6">
                                    <!-- File Upload -->
                                    {{ Form::label('uploadfile', 'File to upload:') }}
                                    {{ Form::file('uploadfile', array('id' => 'uploadfile', 'class' => 'form-control', 'required')) }}
                                    {{ $errors->first('uploadfile', '<span class="text text-danger">* :message</span>') }}
                                </div>
                                <div class="btn-group col-md-6">
                                    <!-- List-name Form Input -->
                                    {{ Form::label('name', 'List Name:') }}
                                    {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name_input']) }}
                                    {{ $errors->first('name', '<span class="text text-danger">* :message</span>') }}
                                </div>

                                <div class="clear"></div>
                                <br/>
                                <div class="col-md-12">
                                    <br/>
                                    {{ Form::submit('Upload', ['class' => 'btn btn-primary', 'id' => 'upload_button']) }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            {{ Form::close() }}



    </div>
</div>





@stop

@section('subfooter')
    @include('product-request-lists.partials._file-requirements')
@stop

@section('bottomlinks')
    @parent

    <script>
        $(document).ready(function(){

            var getRequesterList = function(company) {
                $.get("/product-request-lists/create", { 'company': company } )
                  .done(function( data ) {
                    var options = $("#requested_by_id_input").empty();
                    $.each(data, function() {
                        options.append($("<option />").val(this.id).text(this.name));
                    });
                });
            };
            var getCompanyRequester = function() {
                getRequesterList($('#company_id_input').val());
            };

            $('#company_id_input').change(function(e){
                getCompanyRequester();
            });

            $('.expand').click(function() {
                console.log($('#requester-list').css('display'));
               if($('#requester-list').css('display') === 'none') {
                   $('#company_id_input').attr('disabled', false);
                   $('#requested_by_id_input').attr('disabled', false);
                   getCompanyRequester();
               } else {
                   $('#company_id_input').attr('disabled', true);
                   $('#requested_by_id_input').attr('disabled', true);
               }
            });



        });


    </script>

@stop