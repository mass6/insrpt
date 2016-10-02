@extends($layout)

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Import Request Data'])
@stop

@section('content')

<div style="background-color:#ffffff;padding: 15px;">
    @if(isset($csv_error))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p>The CSV file is incorrect. Please check Admin Logs.
        </div>
    @endif
    @if(isset($count))
        @if($count > 0)
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p>Products were successfully updated.
            </div>
        @else
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p>Sorry, your request could not be completed. Invalid product sku.
            </div>
        @endif
    @endif
    <div id="notice_submit" class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <p>You must choose a CSV file to upload.
    </div>
    <div class="row" style="min-height: 200px;">
        <div class="col-md-12">
            <h3>Select the import format you wish to upload.</h3>
            <br/>
            <div class="btn-group">
                {{ Form::open(array('url' => '/catalogue/product-definitions/import_attributes','id'=>'upload_form','files'=>true,'method' => 'post')) }}
                <input type="file" name="import_file" id="import_file" />
                <input id="submit_import_file" style="margin-top: 20px;" type="submit" class="btn btn-danger" value="Import Attributes" />
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@stop

@section('bottomlinks')
    @parent

    <script type="text/javascript">
        $(document).ready(function() {
            $('#notice_submit').css('display','none');
            if($('.alert-info').is(':visible')){
                $('.alert-danger').css('display','none');
            }
        });
        $('#upload_form').submit(function(){
            var file = $('#import_file').val();
            if(!file){
                $('#notice_submit').css('display','block');
                return false;
            }
            return true;
        });
    </script>

@stop
