@extends($layout)

@section('links')
    @parent
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
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Proposals', 'subheading' => isset($filter) ? $filter: 'All Proposals'])
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-docs"></i>
                    <span class="caption-subject bold uppercase">Proposals List{{ isset($filter) ? ' - ' . $filter: ''}}</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div id="actions-div" class="col-md-4">
                    <form action="/action" method="POST" id="action-form">
                        {{ Form::token() }}
                        <input type="hidden" id="proposal_ids" name="proposal_ids" value=""/>
                        <input type="hidden" id="transition" name="transition" value=""/>

                    <span class="text text-primary">Options</span>
                        <div class="input-group">
                            <select class="form-control" id="actions" style="">
                                <option value="">Select...</option>
                                @if($currentUser->hasAccess('product-proposals.approve'))
                                    <option value="product-proposals/apply-transition" data-transition="approve">Approve</option>
                                @endif
                                @if($currentUser->hasAccess('product-proposals.edit'))
                                    <option value="product-proposals/apply-transition" data-transition="submit_for_pricing">Recall</option>
                                @endif
                            </select>
                            <span  class="input-group-btn" >
                                <input type="submit" class="btn btn-primary" value="Submit" id="submit-action" style="margin-left: 10px;display: none;"  />
                            </span>
                            <span id="count-selected" style="margin-left: 15px;"></span>
                        </div>
                    </form>
                </div>
                <div class="clear"></div>
                <br/>

                <table id="proposals-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="replace-inputs">
                            <th></th>
                            <th></th>
                            @if(isSiteOwner($currentUser))
                                <th></th>
                            @endif
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
                            <th width="30px">
                                <input type="checkbox" class="group-checkable" data-set="#proposals-table .checkboxes"/>
                            </th>
                            <th style="width: 140px;">References</th>
                            @if(isSiteOwner($currentUser))
                                <th>Company</th>
                            @endif
                            <th>Product Name</th>
                            <th>Volume</th>
                            <th>Curr. Price</th>
                            <th>New Price</th>
                            <th>Price Match</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>Updated</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposals as $proposal)
                            <tr>
                                <td><input type="checkbox" class="checkboxes" name="box-{{$proposal->proposal_id}}" value="{{$proposal->proposal_id}}" style="text-align: center;" /> </td>
                                <td style="width: 100px;">
                                    Proposal: <a href="{{route('product-proposals.show',  [$proposal->productRequest->request_id, $proposal->proposal_id])}}">{{ $proposal->proposal_id }}</a><br/>
                                    Request: {{ $proposal->productRequest ? link_to_route('product-requests.show', $proposal->productRequest->request_id, $proposal->productRequest->request_id) : '' }}<br/>
                                    @if($proposal->productRequest->productRequestList)
                                       List: <a href="{{ route('product-requests.index', ['filter' => 'list_id', 'value' => $proposal->productRequest->productRequestList->list_id ]) }}">{{$proposal->productRequest->productRequestList->name}}</a>
                                    @endif
                                </td>
                                @if(isSiteOwner($currentUser))
                                    <td>{{ $proposal->company->name }}</td>
                                @endif
                                <td>{{ $proposal->product_name }}</td>
                                <td style="text-align: center;">{{ $proposal->volume }}</td>
                                <td>{{ $proposal->productRequest->current_price_currency }} {{ $proposal->productRequest->current_price }}</td>
                                <td>{{ $proposal->price_currency }} {{ $proposal->price }}</td>
                                <td>
                                    <?php $result = priceResult($proposal, $proposal->productRequest); ?>
                                    @if($result === 'Decrease')
                                        <i class="entypo-down" style="color: green;"></i><span class="text text-success">{{ pricePercentage($proposal, $proposal->productRequest) }}</span>
                                    @elseif($result === 'Increase')
                                        <i class="entypo-up" style="color: red;"></i><span class="text text-danger">{{ pricePercentage($proposal, $proposal->productRequest) }}</span>
                                    @else
                                        {{ $result }}
                                    @endif

                                </td>
                                <td>{{ $proposal->currentStateLabel() }}</td>
                                <td>{{ $proposal->assignedTo ? $proposal->assignedTo->name() : 'N/A' }}</td>
                                <td>{{ $proposal->updated_at->format('d-M-Y') }}</td>
                                <td>

                                    {{ Form::open(['route' => ['product-proposals.approvals', $proposal->id], 'method' => 'PATCH' ]) }}
                                    @if($currentUser->hasAccess('product-proposals.edit'))
                                        <a href="{{route('product-proposals.edit', [$proposal->productRequest->request_id,  $proposal->proposal_id]) }}" class="btn btn-xs primary-36s">Edit</a>
                                    @endif
                                    @if($proposal->getState() === 'APP' &&  $currentUser->hasAccess('product-proposals.approve') && $proposal->assigned_to_id === $currentUser->id)
                                        {{ Form::submit('Approve', ['name' => "transition[approve]", 'class' => 'btn btn-success btn-xs', 'id' => 'accept_button', 'Onclick'=>'return confirm("Accept this proposal?")']) }}
                                        {{ Form::submit('Reject', ['name' => "transition[reject]", 'class' => 'btn btn-danger btn-xs', 'id' => 'transition_reject']) }}
                                    @endif
                                    {{ Form::close() }}

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Proposal ID</th>
                            @if(isSiteOwner($currentUser))
                                <th>Company</th>
                            @endif
                            <th>Product Name</th>
                            <th>Volume</th>
                            <th>Curr. Price</th>
                            <th>New Price</th>
                            <th>Result</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>Updated</th>
                            <th></th>
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
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.columnFilter.js') }}"></script>
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('js/pages/product-proposals.js') }}"></script>

    <script class="init" type="text/javascript">

        $(document).ready(function() {

            var isSiteOwner;
            var exportColumns = [ 1,2,3,4,5,6,7,8,9];
            isSiteOwner = "<?php echo isSiteOwner($currentUser); ?>";
            if(isSiteOwner) {
                exportColumns.push(10);
            }
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
                    $('#submit-action').fadeIn();
                    $('#count-selected').show();
                    $('#count-selected').html('This many selected');
                } else {
                    $('#submit-action').fadeOut();
                    $('#count-selected').hide();
                }
            }

            $('.checkboxes').change( function () {
                checkChanged($(this));
            } );

            $('#action-form').submit( function(e) {
                var actions = $('#actions');
                var action = actions.val();
                if (action !== '') {
                    $("#action-form").attr('action', '/' + action);
                    $("#proposal_ids").val(selectedRecords);
                    if (actions.find(':selected').data('transition') !== undefined) {
                        $("#transition").val(actions.find(':selected').data('transition'));
                    }
                    return true;
                }
                e.preventDefault();
            });


            var el = $('#proposals-table');

            $.extend(true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group tabletools-btn-group pull-right",
                "buttons": {
                    "normal": "btn btn-sm default",
                    "disabled": "btn btn-sm default disabled"
                }
            });

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
                "order": [[1, 'desc']],
                "sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                "oTableTools": {
                    "sSwfPath": "js/datatables/copy_csv_xls_pdf.swf",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": exportColumns
                        },
                        {
                            "sExtends": "csv",
                            "sFileName": "product-proposals.csv",
                            "oSelectorOpts": { filter: "applied", order: "current" },
                            "mColumns": exportColumns
                        }
                    ]
                }
            });

            var tableWrapper = $('#proposals-table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

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

            table.on('change', 'tbody tr .checkboxes', function () {
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


            table.columnFilter({
                "sPlaceHolder" : "head:after",
                aoColumns: [
                    null,
                    {type: "text"},
                    @if(isSiteOwner($currentUser))
                        {type: "text"},
                    @endif
                    {type: "text"},
                    null,
                    null,
                    null,
                    null,
                    {
                        type: "select",
                        values: ['Draft','Pending Approval','Approved' ]
                    },
                    {type: "text"},
                    null,
                    null
                ]
            });

        });


    </script>

@stop