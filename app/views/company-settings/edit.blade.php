@extends($layout)

@section('links')
    @include('common._editor-styles')
    @include('common._inline-style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <link href="{{ asset('metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css">
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Company Settings'])
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings"></i>
                        <span class="caption-subject bold uppercase">Settings</span>
                    </div>
                    <div class="actions">
                        <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">

                    <div class="tabbable-custom">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a aria-expanded="true" href="#tab_15_1" data-toggle="tab">
                                Suppliers </a>
                            </li>
                            <li class="">
                                <a aria-expanded="false" href="#tab_15_2" data-toggle="tab">
                                Product Request Settings </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_15_1">
                                @include('company-settings.partials._suppliers')
                            </div>
                            <div class="tab-pane" id="tab_15_2">
                                @include('company-settings.partials._product-request-settings')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>



@stop

@section('bottomlinks')
    @parent
    @include('common._editor-js')
    <script src="{{ asset('metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ URL::asset('js/neon-demo.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/fuelux/js/spinner.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">

        var editor; // use a global for the submit and return data rendering in the examples

        $(document).ready(function() {

            var company = <?php echo $company->id; ?>;
            var siteOwner = <?php echo siteOwnerId(); ?>;
            var buttons = [];


            $('#hour-spinner').spinner({value:1, min: 1, max: 168});

            var x = 0;
            $('#suppliers-table tfoot th').each( function () {
                if (x > 0) {
                    var title = $('#suppliers-table thead th').eq( $(this).index() ).text();
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                }
                x++;
            } );


            editor = new $.fn.dataTable.Editor( {
                ajax: {
                    create: {
                        type: 'POST',
                        url:  '/companies/' + "<?php echo $company->id; ?>" + '/suppliers'
                    },
                    edit: {
                        type: 'PATCH',
                        url:  '/companies/' + "<?php echo $company->id; ?>" + '/suppliers/_id_'
                    },
                    remove: {
                        type: 'DELETE',
                        url:  '/companies/' + "<?php echo $company->id; ?>" + '/suppliers/_id_'
                    }
                },
                table: '#suppliers-table',
                idSrc: "id",
                fields: [ {
                        label: "Name:",
                        name: "name"
                    }, {
                        label: "Email:",
                        name: "email"
                    }, {
                        label: "Address:",
                        name: "address",
                        type: "textarea"
                    }, {
                        label: "Primary Contact:",
                        name: "primary_contact"
                    }, {
                        label: "Primary Phone:",
                        name: "telephone1"
                    }, {
                        label: "Phone 2:",
                        name: "telephone2"
                    }, {
                        label: "Fax:",
                        name: "fax"
                    }, {
                        label: "Website:",
                        name: "website"
                    }, {
                        label: "Description:",
                        name: "description",
                        type: "textarea"
                    }
                ]
            } );

            if (company === siteOwner) {
                buttons =  [
                    { extend: "create", editor: editor },
                    { extend: "edit",   editor: editor },
                    { extend: "remove", editor: editor }
                ];

            }

            var table = $('#suppliers-table').DataTable( {
                dom: 'Bfrtip',
                ajax: "/companies/" + "<?php echo $company->id; ?>/suppliers",
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
                    { data: "name" },
                    { data: "primary_contact" },
                    { data: "email" },
                    { data: "telephone1" },
                    { data: "fax" },
                    { data: "website" }
                ],
                order: [ 1, 'asc' ],
                keys: {
                    columns: ':not(:first-child)',
                    keys: [ 9 ]
                },
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                },
                buttons: buttons

            } );


            // Apply the search
            table.columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

            // Activate an inline edit on click of a table cell
            table.on( 'click', 'tbody td:not(:first-child)', function (e) {
                editor.inline( this );
            } );

            table.on( 'key-focus', function ( e, datatable, cell ) {
                editor.inline( cell.index(), {
                    onBlur: 'submit'
                } );
            } );



        } );

    </script>
@stop