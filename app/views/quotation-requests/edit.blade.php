@extends($layout)

@section('links')
    @parent
    @include('common._editor-styles')
    @include('common._inline-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"/>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Quotation Request: ' . $quotation_request->quotation_request_id])
@stop

@section('content')

    @include('layouts.default.partials.errors')

    <div class="note note-info note-bordered">
        <h4>Status: <strong>{{ $quotation_request->currentStateLabel() }}</strong></h4>
        @if(isSiteOwner($currentUser))
            <h4>Customer: <strong>{{ $quotation_request->company->name }}</strong></h4>
        @endif
    </div>


    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs font-green-sharp"></i>
                <span class="caption-subject font-green-sharp bold uppercase">Quotation Items</span>
            </div>
            <div class="actions">
                <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="tabbable-custom">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a aria-expanded="true" href="#tab_15_1" data-toggle="tab">
                        Assign Quotation Requests to Supplier </a>
                    </li>
                    @if(in_array($quotation_request->getState(), ['SUB','RCV']))
                    <li class="">
                        <a aria-expanded="false" href="#tab_15_2" data-toggle="tab">
                        Enter Supplier Quotations </a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_15_1">
                        <br/>
                        <table id="quotation-requests-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Product Description</th>
                                    <th>Request ID</th>
                                    <th>UOM</th>
                                    <th>Qty</th>
                                    <th>Current Price</th>
                                    <th>Currency</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab_15_2">
                        <table id="received-quotations-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Quotation ID</th>
                                    <th>Received</th>
                                    <th>Quotation Reference</th>
                                    <th>Product Name</th>
                                    <th>Quoted Product Name</th>
                                    <th>Product Description</th>
                                    <th>Supplier SKU</th>
                                    <th>Current UOM</th>
                                    <th>Quoted UOM</th>
                                    <th>Current Price</th>
                                    <th>Quoted Price</th>
                                    <th>Currency</th>
                                    <th>Quoted Qty</th>
                                    <th>Quotation Date</th>
                                    <th>Valid Until</th>
                                    <th>Delivery Terms</th>
                                    <th>Payment Terms</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">

                <span class="caption-subject font-green-sharp bold uppercase">Supplier</span>
            </div>

        </div>
        <div class="portlet-body">

            <div class="row">
                {{ Form::open(['route' => ['quotation-requests.update', $quotation_request['quotation_request_id']], 'method' => 'PATCH']) }}
                <div class="col-md-6">
                    <div class="form-group">
                        <!-- Supplier Form Input -->
                        @if(!$quotation_request->sent && !in_array($quotation_request->getState(), ['RCV', 'COM', 'CLS']) )
                            {{ Form::label('supplier_id', 'Select a Supplier:') }}
                            {{ Form::select('supplier_id', [null => '- Select Supplier -'] + $suppliers, isset($quotation_request->supplier_id) ? $quotation_request->supplier_id : null,['class' => 'form-control','id' => 'supplier_id_input']) }}
                        @else
                            {{ Form::label('supplier_id', 'Supplier Selected:') }}
                            {{ Form::hidden('supplier_id', isset($quotation_request->supplier_id) ? $quotation_request->supplier_id : null) }}
                            {{ Form::text('supplier_name', isset($quotation_request->supplier_id) ? $quotation_request->supplier->name : null, ['class' => 'form-control', 'readonly']) }}
                        @endif
                        {{ $errors->first('supplier_id', '<span class="text text-danger">* :message</span>') }}
                    </div>

                @if(in_array($quotation_request->getState(), ['DRA', 'SUB']))

                        <!-- Send to supplier Form Input -->
                        <label>
                        {{ Form::checkbox('send_to_supplier', true, false, ['id' => 'send_to_supplier_input']) }} {{ !$quotation_request->sent ? 'If submitting, email Quotation Request to supplier?' : 'Resend Quotation Request to Supplier?'}}
                        </label>
                        <p class="text-warning" style="line-height: 10px;font-weight: bold;"><em>Note: Email is not sent when saving a draft.</em></p>
                        {{ $errors->first('send_to_supplier', '<span class="text text-danger">* :message</span>') }}


                <div id="message-div" class="form-group" style="display: none;">

                    <div class="form-group" style="">
                        <!-- Message Form Input -->
                        {{ Form::label('message', 'Message to Supplier:') }}
                        {{ Form::textarea('message', null, ['class' => 'form-control']) }}
                        {{ $errors->first('message', '<span class="text text-danger">* :message</span>') }}
                    </div>
                    <div class="form-group" style="">
                        <!-- Send to supplier Form Input -->
                        <label>
                        {{ Form::checkbox('cc_sender', true, false, ['id' => 'cc_sender_input']) }} CC yourself on email to supplier?
                        </label>
                    </div>
                    <div class="clear"></div>
                </div>
                @endif

                </div>
            </div>

            <br/>

            <div class="container">
                <!-- Submit button -->
                @if ($quotation_request->getState() === 'DRA')
                    {{ Form::submit('Save Draft', ['class' => 'btn btn-info', 'id' => 'save_draft_button', 'name' => "transition[save_draft]"]) }}
                    {{ Form::submit('Submit RFQ', ['class' => 'btn btn-success', 'id' => 'submit_rfq_button', 'name' => "transition[submit]"]) }}
                @else
                    @if ($quotation_request->getState() === 'SUB')
                    {{ Form::submit('Update', ['class' => 'btn btn-info', 'id' => 'save_draft_button', 'name' => "transition[save_submitted]"]) }}
                    {{ Form::submit('Mark All Quotations Received', ['class' => 'btn btn-success', 'id' => 'update_button', 'name' => "transition[receive_quotation]"]) }}
                    @endif
                @endif
                {{ Form::close() }}
                {{ Form::open(['route' => ['quotation-requests.destroy', $quotation_request->quotation_request_id], 'method' => 'DELETE', 'class' => 'doc-actions']) }}
                <a href="{{route('quotation-requests.duplicate', $quotation_request->quotation_request_id)}}" class="btn btn-primary" Onclick='return confirm("Create a duplicate of this quotation request?")'>Duplicate this Request</a>
                <a href="{{ route('product-requests.find-by-quotation-request', $quotation_request->quotation_request_id) }}" class="btn blue">View Product Requests</a>
                @if ($quotation_request->getState() === 'DRA')
                    {{ Form::submit('Delete RFQ', ['class' => 'btn btn-danger', 'id' => 'delete_draft_button', 'name' => "transition[delete_draft]", 'Onclick' => 'return confirm("Delete this Quotation Request?")']) }}
                @else
                    {{ Form::submit('Delete RFQ', ['class' => 'btn btn-danger', 'id' => 'delete_draft_button', 'name' => "transition[close]", 'Onclick' => 'return confirm("Delete this Quotation Request?")']) }}
                @endif
            </div>

            {{ Form::close() }}

        </div>
    </div>



@stop

@section('bottomlinks')
    @parent
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    @include('common._editor-js')
    <script src="{{ URL::asset('js/jquery.multi-select.js') }}"></script>
    <script>

    $(document).ready(function() {

        var editor; // use a global for the submit and return data rendering in the quotation-requests-table

        if (   $('#send_to_supplier_input').is(":checked")   ) {
            $('#message-div').show();
        }
        $('#send_to_supplier_input').click(function() {
           $('#message-div').toggle(500);
        });

        $('.nav-tabs li a').click(function(e) {
            reloadTables();
        });


        editor = new $.fn.dataTable.Editor( {
            ajax: {
                    edit: {
                        type: 'PATCH',
                        url:  '/quotations/_id'
                    },
                    remove: {
                        type: 'POST',
                        url:  '/quotations/_id_'
                    }
                },
            table: '#quotation-requests-table',
            idSrc: "id",
            fields: [ {
                    label: "Product Description:",
                    name: "product_description"
                }, {
                    label: "UOM:",
                    name: "uom"
                }, {
                    label: "Quantity:",
                    name: "volume"
                }, {
                    label: "Current Price:",
                    name: "current_price"
                }, {
                    label: "Currency:",
                    name: "current_price_currency"
                }
            ]

        } );

        editor2 = new $.fn.dataTable.Editor( {
            ajax: {
                    edit: {
                        type: 'PATCH',
                        url:  '/quotations/_id'
                    },
                    remove: {
                        type: 'DELETE',
                        url:  '/quotations/_id_'
                    }
                },
            table: '#received-quotations-table',
            idSrc: "id",
            fields: [ {
                    label: "Quotation Reference:",
                    name: "supplier_reference"
                }, {
                    label: "Quotation Date:",
                    name: "quotation_date",
                    type: "date",
                    def:        function () { return new Date(); },
                    dateFormat: $.datepicker.ISO_8601
                }, {
                    label: "Product Name:",
                    name: "suppliers_product_name"
                }, {
                    label: "Product Description:",
                    name: "suppliers_product_description"
                }, {
                    label: "Supplier SKU:",
                    name: "suppliers_product_sku"
                }, {
                    label: "UOM:",
                    name: "suppliers_uom"
                }, {
                    label: "Quantity:",
                    name: "suppliers_quantity"
                }, {
                    label: "Unit Price:",
                    name: "unit_price"
                }, {
                    label: "Currency:",
                    name: "price_currency"
                },{
                    label: "Valid Until:",
                    name: "valid_until",
                    type: "date",
                    def:        function () { return new Date(); },
                    dateFormat: $.datepicker.ISO_8601
                },{
                    label: "Delivery Terms:",
                    name: "delivery_terms",
                    type: "textarea"
                },{
                    label: "Payment Terms:",
                    name: "payment_terms",
                    type: "textarea"
                },{
                    label:     "Mark As Received:",
                    name:      "received",
                    type:      "checkbox",
                    separator: "|",
                    options:   [
                        { label: '', value: 1 }
                    ]
                }
            ]

        } );

        var table = $('#quotation-requests-table').DataTable( {
            dom: 'Bfrtip',
            ajax: "/quotations/get-by-quotation-request/" + "<?php echo $quotation_request->id; ?>",
            scrollY:        '30vh',
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            columns: [
                { data: "product_description" },
                {
                    data: "product_request.request_id",
                    render: function ( data, type, full, meta ) {
                        if(data) {
                            return '<a href="/product-requests/' + data + '">' + data + '</a>';
                        } else {
                            return '';
                        }
                    }
                },
                { data: "uom" },
                { data: "volume" },
                {data: "current_price"},
                { data: "current_price_currency" }
            ],
            order: [ 0, 'asc' ],
            keys: {
                keys: [ 9 ]
            },
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
                { extend: "edit",   editor: editor },
                { extend: "remove", editor: editor }
            ]
        } );


        // Buttons array definition to create previous, save and next buttons in
        // an Editor form
        var backNext = [
            {
                label: "&lt;",
                fn: function (e) {
                    // On submit, find the currently selected row and select the previous one
                    this.submit( function () {
                        var indexes = table2.rows( {search: 'applied'} ).indexes();
                        var currentIndex = table2.row( {selected: true} ).index();
                        var currentPosition = indexes.indexOf( currentIndex );

                        if ( currentPosition > 0 ) {
                            table2.row( currentIndex ).deselect();
                            table2.row( indexes[ currentPosition-1 ] ).select();
                        }

                        // Trigger editing through the button
                        table2.button( 0 ).trigger();
                    }, null, null, false );
                }
            },
            'Save',
            {
                label: "&gt;",
                fn: function (e) {
                    // On submit, find the currently selected row and select the next one
                    this.submit( function () {
                        var indexes = table2.rows( {search: 'applied'} ).indexes();
                        var currentIndex = table2.row( {selected: true} ).index();
                        var currentPosition = indexes.indexOf( currentIndex );

                        if ( currentPosition < indexes.length-1 ) {
                            table2.row( currentIndex ).deselect();
                            table2.row( indexes[ currentPosition+1 ] ).select();
                        }

                        // Trigger editing through the button
                        table2.button( 0 ).trigger();
                    }, null, null, false );
                }
            }
        ];

        var table2 = $('#received-quotations-table').DataTable( {
            dom: 'Bfrtip',
            ajax: "/quotations/get-by-quotation-request/" + "<?php echo $quotation_request->id; ?>",
            scrollY:        '30vh',
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                {data: "quotation_id"},
                {
                    data:   "state",
                    render: function ( data, type, row ) {
                        if ( data === 'RCV' ) {
                            return 'Received';
                        } else if (data === 'SUB') {
                            return 'Submitted';
                        } else if (data === 'CLS') {
                            return 'Closed';
                        }
                        return 'Draft';
                    },
                    className: "dt-body-center"
                },
                {data: "supplier_reference"},
                {data: "product_description"},
                {data: "suppliers_product_name"},
                {data: "suppliers_product_description"},
                {data: "suppliers_product_sku"},
                {data: "uom"},
                {data: "suppliers_uom"},
                {data: "current_price"},
                {data: "unit_price"},
                {data: "price_currency"},
                {data: "suppliers_quantity"},
                {data: "quotation_date"},
                {data: "valid_until"},
                {data: "delivery_terms"},
                {data: "payment_terms"}
            ],
            order: [ 0, 'asc' ],
            keys: {
                columns: ':not(:first-child)',
                keys: [ 9 ]
            },
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
                {
                    extend: 'selected',
                    text:   'Edit',
                    action: function () {
                        var indexes = table2.rows( {selected: true} ).indexes();

                        editor2.edit( indexes, {
                            title: 'Edit',
                            buttons: indexes.length === 1 ?
                                backNext :
                                'Save'
                        } );
                    }
                },
                { extend: "remove", editor: editor2 },
                { extend: "selectAll",   editor: editor2 },
                { extend: "selectNone",   editor: editor2 },
                {
                    extend: "selectedSingle",
                    text: "Mark As Received",
                    action: function ( e, dt, node, config ) {
                        editor2
                            .edit( table2.row( { selected: true } ).index(), false )
                            .set( 'received', 1)
                            .submit();
                    }
                }
            ]
        } );

        var reloadTables = function() {
            table.ajax.reload();
            table2.ajax.reload();
        }

        // Activate an inline edit on click of a table cell
        table.on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this );
        } );
        // Activate an inline edit on click of a table cell
        table2.on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor2.inline( this );
        } );

        table.on( 'key-focus', function ( e, datatable, cell ) {
            editor.inline( cell.index(), {
                onBlur: 'submit'
            } );
        } );

        table2.on( 'key-focus', function ( e, datatable, cell ) {
            editor2.inline( cell.index(), {
                onBlur: 'submit'
            } );
        } );


    });

</script>
@stop