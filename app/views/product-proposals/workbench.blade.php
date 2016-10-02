@extends($layout)

@section('links')
    @include('common._editor-styles')
    @include('common._inline-style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/font-icons/entypo/css/entypo.css') }}">
    <style>
        .proposal-legend {
            border: 1px solid #dcdcdc;
            height: 15px;
            padding: 5px;
            color: #505050;
            font-weight: bold;
        }
        /*table.dataTable thead .sorting {*/
            /*background: transparent url("../../media/images/sort_both.png") no-repeat scroll right center;*/
        /*}*/
        .legend-product-request, table.dataTable thead th.legend-product-request.sorting,
        table.dataTable thead th.legend-product-request.sorting_asc, table.dataTable thead th.legend-product-request.sorting_desc {
            background-color: #CCCCCC;
        }
        .legend-quotation, table.dataTable thead th.legend-quotation.sorting,
        table.dataTable thead th.legend-quotation.sorting_asc, table.dataTable thead th.legend-quotation.sorting_desc {
            background-color: #C6E4EE;
        }
        .legend-proposal, table.dataTable thead th.legend-proposal.sorting,
        table.dataTable thead th.legend-proposal.sorting_asc, table.dataTable thead th.legend-proposal.sorting_desc {
            background-color: #FFFFDB;
        }
        .legend-price-margin, table.dataTable thead th.legend-price-margin.sorting,
        table.dataTable thead th.legend-price-margin.sorting_asc, table.dataTable thead th.legend-price-margin.sorting_desc {
            background-color: #D8FFD3;
        }
    </style>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Proposals Workbench', 'layoutSelector' => true])
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-docs"></i>
                        <span class="caption-subject bold uppercase">Proposals Workbench</span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <h4>Legend</h4>
                    <span class="proposal-legend legend-product-request">from Product Request</span>
                    <span class="proposal-legend legend-quotation">from Quotation</span>
                    <span class="proposal-legend legend-proposal">from Proposal (editable)</span>
                    <span class="proposal-legend legend-price-margin">Margin (auto calculations)</span>
                    <br/>
                    <br/>
                    <table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" >
                        <thead>
                            <tr>
                                <th width="30px;"><input type="checkbox"></th>
                                <th>Product Request</th>
                                <th>Company</th>
                                <th>List</th>
                                <th>Proposal</th>
                                <th>Status</th>
                                <th class="legend-quotation">Quotation</th>
                                <th class="legend-quotation">Supplier</th>
                                <th class="legend-product-request">Original Product Description</th>
                                <th class="legend-product-request">Category</th>
                                <th class="legend-proposal">Proposed Name</th>
                                <th class="legend-proposal">Proposed Description</th>
                                <th class="legend-proposal">Proposed SKU</th>
                                <th class="legend-product-request">Current Price</th>
                                <th class="legend-quotation" style="min-width: 50px;">Buy Price</th>
                                <th class="legend-price-margin">Margin on Buy Price</th>
                                <th class="legend-proposal">Proposal Price</th>
                                <th class="legend-proposal">Price Currency</th>
                                <th class="legend-price-margin">Margin on Proposal</th>
                                <th class="legend-price-margin">Margin to Requester</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Product Request</th>
                                <th>Company</th>
                                <th>List</th>
                                <th>Proposal</th>
                                <th>Status</th>
                                <th class="legend-quotation">Quotation</th>
                                <th class="legend-quotation">Supplier</th>
                                <th class="legend-product-request">Original Product Description</th>
                                <th class="legend-product-request">Category</th>
                                <th class="legend-proposal">Proposed Name</th>
                                <th class="legend-proposal">Proposed Description</th>
                                <th class="legend-proposal">Proposed SKU</th>
                                <th class="legend-product-request">Current Price</th>
                                <th class="legend-quotation">Buy Price</th>
                                <th class="legend-price-margin">Margin on Buy Price</th>
                                <th class="legend-proposal">Proposal Price</th>
                                <th class="legend-proposal">Price Currency</th>
                                <th class="legend-price-margin">Margin on Proposal</th>
                                <th class="legend-price-margin">Margin to Requester</th>
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
    @include('common._editor-js')
    <script>

        $(document).ready(function () {

            var formFields = [];
            var editor; // use a global for the submit and return data rendering in the examples

            formFields.push({
                        label: "Proposed Name:",
                        name: "product_name"
                    }, {
                        label: "Proposed Description:",
                        name: "product_description"
                    }, {
                        label: "SKU",
                        name: "sku"
                    }, {
                        label: "Proposal Price:",
                        name: "price"
                    }, {
                        label: "Currency:",
                        name: "price_currency"
                    }
            );

            editor = new $.fn.dataTable.Editor({
                ajax: {
                    edit: {
                        type: 'PATCH',
                        url: '/product-proposals/_id'
                    },
                    remove: {
                        type: 'POST',
                        url: '/product-proposals/_id_'
                    }
                },
                table: '#datatable',
                idSrc: "id",
                fields: formFields

            });

            // Buttons array definition to create previous, save and next buttons in
            // an Editor form
            var backNext = [
                {
                    label: "&lt;",
                    fn: function (e) {
                        // On submit, find the currently selected row and select the previous one
                        this.submit(function () {
                            var indexes = table.rows({search: 'applied'}).indexes();
                            var currentIndex = table.row({selected: true}).index();
                            var currentPosition = indexes.indexOf(currentIndex);

                            if (currentPosition > 0) {
                                table.row(currentIndex).deselect();
                                table.row(indexes[currentPosition - 1]).select();
                            }

                            // Trigger editing through the button
                            table.button(1).trigger();
                        }, null, null, false);
                    }
                },
                'Save',
                {
                    label: "&gt;",
                    fn: function (e) {
                        // On submit, find the currently selected row and select the next one
                        this.submit(function () {
                            var indexes = table.rows({search: 'applied'}).indexes();
                            var currentIndex = table.row({selected: true}).index();
                            var currentPosition = indexes.indexOf(currentIndex);

                            if (currentPosition < indexes.length - 1) {
                                table.row(currentIndex).deselect();
                                table.row(indexes[currentPosition + 1]).select();
                            }

                            // Trigger editing through the button
                            table.button(1).trigger();
                        }, null, null, false);
                    }
                }
            ];

            var table = $('#datatable').DataTable({
                dom: 'Bfrtip',
                ajax: "/product-proposals/workbench",
                scrollY: '40vh',
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                columns: [
                    {
                        data: null,
                        defaultContent: '',
                        className: 'select-checkbox',
                        orderable: false
                    },
                    {data: "product_request.request_id"},
                    {data: "company.name"},
                    {
                        data: "product_request",
                        render: function (data, type, full, meta) {
                            if (data.list_id) {
                                return data.product_request_list.name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {data: "proposal_id"},
                    {data: "status"},
                    {
                        data: "quotation",
                        render: function (data, type, full, meta) {
                            if (data) {
                                return data.quotation_id;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: "quotation",
                        render: function (data, type, full, meta) {
                            if (data) {
                                if (data.supplier) {
                                    return data.supplier.name;
                                }
                            } else {
                                return '';
                            }
                        }
                    },
                    {data: "product_request.product_description"},
                    {data: "product_request.category"},
                    {data: "product_name"},
                    {data: "product_description"},
                    {data: "sku"},
                    {
                        data: "product_request",
                        render: function (data, type, full, meta) {
                            if (data.current_price) {
                                return data.current_price_currency + ' ' + data.current_price;
                            } else {
                                return '';
                            }
                        }
                    },
                    // Buy Price
                    {
                        data: "quotation",
                        render: function (data, type, full, meta) {
                            if (data && data.unit_price) {
                                return data.price_currency + ' ' + data.unit_price;
                            } else {
                                return '';
                            }
                        }
                    },
                    // Margin on Buy Price
                    {
                        data: null,
                        render: function ( data, type, row ) {
                            if (data.quotation && data.quotation.unit_price && data.product_request && data.product_request.current_price) {
                                var curr = data.product_request.current_price;
                                var buy = data.quotation.unit_price;
                                if (curr >= buy) {
                                    return '<i class="entypo-thumbs-up text text-success" >' + ((curr - buy) / curr * 100).toFixed(2) + '%</i>';
                                } else {
                                    return '<i class="entypo-thumbs-down text text-danger" >' + ((buy - curr) / curr * 100).toFixed(2) + '%</i>';
                                }
                            } else {
                                return '';
                            }
                        }
                    },
                    // Proposal Price
                    {data: "price"},
                    // Proposal Price Currency
                    {data: "price_currency"},
                    // Margin on Proposal
                    {
                        data: null,
                        render: function ( data, type, row ) {
                            if (data.quotation && data.quotation.unit_price && data.price) {
                                var sell = data.price;
                                var buy = data.quotation.unit_price;
                                if (sell >= buy) {

                                    return '<i class="entypo-thumbs-up text text-success" >'  + ((sell - buy) / buy * 100).toFixed(2) + '%</i>';
                                } else {
                                    return '<i class="entypo-thumbs-down text text-danger" >' + ((buy - sell) / buy * 100).toFixed(2) + '%</i>';
                                }
                            } else {
                                return '';
                            }
                        }
                    },
                    // Margin to Requester
                    {
                        data: null,
                        render: function ( data, type, row ) {
                            if (data.price && data.product_request && data.product_request.current_price) {
                                var curr = data.product_request.current_price;
                                var sell = data.price;
                                if (curr >= sell) {
                                    return '<i class="entypo-thumbs-up text text-success" >' + ((curr - sell) / curr * 100).toFixed(2) + '%</i>';
                                } else {
                                    return '<i class="entypo-thumbs-down text text-danger" >' + ((sell - curr) / curr * 100).toFixed(2) + '%</i>';
                                }
                            } else {
                                return '';
                            }
                        }
                    }

                ],
                order: [1, 'asc'],
                keys: {
                    columns: ':not(:first-child)',
                    keys: [9]
                },
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                buttons: [
                    {
                        extend: 'selected',
                        text: 'Edit',
                        action: function () {
                            var indexes = table.rows({selected: true}).indexes();

                            editor.edit(indexes, {
                                title: 'Edit',
                                buttons: indexes.length === 1 ?
                                        backNext :
                                        'Save'
                            });
                        }
                    },
                    {extend: "remove", editor: editor}
                ]
            });

            // Activate an inline edit on click of a table cell
            table.on('click', 'tbody td:not(:first-child)', function (e) {
                editor.inline(this);
            });



        });

    </script>


@stop