@extends($layout)

@section('links')
    @parent
    {{--<link rel="stylesheet" href="{{ URL::asset('js/dropzone/basic.css') }}">--}}
    <link rel="stylesheet" href="{{ URL::asset('js/dropzone-OLD/dropzone.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
    <meta name="_token" content="{{ csrf_token() }}" />
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'New Product Proposal'])
@stop

@section('content')


    @include('product-requests.partials._data', ['product_request' => $product_request, 'collapsed' => 1, 'panel_type' => 'info' ])
    @if($quotation)
        @include('quotations.partials._data')
    @endif

    {{ Form::open(['route' => ['product-proposals.store', $product_request->id]]) }}

    @include('product-proposals.partials._form')

    {{ Form::close() }}

@stop

@section('bottomlinks')
    @parent
    <script src="{{ URL::asset('js/dropzone-OLD/dropzone.js') }}"></script>
    <script src="{{ URL::asset('js/dropzone-OLD/dropzone.proposals-config.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-switch.min.js') }}"></script>
    <script>

        $(document).ready(function () {

            $('#fill-from-request').click(function(e) {
                e.preventDefault();
                $('#product_name_input').val($('#product_description_input').val());
                $('#product_description_text').val($('#product_description_input').val());
                $('#uom_input_text').val($('#uom_input').val());
                $('#volume_input_text').val($('#first_time_order_quantity_input').val());
            });
            $('#fill-from-quotation').click(function(e) {
                e.preventDefault();
                $('#product_name_input').val($('#suppliers_product_name').html());
                $('#product_description_text').val($('#suppliers_product_description').html());
                $('#uom_input_text').val($('#suppliers_uom').html());
                $('#volume_input_text').val($('#suppliers_quantity').html());
            });

        });

        $(function() {
            $(".attachment-wrap button").click(function(e) {
                var attachmentDiv =  $(this).parent();
                var confirmDeleteImage = confirm("Permanently delete this " + type + "?");
                if (confirmDeleteImage) {
                    var type = attachmentDiv.attr("data-type");
                    var id = attachmentDiv.attr("data-id");
                    $.ajax({
                        url: "/product-requests/attachments/" + type + "/" + id,
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        type: "DELETE",
                        success: function (response) {
                            attachmentDiv.slideUp();
                        }
                    });
                }
            });
        });
    </script>

@stop