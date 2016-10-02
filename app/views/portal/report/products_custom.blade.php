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
                        <span class="caption-subject bold uppercase">Custom Order Lines</span>
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
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Created Date</th>
                            <th>Ordered By</th>
                            <th>Approved Date</th>
                            <th>Contract</th>
                            <th>Contract ID</th>
                            <th>Contract Site</th>
                            <th>Contract Contractor</th>
                            <th>Ship To</th>
                            <th>Customer Note</th>
                            <th>Product SKU</th>
                            <th>Partner Code</th>
                            <th>Product Name</th>
                            <th>Chargeable</th>
                            <th>UOM</th>
                            <th>QTY</th>
                            <th>QTY Received</th>
                            <th>Price</th>
                            <th>Row Total</th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>Category Level 1</th>
                            <th>Category Level 2</th>
                            <th>Category Level 3</th>
                            <th>Category Level 4</th>
                            <th>Category Level 5</th>
                            <th>Category Tree</th>
                            <th>Attribute Set</th>
                            <th>Custom Reference 1</th>
                            <th>Custom Reference 2</th>
                            <th>Custom Reference 3</th>
                            <th>Custom Reference 4</th>
                            <th>Custom Reference 5</th>
                        </tr>
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

        $(document).ready(function() {

            var table = $('#datatable');

            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });

            var oTable = table.DataTable({
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
                    "url" : "{{ action('Portal\OrdersController@getCustomOrderLinesReport', $filters) }}",
                    "dataSrc": ""
                },
                @endif
                "deferRender": true,
                "aoColumns": [
                    { "data": function(row, type, set, meta) {
                        return '<a href="/portal/orders/details/' + row.entity_id + '">' +  row.increment_id + '</a>';
                    }},
                    { "data": "customer" },
                    { "data": "created_at", visible: false },
                    { "data": "ordered_by", visible: false },
                    { "data": "approved_at" },
                    { "data": "contract" },
                    { "data": "contract_id", visible: false },
                    { "data": "contract_site", visible: false },
                    { "data": "contract_contractor" },
                    { "data": "ship_to", visible: false },
                    { "data": "customer_note", visible: false },
                    { "data": "sku" },
                    { "data": "partner_code" },
                    { "data": "name" },
                    { "data": "chargeable", visible: false },
                    { "data": "uom", visible: false },
                    { "data": "qty" },
                    { "data": "qty_received", visible: false },
                    { "data": "price" },
                    { "data": "row_total" },
                    { "data": "supplier", visible: false },
                    { "data": "category" },
                    { "data": "category_level1" },
                    { "data": "category_level2" },
                    { "data": "category_level3" },
                    { "data": "category_level4" },
                    { "data": "category_level5" },
                    { "data": "all_categories", visible: false },
                    { "data": "attribute_set_name" },
                    { "data": "custom_ref1" },
                    { "data": "custom_ref2" },
                    { "data": "custom_ref3", visible: false },
                    { "data": "custom_ref4", visible: false },
                    { "data": "custom_ref5", visible: false }
                ],
                "order": [[4, 'desc']],
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "csv",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        }
                    ]
                }
            });

            loadContractGroups('{{ $filters['customer_group'] }}');
            $('.date-picker').datepicker({format: 'dd-mm-yyyy', todayHighlight: true});
        });
    </script>
@stop