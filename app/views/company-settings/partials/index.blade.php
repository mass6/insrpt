@extends($layout)

@section('links')
    @parent
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.0.1/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/editor/css/editor.bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/editor/css/editor.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/editor/examples/resources/syntax/shCore.css') }}">
	<style type="text/css" class="init">

	</style>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.0.1/js/dataTables.select.min.js"></script>
	<script type="text/javascript" language="javascript" src="{{ URL::asset('js/editor/js/dataTables.editor.min.js') }}"></script>
	<script type="text/javascript" language="javascript" src="{{ URL::asset('js/editor/examples/resources/syntax/shCore.js') }}"></script>
	<script type="text/javascript" language="javascript" class="init">

        var editor; // use a global for the submit and return data rendering in the examples

        $(document).ready(function() {
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
                        label: "Primary Contact:",
                        name: "primary_contact"
                    }, {
                        label: "Email:",
                        name: "email"
                    }, {
                        label: "Phone:",
                        name: "telephone1"
                    }, {
                        label: "Fax:",
                        name: "fax"
                    }, {
                        label: "Website:",
                        name: "website"
                    }
                ]
            } );

            $('#suppliers-table').DataTable( {
                dom: 'Bfrtip',
                ajax: "/companies/" + "<?php echo $company->id; ?>/suppliers",
                columns: [
                    { data: "name" },
                    { data: "primary_contact" },
                    { data: "email" },
                    { data: "telephone1" },
                    { data: "fax" },
                    { data: "website" }
                ],
                select: true,
                buttons: [
                    { extend: "create", editor: editor },
                    { extend: "edit",   editor: editor },
                    { extend: "remove", editor: editor }
                ]
            } );
        } );

    </script>

@stop


@section('content')

    <h3>Supplier List</h3>
    <hr/>
    <br/>

    <table id="suppliers-table" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Primary Contact</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Fax</th>
                <th>Website</th>
            </tr>
        </thead>
    </table>


@stop
