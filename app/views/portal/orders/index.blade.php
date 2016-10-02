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
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->

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
                    @if (isset($customers))
                        <div class="btn-group" style="margin-left: 10px;{{ count($customers) <= 1 ? 'display:none' : '' }}">
                            <button type="button" class="btn primary-36s">@if(isset($customer) && $customer != ''){{  $customer }}@else Customer @endif</button>
                            <button type="button" class="btn primary-36s dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                @foreach($customers as $code => $data)
                                    @if(isset($customer))
                                        @if($customer !== $code)
                                            <li>{{link_to('portal/orders/' . $period . '/' . $code, $code)}}</li>
                                        @endif
                                    @else
                                        <li>{{link_to('portal/orders/' . $period . '/' . $code, $code)}}</li>
                                    @endif
                                @endforeach
                                <li class="divider">
                                </li>
                                <li>{{link_to('portal/orders/' . $period, "View All")}}</li>
                            </ul>
                        </div>
                        <!-- contract groups -->
                        @if ($customer && isset($customers[$customer]) && count($customers[$customer]['contracts_groups']) > 0)
                            <div class="btn-group" style="margin-left: 10px;">Contract Groups</div>
                            <div class="btn-group">
                                <button type="button" class="btn primary-36s">{{ isset($contractGroup) ? $contractGroup : 'All' }}</button>
                                <button type="button" class="btn primary-36s dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach($customers[$customer]['contracts_groups'] as $contractGroupName => $contractGroupId)
                                        @if (isset($contractGroup))
                                            @if ($contractGroup != $contractGroupName)
                                                <li>{{link_to('portal/orders/' . $period . '/' . $customer . '/' . $contractGroupName, $contractGroupName)}}</li>
                                            @endif
                                        @else
                                            <li>{{link_to('portal/orders/' . $period . '/' . $customer . '/' . $contractGroupName, $contractGroupName)}}</li>
                                        @endif
                                    @endforeach
                                    <li class="divider">
                                    </li>
                                    <li>{{link_to('portal/orders/' . $period . '/' . $customer, "View All")}}</li>
                                </ul>
                            </div>
                        @endif
                        <br/>
                        <br/>
                    @endif
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

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
                            <th>Weborder No.</th>
                            <th>Created</th>
                            <th>Ordered By</th>
                            <th>Approved Date</th>
                            <th>Approved By</th>
                            <th>Status</th>
                            <th>Delivery Date</th>
                            <th>Delivery Days</th>
                            <th>Customer</th>
                            <th>Contract</th>
                            <th>Ship To</th>
                            <th>Custom Field 1</th>
                            <th>Custom Field 2</th>
                            <th>Total</th>
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
    <!-- END PAGE LEVEL PLUGINS -->

    <script class="init" type="text/javascript">
        <?php
            $reportUri = "$period%s%s"; // expected: reportName/customerGroup/contractGroup
            $selectedCustomer = ''; $selectedContractGroup = '';
            $selectedCustomer = isset($customer) ? '/' . (isset($customers[$customer]) ? $customers[$customer]['customer_group_id'] : '0') : $selectedCustomer;
            $selectedContractGroup = isset($contractGroup) ? '/' . (isset($customers[$customer]['contracts_groups'][$contractGroup]) ? $customers[$customer]['contracts_groups'][$contractGroup] : '0') : $selectedContractGroup;
            $reportUri = sprintf($reportUri, $selectedCustomer, $selectedContractGroup);
        ?>
        var reportname = "{{ $reportUri }}";

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
                    [20, 50, 100, 200, -1],
                    [20, 50, 100, 200, "All"] // change per page values here
                ],
                "ajax": {
                    "url" : "/portal/orders/"+reportname,
                    "dataSrc": ""
                },
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
                }
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
                            "mColumns": reportname.search('OrdersThirdPartyThisMonth') == -1 ? [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13 ] : [ 0,1,2,3,4,5,6,8,9,10,11,12,13 ]
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "orders.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": reportname.search('OrdersThirdPartyThisMonth') == -1 ? [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13 ] : [ 0,1,2,3,4,5,6,8,9,10,11,12,13 ]
                        }
                    ]
                }
            });

            var tableWrapper = $('#datatable_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity
            }); // initialize select2 dropdown

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

        });
    </script>


@stop