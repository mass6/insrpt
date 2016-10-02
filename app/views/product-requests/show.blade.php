@extends($layout)

@section('links')
    @parent
    {{--<link rel="stylesheet" href="{{ URL::asset('js/dropzone/basic.css') }}">--}}
    <link rel="stylesheet" href="{{ URL::asset('js/dropzone-OLD/dropzone.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ asset('metronic/assets/admin/layout3/css/blockquotes.css') }}" rel="stylesheet" type="text/css">
    <meta name="_token" content="{{ csrf_token() }}"/>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Request ID: <strong>' . $product_request->request_id . '</strong>'])
@stop

@section('content')

    @include('product-requests.partials._meta')

    <div class="row">
        @if(count($product_request->quotations) && $currentUser->hasAccess('quotations.create'))
            @include('product-requests.partials._quotations')
        @endif
        @if(count($proposals))
            @include('product-requests.partials._proposals', ['proposals' => $proposals, 'displayQuotations' => $currentUser->hasAccess('quotations.*')])
        @endif
    </div>

    @include('product-requests.partials._data')


    {{-- Actions --}}
    <?php $removeTransitions = ['submit_proposal', 'approve', 'reject'] ; ?>
    <div class="row">
        <div class="col-md-12">
            @if(canEdit($product_request, $currentUser))
                <a href="{{route('product-requests.edit', $product_request->request_id)}}" class="btn blue">Edit Request</a>
            @endif
            <a href="{{ URL::previous() }}" class="btn grey-cascade">Back</a>
        </div>
    </div>

    <div class="clear"></div>
    <br/>




    @include('common._comments', ['model' => $product_request])

@stop

@section('bottomlinks')
    @parent
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('js/pages/product-proposals.js') }}"></script>
@stop