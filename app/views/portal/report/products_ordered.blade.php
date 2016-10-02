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
    <style type="text/css">
        #filter-form {margin-bottom: 10px}
        #filter-form .form-group {margin: 0 5px 5px 0}
    </style>
    <!-- END PAGE LEVEL STYLES -->
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Portal Reports', 'subheading' => 'Products Ordered'])
@stop

@section('content')

    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-docs"></i>
                    <span class="caption-subject bold uppercase">Products Ordered</span>
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
                        <div class="form-group">
                            <label for="report_period">Period</label>
                            <select name="report_period" id="report_period" class="form-control">
                                <option value="day">Day</option>
                                <option value="month" selected="selected">Month</option>
                                <option value="year">Year</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="report_from">From</label>
                            <input type="text" name="report_from" id="report_from" value="{{ $filters['report_from'] }}" class="form-control datepicker required-entry" placeholder="d-m-y"/>
                        </div>
                        <div class="form-group">
                            <label for="report_to">To</label>
                            <input type="text" name="report_to" id="report_to" value="{{ $filters['report_to'] }}" class="form-control datepicker required-entry" placeholder="d-m-y"/>
                        </div>
                    </fieldset>
                    <fieldset>
                        @if (count($websites) > 1)
                            <div class="form-group">
                                <label for="website">Website</label>
                                <select name="website" id="website" class="form-control" onchange="loadContracts(this.value)">
                                    <option value="">All websites</option>
                                    @foreach ($websites as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group" id="contract-load">
                            <label for="contract">Contract</label>
                            <select name="contract" id="contract" class="form-control"></select>
                        </div>
                        @if(count($suppliers))
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control">
                                    <option value="">All suppliers</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier['name'] }}">{{ $supplier['sup_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <div id="datatable-load">
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>UOM</th>
                            <th>Price</th>
                            <th>Purchases</th>
                        </tr>
                        </thead>
                    </table>
                </div>
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

        function validateFilterData() {
            $('.has-error').removeClass('has-error');
            var result = true;
            $('.required-entry').each(function() {
                if ($(this).val() == '') {
                    $(this).parent().addClass('has-error');
                    result = false;
                }
            })
            return result;
        }
        function loadContracts(website) {
            var select = $('#contract');
            select.empty();
            select.append($('<option/>', {value: '', text: 'All Contracts/Sites'}));
            if (Insight.contracts[website]) {
                var options = Insight.contracts[website];
                $.each (options, function(index, item) {
                    select.append($('<option/>', {value: item.contract_id, text: item.contract_name}));
                });
            }
            select.select2({width: '250px'});
        }
        $(function() {
            $('#datatable').DataTable({
                "language": {
                    "emptyTable": "Please select the criteria and click Search."
                },
                "lengthMenu": [
                    [20, 50, -1],
                    [20, 50, "All"] // change per page values here
                ]
            });
            $('.datepicker').datepicker({format: 'dd-mm-yyyy', todayHighlight: true});
            $('#filter-form select').select2({width: '200px'});
            $('#filter-form').submit(function(e) {
                e.preventDefault();
                if (validateFilterData()) {
                    $(this).find('button').attr('disabled', 'disabled').text('Loading data...');
                    $.get('/portal/report/products-ordered', $(this).serialize(), function(response) {
                        $('#datatable-load').html(response);
                    })
                }
            })
            loadContracts(Insight.default_website);
            $('#filter-form').submit();
        });
    </script>
@stop