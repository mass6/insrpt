@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('js/select2/select2-bootstrap.css') }}">
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
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Active Product Cataloguing Requests'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-docs"></i>
                    <span class="caption-subject bold uppercase">Active Requests</span>
                </div>
                <div class="actions">
                    @if($currentUser->hasAccess('cataloguing.products.add'))
                        <a href="{{ route('catalogue.product-definitions.create') }}" class="btn btn-circle blue ">
                            <i class="fa fa-plus"></i> New Request
                        </a>
                    @endif
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                @if(isset($product_companies))
                <div class="btn-group customer-selecter">
                    <button type="button" class="btn btn-primary">Companies</button>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-primary" role="menu">
                        @foreach($product_companies as $company)
                            <li>
                                <a href="{{ route('catalogue.product-definitions.index.company_id',$company->id) }}">
                                    <span>{{$company->name}}</span>
                                </a>
                            </li>
                        @endforeach
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('catalogue.product-definitions.index.company_id','all') }}">
                                <span>View All</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif
                <br/>
                <br/>

                @if (count($displaying_products))
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="filter_row">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Supplier</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Attributes</th>
                            <th style="width: 9%;">Updated</th>
                            <th width="90px">Options</th>
                        </tr>

                    </thead>
                    <tbody>
                    <?php $counter = 1; ?>
                    @foreach ($displaying_products as $product)
                    <tr>
                        <td>{{ $product->customer->name }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ isset($product->supplier_id) ? $product->supplier->name : '' }}</td>
                        <td>{{ isset($product->assigned_user_id) ? $product->assignedTo->name() : '' }}</td>
                        <td>{{ $product->statusName->name }}</td>
                        <td>
                            <div class="progress progress-striped">
                                <div class="progress-bar progress-bar-{{$product->attributeCompleteness()['label']}}" role="progressbar" aria-valuenow="{{ $product->attributeCompleteness()['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $product->attributeCompleteness()['percentage'] }}%">
                                    <span class="sr-only">20% Complete (success)</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->updated_at }}</td>

                        <td>
                            <div class="btn-group">
                                <a href="{{URL::route('catalogue.product-definitions.show', ['id' => $product->id])}}"><i class="entypo-right"></i>View</a> |
                                @if($product->assigned_user_id === $currentUser->id || $currentUser->hasAccess('cataloguing.products.admin'))
                                    <a href="{{URL::route('catalogue.product-definitions.edit', ['id' => $product->id])}}" readonly><i class="entypo-right"></i>Edit</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <?php $counter++; ?>
                    @endforeach
                    </tbody>
                </table>
                @endif


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
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.columnFilter.js') }}"></script>
    <script class="init" type="text/javascript">
        $(document).ready(function() {

            @if($statuses){
                var statusList = <?php echo json_encode($statuses); ?>;
            }@else{
                var statusList = null;
            }
            @endif

            @if($assignedList){
                var assignedList = <?php echo json_encode($assignedList) ?>;
            }@else{
                var assignedList = null;
            }
            @endif

            var el = $('#datatable');

            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });

            var table = el.dataTable({
                "sPaginationType": "bootstrap",
                "pagingType": "full_numbers",
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "order": [[0, 'desc']],
    //            "bSortCellsTop": true,
                "oTableTools": {
                    "sSwfPath": "{{ asset('js/datatables/copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "product-cataloguing-requests.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" }
                        }
                    ]
                },
                "aoColumnDefs": [
                    { "bSortable": false, "aTargets": [ 8 ] }
                ]
            });

            table.columnFilter({
                sPlaceHolder: "head:after",
                aoColumns:
                    [
                        null,
                        null,
                        null,
                        null,
                        { type: "select", values: assignedList},
                        { type: "select", values: statusList},
                        null,
                        null,
                        null,
                        null
                    ]

            });
        });


        function btnClicked(elname, id)
        {
            if ( document.getElementById(elname).value == "view" )
            {
                window.location="{{ url('/catalogue/product-definitions/') }}" + "/" + id
            }
            else if ( document.getElementById(elname).value == "edit" )
            {
                window.location="{{ url('/catalogue/product-definitions/') }}" + "/" + id + "/edit"
            }
            else if ( document.getElementById(elname).value == "assign-quotation" )
            {
                alert('This functionality is to be added...')
    //            window.location="{{ url('/quotations/create') }}" + "/" + id
            }
        }

    </script>

@stop