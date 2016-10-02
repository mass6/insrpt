@extends($layout)

@section('links')
    @parent
        <!-- BEGIN PACE PLUGIN FILES -->
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/pace/pace.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/pace/themes/pace-theme-big-counter.css') }}"/>
    <!-- END PACE PLUGIN FILES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <style>
        input.search_init.text_filter {
            width: 100%;
        }
        th.width-70 input.search_init.text_filter {
            width: 70px;
        }
        th.width-90 input.search_init.text_filter {
            width: 90px;
        }
    </style>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Requests'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-docs"></i>
                    <span class="caption-subject bold uppercase">
                        {{ isset($filterBy) && isset($filterValue) ? 'Filtered By  ' . ucwords($filterBy) . ' : ' . $filterValue : 'All Product Requests' }}
                    </span>
                </div>
                <div class="actions">
                    @if($currentUser->hasAccess('product-requests.create'))
                        <a href="{{ route('product-requests.create') }}" class="btn btn-circle blue ">
                            <i class="fa fa-plus"></i> New Request
                        </a>
                    @endif
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div id="actions-div" class="col-md-3">



                    <form action="/action" method="POST" id="action-form">
                        {{ Form::token() }}
                        <input type="hidden" id="request_ids" name="request_ids" value=""/>
                        <input type="hidden" id="transition" name="transition" value=""/>

                    <span class="text text-primary">Bulk Actions</span>
                    <div class="input-group">
                        <select class="form-control" id="actions" style="" disabled>
                            <option value="">Select...</option>
                            @if($currentUser->hasAccess('product-requests.submit_request'))
                                <option value="product-requests/apply-transition" data-transition="submit_request">Submit Request</option>
                            @endif
                            @if($currentUser->hasAccess('product-requests.submit_for_sourcing'))
                                <option value="product-requests/apply-transition" data-transition="submit_for_sourcing">Submit for Sourcing</option>
                            @endif
                            @if($currentUser->hasAccess('product-requests.submit_for_pricing'))
                                <option value="product-requests/apply-transition" data-transition="submit_for_pricing">Submit for Pricing</option>
                            @endif
                            @if($currentUser->hasAccess('product-requests.submit_for_cataloguing'))
                                <option value="product-requests/apply-transition" data-transition="submit_for_cataloguing">Submit for Cataloguing</option>
                            @endif
                            @if($currentUser->hasAccess('product-requests.submit_for_deployment'))
                                <option value="product-requests/apply-transition" data-transition="submit_for_deployment">Submit for Deployment</option>
                            @endif
                            @if($currentUser->hasAccess('product-requests.complete'))
                                <option value="product-requests/apply-transition" data-transition="complete">Mark Complete</option>
                            @endif
                            @if($currentUser->hasAccess('product-requests.close'))
                                <option value="product-requests/apply-transition" data-transition="close">Close</option>
                            @endif
                            @if($currentUser->hasAccess('quotations.create'))
                                <option value="quotation-requests">Create Quotation Request</option>
                            @endif
                        </select>
                        <span  class="input-group-btn" >
                            <input type="submit" class="btn btn-primary" value="Submit" id="submit-action" style="margin-left: 10px;display: none;"  />
                        </span>
                    </div>
                    <br/>

                        <div id="reason_closed_div" class="input-group" style="display:none;">
                            <!-- Reason closed Form Input -->
                            {{ Form::label('reason_closed', 'Reason closed:') }}
                            {{ Form::select('reason_closed', [null => 'Select...', 'DUP' => 'Duplicate', 'WNS' => 'Will not source'], null, ['class' => 'form-control', 'id' => 'reason_closed_input', 'required', 'disabled']) }}
                        </div>
                        <br/>
                        {{ Form::submit('Close Request', ['class' => 'btn btn-black', 'id' => 'close_button', 'style' => 'margin-top:-10px;display:none;']) }}
                        <p id="selection-counter" class="lead"></p>
                    </form>
                </div>
                <div class="clear"></div>
                <br/>

                <div class="row">
                    <div class="col-md-12">
                        <span style="color:#505050;">Show/Hide:</span>
                           <a href="#" id="category-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="3">Category</a>
                          <a href="#" id="uom-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="4">UOM</a>
                          <a href="#" id="customer-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="5">Customer</a>
                          <a href="#" id="requester-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="6">Requester</a>
                          <a href="#" id="created-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="7">Created</a>
                          <a href="#" id="list-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="8">List</a>
                          <a href="#" id="first-time-order-quantity-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="9">Order Qty</a>
                          <a href="#" id="volume-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="10">Recurrence Qty</a>
                          <a href="#" id="cataloguing-item-code-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="11">Catalogue Item Code</a>
                          <a href="#" id="remarks-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="22">Remarks</a>
                          <a href="#" id="quotations-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="24">Completed</a>
                        @if ($currentUser->hasAccess('quotations.create'))
                          <a href="#" id="quotations-toggle" class="toggle-vis btn btn-xs primary-36s" data-column="25">Quotations</a>
                        @endif
                    </div>
                </div>
                <div class="clear"></div>
                <br/>

                <div class="row">
                    <div class="col-md-12">
                        <button id="remove-filters" class="btn btn-sm yellow-gold pull-right">Remove All Filters</button>
                    </div>
                </div>
                <div class="clear"></div>

                <table id="product-requests-table" class="table table-striped table-bordered table-hover" style="font-size: .9em;">
                    <thead>
                        <tr class="replace-inputs">
                            <th></th>
                            <th class="width-70"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="width-70"></th>
                            <th>Select</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Select</th>
                            <th></th>
                            @if($currentUser->hasAccess('quotations.create'))
                                <th class="width-90"></th>
                            @endif
                            <th></th>
                        </tr>
                        <tr>
                            <th width="30px">
                                <input type="checkbox" class="group-checkable" data-set="#product-requests-table .checkboxes"/>
                            </th>
                            <th>Req ID</th>
                            <th>Product Description</th>
                            <th>Category</th>
                            <th>UOM</th>
                            <th>Customer</th>
                            <th>Requester</th>
                            <th>Created</th>
                            <th>List</th>
                            <th>Order Qty</th>
                            <th>Recurrence Qty</th>
                            <th>Cataloguing Item Code</th>
                            <th>Purchase Recurrence</th>
                            <th>Sku</th>
                            <th>Current Price</th>
                            <th>Currency</th>
                            <th>Current Supplier</th>
                            <th>Supplier Contact</th>
                            <th>Reference 1</th>
                            <th>Reference 2</th>
                            <th>Reference 3</th>
                            <th>Reference 4</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Completed</th>
                            @if($currentUser->hasAccess('quotations.create'))
                                <th>Quotations</th>
                            @endif
                            <th>Options</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Req ID</th>
                            <th>Product Description</th>
                            <th>Category</th>
                            <th>UOM</th>
                            <th>Customer</th>
                            <th>Requester</th>
                            <th>Created</th>
                            <th>List</th>
                            <th>Order Qty</th>
                            <th>Recurrence Qty</th>
                            <th>Cataloguing Item Code</th>
                            <th>Purchase Recurrence</th>
                            <th>Sku</th>
                            <th>Current Price</th>
                            <th>Currency</th>
                            <th>Current Supplier</th>
                            <th>Supplier Contact</th>
                            <th>Reference 1</th>
                            <th>Reference 2</th>
                            <th>Reference 3</th>
                            <th>Reference 4</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Completed</th>
                            @if($currentUser->hasAccess('quotations.create'))
                                <th>Quotations</th>
                            @endif
                            <th></th>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>

<?php
    $dataSrcUrl = '/product-requests/get-product-requests';
    if (isset($filterBy) && isset($filterValue)) {
        $dataSrcUrl .= "?filter={$filterBy}&value={$filterValue}";
    }
?>

@stop

@section('bottomlinks')
    @parent
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/plugins/sorting/date-dd-MMM-yyyy.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.columnFilter.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.fnFilterClear.js') }}"></script>

<script class="init" type="text/javascript">

    $(document).ready(function() {

        var dataSrcUrl = "{{ $dataSrcUrl }}";
        var selectedRecords = [];
        var checkChanged = function(checkbox) {
            if(checkbox.is(":checked")) {
                selectedRecords.push(checkbox.val());
            }
            if(!checkbox.is(":checked")) {
                var searchValue = checkbox.val();
                selectedRecords = $(selectedRecords).not([searchValue]).get();
            }
            if(selectedRecords.length) {
                $('#selection-counter').html(selectedRecords.length + ' records selected.').show();
                $('#actions').attr('disabled', false);
                if ($("#actions option:selected").text() !== 'Close') {
                    $('#submit-action').fadeIn();
                }
            } else {
                $('#selection-counter').hide();
                $('#actions').prop('selectedIndex',0).attr('disabled', true);
                $('#submit-action').fadeOut();
                $('#reason_closed_div').fadeOut();
                $('#close_button').fadeOut();
            }
        }

        $('.checkboxes').change( function () {
            alert('Just Checked');
            checkChanged($(this));
        } );

        $('#action-form').submit( function(e) {
            var actions = $('#actions');
            var action = actions.val();
            if (action !== '') {
                $("#action-form").attr('action', '/' + action);
                $("#request_ids").val(selectedRecords);
                if (actions.find(':selected').data('transition') !== undefined) {
                    $("#transition").val(actions.find(':selected').data('transition'));
                }
                return true;
            }
            e.preventDefault();
        });

        $('#actions').change(function(e){
            var action = $("#actions option:selected").text();
            if (action === 'Close') {
                $('#submit-action').fadeOut();
                $('#reason_closed_input').attr('disabled', false);
                $('#reason_closed_div').fadeIn();
                $('#close_button').fadeIn();
            } else {
                $('#submit-action').show();
                $('#reason_closed_input').attr('disabled', true);
                $('#reason_closed_div').hide();
                $('#close_button').hide();
            }
        });

        var el = $('#product-requests-table');

        $.extend(true, $.fn.DataTable.TableTools.classes, {
            "container": "btn-group tabletools-btn-group pull-right",
            "buttons": {
                "normal": "btn btn-sm default",
                "disabled": "btn btn-sm default disabled"
            }
        });

        var columnData = [
            { "data": function(row, type, set, meta) {
                return '<input type="checkbox" id="box-' + row.request_id + '" class="checkboxes" name="box-' + row.request_id + '" value="' + row.request_id +'" style="text-align: center;" />' ;
            } },
            { "data": function(row, type, set, meta) {
                return '<a href="/product-requests/' + row.request_id + '">' +  row.request_id + '</a>';
            }},
            { "data": "product_description" },
            { "data": "category" },
            { "data": "uom" },
            { "data": "customer" },
            { "data": "requester" },
            { "data": "created_at" },
            { "data": "request_list_id" },
            { "data": "first_time_order_quantity" },
            { "data": "purchase_recurrence_quantity" },
            { "data": "cataloguing_item_code" },
            { "data": "purchase_recurrence" },
            { "data": "sku" },
            { "data": "current_price" },
            { "data": "current_price_currency" },
            { "data": "current_supplier" },
            { "data": "current_supplier_contact" },
            { "data": "reference1" },
            { "data": "reference2" },
            { "data": "reference3" },
            { "data": "reference4" },
            { "data": "remarks" },
            { "data": "status" },
            { "data": "completed_at" }
        ];

        var mColumnData= [
            1,2,3,4,5,6,7,8,9,10,
            11,12,13,14,15,16,17,18,19,20,
            21, 22, 23, 24
        ];
        var bSortableTargets = [ 0, 25 ];
        var isAllowedAccessToQuotations = "{{ $currentUser->hasAccess('quotations.create') }}";
        if (isAllowedAccessToQuotations) {
            columnData.push(
                { "data": "quotations" }
            );
            mColumnData.push(25);
            bSortableTargets.splice(-1,1);
            bSortableTargets.push(26);
        }
        columnData.push(
            { "data": function(row, type, set, meta) {
                    return '<a href="/product-requests/' + row.request_id + '/edit" class="btn primary-36s btn-xs">Edit</a>';
                }
            }
        );

        var setupTableFilter = function(json) {
            var requestLists = json.requestLists
            var customersList = json.customersList
            table.columnFilter({
                "sPlaceHolder" : "head:after",
                aoColumns: [
                    null,
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {type: "text"},
                    {
                        type: "select",
                        values: customersList
                    },
                    {type: "text"},
                    {type: "text"},
                    {
                        type: "select",
                        values: requestLists
                    },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        type: "select",
                        values: ['Draft','Pending Input','In Review','Sourcing','Pending Quotation','Pending Proposal','Pending Approval','Approved','Proposal Rejected', 'Ready to Catalogue', 'Ready to Deploy', 'Complete', 'Closed', 'Cancelled' ]
                    },
                    null,
                @if($currentUser->hasAccess('quotations.create'))
                    {type: "text"},
                @endif
                    null
                ]
            });
        }

        var table = el.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "Show _MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"] // change per page values here
            ],
            "sPaginationType": "bootstrap",
            "pagingType": "full_numbers",
            "stateSave": true,
            "deferRender": true,
            "order": [[7, 'desc']],

            "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
            "oTableTools": {
                "sSwfPath": "{{ asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "oSelectorOpts": { filter: "applied", order: "current" },
                        "mColumns": mColumnData
                    },
                    {
                        "sExtends": "csv",
                        "sFileName": "product-requests.csv",
                        "oSelectorOpts": { filter: "applied", order: "current" },
                        "mColumns": mColumnData
                    }
                ]
            },
            "aoColumnDefs": [
                { "bVisible": false, "aTargets": [ 10,11,12,13,14,15,16,17,18,19,20,21,22,24 ] },
                { "bSortable": false, "aTargets": bSortableTargets }
            ],
            "processing": true,
            "ajax":  {
                "url": dataSrcUrl,
                "dataSrc": function(json) {
                    setupTableFilter(json);
                    return json.data;
                }
            },
            "columns": columnData

        });

        var tableWrapper = $('#product-requests-table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

        $('#remove-filters').click(function(e){
            e.preventDefault();
            table.fnFilterClear();
            $('.text_filter').val('');
            $('.select_filter').val('');
        });

        $('.group-checkable').change(function () {
            var checked = jQuery(this).is(":checked");
            $('.checkboxes').each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
                checkChanged($(this));
            });
        });

        table.on('change', 'tbody tr .checkboxes', function (e) {
            checkChanged($(this));
            $(this).parents('tr').toggleClass("active");
        });

        var uncheckAll = function() {
            $('.group-checkable').prop("checked", false);
            $('.checkboxes').each(function () {
                $(this).prop("checked", false);
                $(this).parents('tr').removeClass("active");
                checkChanged($(this));
            });
        }();

        var fnShowHide = function ( iCol )
        {
            var bVis = table.fnSettings().aoColumns[iCol].bVisible;
            table.fnSetColumnVis( iCol, bVis ? false : true );
        }
        var toggleVisibility = function(button, column) {
            button.toggleClass("primary-36s");
            button.toggleClass("btn-default");
            fnShowHide(column);
        }

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();
            toggleVisibility($(this), $(this).attr('data-column'))
        } );

        var resetColumns = function() {
            var toggles = $('a.toggle-vis');
            toggles.each(function(){
                var col = $(this).attr('data-column');
                if(!table.fnSettings().aoColumns[col].bVisible) {
                    $(this).toggleClass("primary-36s");
                    $(this).toggleClass("btn-default");
                }
            });
            if (!table.api().state.loaded()) {
                var requesterToggle = $('#requester-toggle');
                var listToggle = $('#list-toggle');
                toggleVisibility( requesterToggle, requesterToggle.attr('data-column') );
                toggleVisibility( listToggle, listToggle.attr('data-column') );
            }
        }();

    });


</script>

@stop