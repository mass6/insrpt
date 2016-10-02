@extends($layout)

@section('links')
    @include('common._editor-styles')
    @include('common._inline-style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Quotations'])
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
                            @if($search)
                                Quotation: {{$search}}
                                <button id="show-all" class="btn btn-primary">Show All</button>
                            @else
                                Quotations List
                            @endif
                        </span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">

                    <table id="datatable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th width="30px;"><input type="checkbox"></th>
                                <th>Quotation ID</th>
                                <th>Company</th>
                                <th>Quotation Request</th>
                                <th>Product Request ID</th>
                                <th>Supplier</th>
                                <th>Quotation Reference</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Supplier SKU</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Quoted Price</th>
                                <th>Currency</th>
                                <th>Quotation Date</th>
                                <th>Valid Until</th>
                                <th>Delivery Terms</th>
                                <th>Payment Terms</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Quotation ID</th>
                                <th>Company</th>
                                <th>Quotation Request</th>
                                <th>Product Request ID</th>
                                <th>Supplier</th>
                                <th>Quotation Reference</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Supplier SKU</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Quoted Price</th>
                                <th>Currency</th>
                                <th>Quotation Date</th>
                                <th>Valid Until</th>
                                <th>Delivery Terms</th>
                                <th>Payment Terms</th>
                                <th>Status</th>
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

            var companies = <?php echo $companyOptions; ?>;
            var suppliers = <?php echo $supplierOptions; ?>;
            var productRequests = <?php echo $productRequestOptions; ?>;
            var formFields = [];
            var editor; // use a global for the submit and return data rendering in the examples

            formFields.push({
                        label: "Company:",
                        name: "company_id",
                        type: "select",
                        options: companies
                    }, {
                        label: "Supplier:",
                        name: "supplier_id",
                        type: "select",
                        options: suppliers
                    }, {
                        label: "Product Request:",
                        name: "request_id",
                        type: "select",
                        options: productRequests
                    }, {
                        label: "Quotation Date:",
                        name: "quotation_date",
                        fieldInfo: "Date format: yyyy-mm-dd"
                    }, {
                        label: "Quotation Reference:",
                        name: "supplier_reference"
                    }, {
                        label: "Product Name",
                        name: "suppliers_product_name"
                    }, {
                        label: "Product Description",
                        name: "suppliers_product_description"
                    }, {
                        label: "Supplier SKU",
                        name: "suppliers_product_sku"
                    }, {
                        label: "UOM",
                        name: "suppliers_uom"
                    }, {
                        label: "Quantity",
                        name: "suppliers_quantity"
                    }, {
                        label: "Price",
                        name: "unit_price"
                    }, {
                        label: "Currency",
                        name: "price_currency"
                    }, {
                        label: "Total Price",
                        name: "total_price"
                    }, {
                        label: "Valid Until",
                        name: "valid_until",
                        fieldInfo: "Date format: yyyy-mm-dd"
                    }, {
                        label: "Delivery Terms",
                        name: "delivery_terms"
                    }, {
                        label: "Payment Terms",
                        name: "payment_terms"
                    }
            );

            editor = new $.fn.dataTable.Editor({
                ajax: {
                    create: {
                        type: 'POST',
                        url: '/quotations'
                    },
                    edit: {
                        type: 'PATCH',
                        url: '/quotations/_id'
                    },
                    remove: {
                        type: 'POST',
                        url: '/quotations/_id_'
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
                ajax: "/quotations",
                scrollY: '40vh',
                "scrollX": true,
                scrollCollapse: true,
                paging: false,
                columns: [
                    {
                        data: null,
                        defaultContent: '',
                        className: 'select-checkbox',
                        orderable: false
                    },
                    {data: "quotation_id"},
                    {data: "company.name"},
                    {
                        data: "quotation_request.quotation_request_id",
                        render: function (data, type, full, meta) {
                            if (data) {
                                return '<a href="/quotation-requests/' + data + '/edit">' + data + '</a>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: "product_request.request_id",
                        render: function (data, type, full, meta) {
                            if (data) {
                                return '<a href="/product-requests/' + data + '">' + data + '</a>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: "supplier.name",
                        render: function (data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return '';
                            }
                        }
                    },
                    {data: "supplier_reference"},
                    {data: "suppliers_product_name"},
                    {data: "suppliers_product_description"},
                    {data: "suppliers_product_sku"},
                    {data: "suppliers_uom"},
                    {data: "suppliers_quantity"},
                    {data: "unit_price"},
                    {data: "price_currency"},
                    {data: "quotation_date"},
                    {data: "valid_until"},
                    {data: "delivery_terms"},
                    {data: "payment_terms"},
                    {data: "state"}
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
                    {extend: "create", editor: editor},
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

            var reload = function () {
                table.ajax.reload();
            }

            // Activate an inline edit on click of a table cell
            table.on('click', 'tbody td:not(:first-child)', function (e) {
                editor.inline(this);
            });

            table.on('key-focus', function (e, datatable, cell) {
                editor.inline(cell.index(), {
                    onBlur: 'submit'
                });
            });

            var searchQuotation = function(quotation_id) {
                table.search(quotation_id).draw();
            };

            var searchQuotation = function(quotation_id) {
                table.search(quotation_id).draw();
            };

            var search = '<?php echo $search; ?>';
            if (search) {
                searchQuotation(search);
            }

            $('#show-all').click(function(e) {
                e.preventDefault();
                searchQuotation('');
                $('#header').html('Quotation List');
                $(this).fadeOut();
            });

        });

    </script>

@stop