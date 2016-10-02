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
    <!-- END PAGE LEVEL STYLES -->
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Reports'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-docs"></i>
                    <span class="caption-subject bold uppercase">{{ isset($heading) ? $heading : 'Products: ' }}</span>
                    @if (isset($customers))
                        <div class="btn-group" style="margin-left: 10px;">
                            <button type="button" class="btn primary-36s">Customer</button>
                            <button type="button" class="btn primary-36s dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                @foreach($customers as $id => $customer)
                                    <li>
                                        <a href="{{ route('portal.products',$id) }}">
                                            <span>{{$customer}}</span>
                                        </a>
                                    </li>
                                @endforeach
                                <li class="divider">
                                </li>
                                <li>{{link_to_route('portal.products','View Main Catalogue')}}</li>
                                    <li>
                                        <a href="{{ route('portal.products', 'all') }}">
                                            All Customer Products <span class="label label-danger">Slow Load Time! </span>
                                        </a>
                                    </li>
                            </ul>
                        </div>
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
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>SKU</th>
                        <th>Partner Code</th>
                        <th>Name</th>
                        <th>UOM</th>
                        <th>Product Link</th>
                        <th>Supplier</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Customer</th>
                        <th>SKU</th>
                        <th>Partner Code</th>
                        <th>Name</th>
                        <th>UOM</th>
                        <th>Product Link</th>
                        <th>Supplier</th>
                        <th>Price</th>
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
        var customer_id = "<?php echo $customer_id; ?>";
        var portalUrl = '<?php echo getenv("PORTAL_URL"); ?>';
        $(document).ready(function() {


            $('#datatable tfoot th').each( function () {
                var title = $('#example thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            } );

            var canExportProducts = "<?php echo $exportable; ?>";

            // DataTables TableTools buttons options
            var aButtonsData = [
                {
                    "sExtends": "copy",
                    "oSelectorOpts": { filter: "applied", order: "current" },
                    "mColumns": [0,1,2,3,4,6,7]
                },
                {
                    "sExtends": "print",
                    "oSelectorOpts": { filter: "applied", order: "current" },
                    "mColumns": [0,1,2,3,4,6,7]
                }
            ];
            // If user can export products
            if(canExportProducts){
                aButtonsData.push({
                    "sExtends": "csv",
                    "sFileName": "products.csv",
                    "oSelectorOpts": { filter: "applied", order: "current" },
                    "mColumns": "all"
                });
            }

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
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                "ajax": {
                    "url" : "/portal/products-filter/" + customer_id,
                    "dataSrc": ""
                },
                "deferRender": true,
                stateSave: false,
                "columns": [
                    { "data": function(row, type, set, meta) {
                        var str = row.website.replace('_', ' ');
                        return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                                function($1){
                                    return $1.toUpperCase();
                                });
                    }},
                    { "data": "sku" },
                    { "data": "bp_product_code" },
                    { "data": "name" },
                    { "data": "uom" },
                    { "data": function(row, type, set, meta) {
                        return '<a href="' + portalUrl + '/' + row.url_key + '.html" target="_blank"><img src="' + row.thumbnail + '" style="height:50px;">';
                    }},
                    { "data": "supplier" },
                    { "data": "price" },
                ],
                "order": [[0, 'asc']],
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "{{ URL::asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": aButtonsData
                }
            });

            var tableWrapper = $('#datatable_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown


            // Apply the filter
            oTable.columns().eq( 0 ).each( function ( colIdx ) {
                $( 'input', oTable.column( colIdx ).footer() ).on( 'keyup change', function () {
                    oTable
                        .column( colIdx )
                        .search( this.value )
                        .draw();
                } );
            } );

        });
    </script>

@stop

