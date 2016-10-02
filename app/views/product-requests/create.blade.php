@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('js/dropzone-OLD/dropzone.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
    <meta name="_token" content="{{ csrf_token() }}" />
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'New Product Request'])
@stop

@section('content')

    @include('layouts.default.partials.errors')

    <div class="note note-warning note-bordered">
        <p>If the request is to replace an existing product, please be sure to complete the existing product and supplier details as well.</p>
    </div>

    {{ Form::model($product_request = new Insight\ProductRequests\ProductRequest, ['route' => ['product-requests.store'], 'id' => 'productrequestform']) }}
    <?php $product_request->company = $currentUser->company; ?>
    @include('product-requests.partials._form')

@stop

@section('bottomlinks')
    @parent
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ URL::asset('js/dropzone-OLD/dropzone.js') }}"></script>
    <script src="{{ URL::asset('js/dropzone-OLD/dropzone.config.js') }}"></script>
    <script src="{{ URL::asset('js/pages/product-request-form.js') }}"></script>
@stop
