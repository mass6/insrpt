@extends($layout)

@section('links')
    @include('common._editor-styles')
    @include('common._inline-style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/jquery-multi-select/css/multi-select.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/icheck/skins/all.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/jquery-nestable/jquery.nestable.css') }}"/>
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Company Administration', 'subheading' => 'Edit'])
@stop

@section('content')

    {{ Form::model($company, ['route' => ['admin.companies.update', $company->id], 'method' => 'PATCH', 'id' => 'company-form', 'class' => 'form-horizontal form-groups-bordered', 'data-company' => $company->id]) }}

        <?php $submit = 'Update'; ?>
        @include('admin.companies._form', ['settings' => $company->settings])

    {{ Form::close() }}

@stop

@section('bottomlinks')
    @parent
    @include('common._editor-js')
    <script src="{{ asset('metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/fuelux/js/spinner.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('metronic/assets/global/plugins/jquery-nestable/jquery.nestable.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">



        var editor; // use a global for the submit and return data rendering in the examples


        $(document).ready(function() {

            var nodeCount = $('#nestable_list_3 > ol').children().length;
            var customFieldCount = $("#nestable_list_3 li[data-src='user_defined']").length;
            var updatePositions = function (e) {
                var positions = $('.dd').nestable('serialize');
                for (var i = 0; i < positions.length; i++) {
                    $('#'+ positions[i].id + '_position_input').val(i);
                }
            };
            $('#nestable_list_3').nestable({maxDepth: 1}).on('change', updatePositions);

            $('#add-custom-field').click(function(e){
                e.preventDefault();
                customFieldCount++;
                var newListElement = "<li class='dd-item dd3-item' data-id='custom_field_" + customFieldCount + "'>"
                        + "<div class='dd-handle dd3-handle'></div>"
                            + "<div class='dd3-content'>"
                                + "<div class='row'>"
                                    + "<input class='form-control' id='custom_field_" + customFieldCount +"_type_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][type]' type='hidden' value='text'>"
                                    + "<input class='form-control' id='custom_field_" + customFieldCount +"_position_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][position]' type='hidden' value='" + nodeCount++ + "'>"
                                    + "<input class='form-control' id='custom_field_" + customFieldCount +"_src_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][src]' type='hidden' value='user_defined'>"
                                    + "<input class='form-control' id='custom_field_" + customFieldCount +"_default_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][default]' type='hidden' value='1'>"
                                    + "<input class='form-control' id='custom_field_" + customFieldCount +"_default_label_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][default_label]' type='hidden' value='Custom Field " + customFieldCount + "'>"
                                    + "<div class='col-md-2'>"
                                        + "<label for='custom_field_" + customFieldCount + "'>Custom Field " + customFieldCount + "</label>"
                                    + "</div>"
                                    + "<div class='col-md-2'>"
                                        + "<div class='checker' id='custom_field_" + customFieldCount +  "_enabled_input'><span class='checked'><input class='form-control' id='custom_field_" + customFieldCount + "_input' checked='checked' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][enabled]' type='checkbox' value='1'></span></div>"
                                    + "</div>"
                                    + "<div class='col-md-4'>"
                                        + "<input autofocus class='form-control' id='custom_field_" + customFieldCount + "_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][label]' type='text' value=''>"
                                    + "</div>"
                                    + "<div class='col-md-3'>"
                                        + "<input class='form-control' id='custom_field_" + customFieldCount + "_value_input' name='settings[order_report][field_definitions][custom_field_" + customFieldCount + "][value]' type='text' value=''>"
                                    + "</div>"
                                    + "<div class='col-md-1'>"
                                        + "<button class='delete-button'>&#10007;</button>"
                                    + "</div>"
                                + "</div>"
                        + "</div></li>";

                $("#nestable_list_3 > ol").append(newListElement);
            });

            $('.dd-list').on('click', 'button.delete-button', function(e) {
                e.preventDefault();
                $(this).closest('li').remove();
            });

            var makeReportField = function(key, config) {
                var cssClass = 'formatting' in config ? 'formatting-item' : 'non-formatting';
                var fieldHtml = "<li class='dd-item dd3-item " + cssClass + "' data-id='" + key + "' data-src='" + config.src + "'>"
                        + "<div class='dd-handle dd3-handle'></div>"
                        + "<div class='dd3-content'>"
                            + "<input class='form-control' id='" + key +"_type_input' name='settings[order_report][field_definitions][" + key + "][type]' type='hidden' value='" + config.type + "' />"
                            + "<input class='form-control' id='" + key +"_position_input' name='settings[order_report][field_definitions][" + key + "][position]' type='hidden' value='" + config.position + "' />"
                            + "<input class='form-control' id='" + key +"_src_input' name='settings[order_report][field_definitions][" + key + "][src]' type='hidden' value='" + config.src + "' />"
                            + "<input class='form-control' id='" + key +"_default_input' name='settings[order_report][field_definitions][" + key + "][default]' type='hidden' value='" + config.default + "' />"
                            + "<input class='form-control' id='" + key +"_default_label_input' name='settings[order_report][field_definitions][" + key + "][default_label]' type='hidden' value='" + config['default_label'] + "' />"
                            + "<div class='row'>"
                                + "<div class='col-md-2'>"
                                    + "<label for='" + key + "'>" + config['default_label'] + "</label>"
                                + "</div>"
                                + "<div class='col-md-2'>"
                                    + "<div class='checker'><span class='checked'><input class='form-control' checked='checked' name='settings[order_report][field_definitions][" + key + "][enabled]' type='checkbox' value='1' /></span></div><span>Enabled</span>"
                                + "</div>"
                                + "<div class='col-md-4'>"
                                    + "<input class='form-control' id='" + key + "_input' name='settings[order_report][field_definitions][" + key + "][label]' type='text' value='' />"
                                + "</div>"
                                + "<div class='col-md-3'>"
                                + "</div>"
                                + "<div class='col-md-1'>"
                                    + "<button class='delete-button'>&#10007;</button>"
                                + "</div>"
                            + "</div>";

                nodeCount++;

                if (config.type == 'number' || config.type == 'date') {
                    $.ajax({
                        url        : "/admin/companies/ajax/get-formatting-partial",
                        data       : {"key": key, "field": config},
                        type       : 'GET',
                        success   : function(result) {
                            fieldHtml = fieldHtml  + "<div class='row formatting'>" + result.partial + "</div></div></li>";
                            $("#nestable_list_3 > ol").append(fieldHtml);
                        }
                    });
                }
                else {
                    fieldHtml = fieldHtml + "</div></li>";
                    $("#nestable_list_3 > ol").append(fieldHtml);
                }
            };

            var reportFields = JSON.parse('<?php echo json_encode($reportFieldSelectionOptions["all"]) ?>');
            var selectedFields = JSON.parse('<?php echo json_encode($reportFieldSelectionOptions["selected"]) ?>');
            var src;
            var fieldConfig;
            var pos;
            $('#custom-headers').multiSelect({
                keepOrder: true,
                afterInit: function(container) {
                    var that = this;
                    var fieldKeys = [];
                    $.each(selectedFields, function(key, value) {
                        if ($.inArray(value.src, fieldKeys) === -1 && value.src != 'user_defined') {
                            fieldConfig = reportFields[value.src];
                            fieldKeys.push(value.src)
                            that.addOption({
                                value: fieldConfig['src'] + "_dup_" + ++nodeCount , text: fieldConfig['default_label'], index: fieldConfig['position']
                            });
                        }
                    });
                },
                afterSelect: function(value){
                    pos = value[0].search('_dup_');
                    if ( pos > 0) {
                        src = value[0].substr(0,pos);
                        fieldConfig = reportFields[src];
                    } else {
                        fieldConfig = reportFields[value[0]];
                    }
                    makeReportField(value[0], fieldConfig);
                    this.addOption({
                        value: fieldConfig['src'] + "_dup_" + nodeCount , text: fieldConfig['default_label'], index: fieldConfig['position']
                    });
                },
                afterDeselect: function(value){
                   $('#nestable_list_3 li[data-src="' + reportFields[value[0]]['src'] + '"').remove();

                },
                selectableHeader: "<div class='custom-header'>Click To Add Field</div>"
            });

            var references = $("#references-container");
            var enabled = $('#references_enabled_input').parent().parent().hasClass('bootstrap-switch-on');
            if (enabled == false) {
                references.hide();
            }
            $('input[name="settings[product-requests][references][enabled]"]').on('switchChange.bootstrapSwitch', function(event, state) {
              references.fadeToggle();
            });

            var order_report = $(".order_report_container");
            var enabled = $('#order_report_enabled_input').parent().parent().hasClass('bootstrap-switch-on');
            if (enabled == false) {
                order_report.hide();
            }
            $('input[name="settings[order_report][enabled]"]').on('switchChange.bootstrapSwitch', function(event, state) {
              order_report.fadeToggle();
            });


            $('#hour-spinner').spinner({value:1, min: 1, max: 168});

            $('#supplier-multiselect').multiSelect();

            var company = <?php echo $company->id; ?>;
            var siteOwner = <?php echo siteOwnerId(); ?>;
            var buttons = [];

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
                buttons:buttons
            } );

            globalTable = table;

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

            $('#suppliers-tab').click(function() {
                table.ajax.reload();
            });

            $('#copy-from-master-list').click( function(e) {
                e.preventDefault();
                var company = $('#company-form').data('company');
                $.post("/admin/companies/" + company + "/suppliers/copy-from-master-list",
                {
                    companyId: company,
                },
                function(data, status){
                    table.ajax.reload();
                    if (data.numberOfSuppliersAdded === 0) {
                        $("#alert-wrapper").append(
                        '<div class="alert alert-warning">' +
                            '<button type="button" class="close" data-dismiss="alert">' +
                            '&times;</button>' +
                            "No new suppliers added. All suppliers in the master list currently exist."
                        + '</div>');
                    } else {
                        $("#alert-wrapper").append(
                        '<div class="alert alert-success">' +
                            '<button type="button" class="close" data-dismiss="alert">' +
                            '&times;</button>' +
                            data.numberOfSuppliersAdded + " suppliers have been copied from the master supplier list."
                        + '</div>');
                    }

                });
            });

            $('.report-recipients').select2({
                placeholder: "Select Report Recipients",
                allowClear: true
            });

        } );


    </script>
@stop