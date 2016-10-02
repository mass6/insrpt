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
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker.standalone.min.css">
<!-- END PAGE LEVEL STYLES -->

<style>
    table.users, table.addresses {border:1px solid #dddddd;padding:5px;}
    .users td, .addresses td {border:none;}
    td.details-control1  {
        background: url('{{ URL::asset("js/datatables/resources/details_open.png") }}') no-repeat center center;
        cursor: pointer;
    }
    tr.shown1 td.details-control1 {
        background: url('{{ URL::asset("js/datatables/resources/details_close.png") }}') no-repeat center center;
    }
    tr.row-header {background-color: #DDDDDD !important;}
    td.column-header {width: 100% !important;color:#464646}
    .users tr {border-bottom: 1px solid #ddd;}
    td.col-label {color:#464646;}
    td.col-value {border-right:1px solid #DDDDDD;}
    td.col-label, td.col-value {padding: 5px;}
    table.addresses tr td.col-label {text-decoration: underline;}
    table.addresses td {font-size:12px;}
    #filter-form {margin-bottom: 10px}
    #filter-form .form-group {margin: 0 5px 5px 0}
</style>

@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Order Reports'])
@stop

@section('content')


    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-docs"></i>
                        <span class="caption-subject bold uppercase">{{ isset($heading) ? $heading : 'Orders: ' }}</span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <form method="get" class="form-inline" id="filter-form">
                        <fieldset>
                            <legend>Filters</legend>
                            @if(count($customers) > 1)
                            <div class="form-group">
                                <label for="customer_group">Customer</label>
                                <select name="customer_group" id="customer_group_id" class="form-control" onchange="loadContractGroups(this.value)">
                                    <option value="">All customers</option>
                                    @foreach($customers as $group)
                                        <option value="{{ $group['customer_group_id'] }}"@if($filters['customer_group'] == $group['customer_group_id']) selected="selected"@endif>{{ $group['customer_group_code'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="contract_group">Contract Group</label>
                                <select name="contract_group" id="contract_group" class="form-control"></select>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control">
                                    <option value="">All suppliers</option>
                                    <option value="0"@if($filters['supplier'] === '0') selected="selected"@endif>{{ siteOwner()->name }} supplied</option>
                                    <option value="1"@if($filters['supplier'] === '1') selected="selected"@endif>Third Party supplied</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <select name="date" id="date" class="form-control" onchange="$('.date-picker').val('').parent().toggle();">
                                    <option value="all">All dates</option>
                                    <option value="range"@if($filters['date'] == 'range') selected="selected"@endif>Filter by date</option>
                                </select>
                            </div>
                            <div class="form-group" @if($filters['date'] != 'range') style="display:none;"@endif>
                                <label for="from">From</label>
                                <input type="text" name="from" id="from" value="{{ $filters['from'] }}" class="form-control date-picker" placeholder="d-m-y"/>
                            </div>
                            <div class="form-group" @if($filters['date'] != 'range') style="display:none;"@endif>
                                <label for="to">To</label>
                                <input type="text" name="to" id="to" value="{{ $filters['to'] }}" class="form-control date-picker" placeholder="d-m-y"/>
                            </div>
                        </fieldset>
                        <input type="hidden" name="search" value="1"/>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <div class="col-md-12">
                        <span style="color:#505050;">Show/Hide:</span>
                        <a href="#" id="ordered-by-toggle" class="toggle-vis btn btn-xs btn-default" data-column="2">Ordered By</a>
                        <a href="#" id="approved-at-toggle" class="toggle-vis btn btn-xs btn-default" data-column="3">Approved At</a>
                        <a href="#" id="approved-by-toggle" class="toggle-vis btn btn-xs btn-default" data-column="4">Approved By</a>
                        <a href="#" id="delivery-days-toggle" class="toggle-vis btn btn-xs btn-default" data-column="7">Delivery Days</a>
                        <a href="#" id="ship-to-toggle" class="toggle-vis btn btn-xs btn-default" data-column="10">Ship To</a>
                        <a href="#" id="custom-field-1-toggle" class="toggle-vis btn btn-xs btn-default" data-column="11">Custom Field 1</a>
                        <a href="#" id="custom-field-2-toggle" class="toggle-vis btn btn-xs btn-default" data-column="12">Custom Field 2</a>
                    </div>
                    <br/>
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Weborder No.</th> {{-- 0 --}}
                            <th>Created</th> {{-- 1 --}}
                            <th>Ordered By</th> {{-- 2 --}}
                            <th>Approved Date</th> {{-- 3 --}}
                            <th>Approved By</th> {{-- 4 --}}
                            <th>Status</th> {{-- 5 --}}
                            <th>Actual Delivery Date</th> {{-- 6 --}}
                            <th>Delivery Days</th> {{-- 7 --}}
                            <th>Customer</th> {{-- 8 --}}
                            <th>Contract</th> {{-- 9 --}}
                            <th>Ship To</th> {{-- 10 --}}
                            <th>Custom Field 1</th> {{-- 11 --}}
                            <th>Custom Field 2</th> {{-- 12 --}}
                            <th>Total</th> {{-- 13 --}}
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align:right">Total:</th>
                            <th></th>
                            <th></th>
                            <th colspan="2"></th>
                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

    @stop

    @section('bottomlinks')
    @parent
            <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <script class="init" type="text/javascript">
        function loadContractGroups(customerGroupId) {
            var select = $('#contract_group');
            select.empty();
            select.append($('<option/>', {value: '', text: 'All Contract Groups'}));
            select.parent().hide();
            var selectedValue = '{{ $filters['contract_group'] }}'
            for (var customerGroup in Insight.customer_groups) {
                if (Insight.customer_groups[customerGroup].customer_group_id == customerGroupId) {
                    var options = Insight.customer_groups[customerGroup].contracts_groups;
                    for (var contractGroup in options) {
                        select.append($('<option/>', {value: options[contractGroup], text: contractGroup, selected: options[contractGroup] == selectedValue}));
                        select.parent().show();
                    }
                }
            }
        }

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<table class="addresses"  cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                    '<tr class="row-header">'+
                    '<td class="col-label">Ordered By</td>'+
                    '<td class="col-label">Ship To</td>'+
                    '<td class="col-label">Approved By</td>'+
                    '<td class="col-label">Approved At</td>'+
                    '<td class="col-label">Custom Field 1</td>'+
                    '<td class="col-label">Custom Field 2</td>'+
                    '<td class="col-label">Delivery Days</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td class="col-value">'+d.ordered_by+'</td>'+
                    '<td class="col-value">'+d.ship_to+'</td>'+
                    '<td class="col-value">'+d.approved_by+'</td>'+
                    '<td class="col-value">'+d.approved_at+'</td>'+
                    '<td class="col-value">'+d.custom_ref1+'</td>'+
                    '<td class="col-value">'+d.custom_ref2+'</td>'+
                    '<td class="col-value">'+d.delivery_days+'</td>'+
                    '</tr>'+
                    '</table>';
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $(document).ready(function() {

            var totalColumn = 13;

            var table = $('#datatable');

            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });

            var oTable = table.dataTable({
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
                    [20, 50, -1],
                    [20, 50, "All"] // change per page values here
                ],
                @if($performSearch)
                "ajax": {
                    "url" : "{{ action('Portal\OrdersController@getCustomOrderReport', $filters) }}",
                    "dataSrc": ""
                },
                @endif
                "deferRender": true,
                "aoColumns": [
                    { "data": function(row, type, set, meta) {
                        return '<a href="/portal/orders/details/' + row.entity_id + '">' +  row.increment_id + '</a>';
                    }},
                    { "data": "created_at" },
                    { "data": "ordered_by", "visible":false },
                    { "data": "approved_at", "visible":false },
                    { "data": "approved_by", "visible":false },
                    { "data": "status" },
                    { "data": "actual_delivery_date" },
                    { "data": "delivery_days", "visible": false },
                    { "data": "customer" },
                    { "data": "contract_display_name" },
                    { "data": "ship_to", "visible":false },
                    { "data": "custom_ref1", "visible":false },
                    { "data": "custom_ref2", "visible":false },
                    { "data": "grand_total" },
                    { "data": "week", "visible":false },
                    { "data": "month", "visible":false },
                    { "data": "quarter", "visible":false }
                ],
                "columnDefs": [ {
                    "targets": totalColumn,
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('color', '#4379C9');
                        $(td).css('font-weight', 'bold');
                        $(td).css('width', '7%');
                        $(td).css('text-align', 'right');
                    }
                },
//                {
//                    "targets": 10,
//                    "createdCell": function (td, cellData, rowData, row, col) {
//                        $(td).css('width', '10%');
//                    }
//                }
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                        i : 0;
                    };

                    // Total over all pages
                    data = api.column( totalColumn ).data();
                    total = data.length ?
                            data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } ) :
                            0;

                    // Total over this page
                    data = api.column( totalColumn, { page: 'current'} ).data();
                    pageTotal = data.length ?
                            data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            } ) :
                            0;

                    // Update footer
                    $( api.column( totalColumn ).footer() ).html(
                            'Showing AED '+ numberWithCommas(Math.round(pageTotal*100)/100) +
                            '<br/> of  AED '+ numberWithCommas(Math.round(total*100)/100) +' Total'
                    );
                },
                "order": [[1, 'desc']],
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13 ]
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "orders.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": [ 1,2,3,4,5,6,7,8,9,10,11,12,13 ]
                        }
                    ]
                }
            });

            var tableWrapper = $('#datatable_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

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


            // Add event listener for opening and closing details
//            $('#datatable tbody').on('click', 'td.details-control1', function () {
//                var tr = $(this).closest('tr');
//                var row = oTable.row( tr );
//
//                if ( row.child.isShown() ) {
//                    // This row is already open - close it
//                    row.child.hide();
//                    tr.removeClass('shown1');
//                }
//                else {
//                    // Open this row
//                    row.child( format(row.data()) ).show();
//                    tr.addClass('shown1');
//                }
//            } );

            loadContractGroups('{{ $filters['customer_group'] }}');
            $('.date-picker').datepicker({format: 'dd-mm-yyyy', todayHighlight: true});
        });
    </script>


@stop