@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css') }}"/>
<link href="{{ asset('metronic/assets/admin/layout3/css/blockquotes.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('js/dropzone-OLD/dropzone.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
    <meta name="_token" content="{{ csrf_token() }}" />
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Request ID: <strong>' . $product_request->request_id . '</strong>', 'subheading' => '[Editing]'])
@stop

@section('content')

    @include('layouts.default.partials.errors')

    @if (in_array($product_request->getState(), ['DRA', 'INP']))
        <div class="note note-warning note-bordered">
            <p>If the request is to replace an existing product, please be sure to complete the existing product and supplier details as well.</p>
        </div>
    @endif

    @include('product-requests.partials._meta')
    <div class="row">
        @if(count($product_request->quotations) && $currentUser->hasAccess('quotations.create'))
            @include('product-requests.partials._quotations')
        @endif
        @if(count($proposals) || $currentUser->hasAccess('product-proposals.create'))
            @include('product-requests.partials._proposals', ['proposals' => $proposals, 'displayQuotations' => $currentUser->hasAccess('quotations.*')])
        @endif
    </div>

    {{ Form::model($product_request, ['route' => ['product-requests.update', $product_request->request_id], 'id' => 'productrequestform', 'method' => 'PATCH']) }}

    @include('product-requests.partials._form')

    @include('common._comments', ['model' => $product_request])

@stop

@section('bottomlinks')
    @parent
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('js/dropzone-OLD/dropzone.js') }}"></script>
    <script src="{{ URL::asset('js/dropzone-OLD/dropzone.config.js') }}"></script>
    <script src="{{ URL::asset('js/jquery.multi-select.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.0/jquery.scrollTo.min.js"></script>
    <script src="{{ URL::asset('js/pages/product-request-form.js') }}"></script>
    <script src="{{ URL::asset('js/pages/product-proposals.js') }}"></script>
    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.min.js"></script>
    <script src="{{ URL::asset('js/pages/product-matches.js') }}"></script>
@stop