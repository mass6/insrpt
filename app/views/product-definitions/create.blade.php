@extends($layout)

@section('links')
    {{--<link rel="stylesheet" href="{{ URL::asset('css/neon-forms.css') }}">--}}
    @parent
    <link rel="stylesheet" href="{{ asset('css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}"/>
    {{--<link rel="stylesheet" href="{{ URL::asset('js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">--}}
    {{--<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('css/neon-core.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('css/neon-theme.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('css/skins/blue.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">--}}
    {{--<link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">--}}

    {{--<link rel="stylesheet" href="{{URL::asset('js/selectboxit/jquery.selectBoxIt.css')}}">--}}
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Product Cataloguing Request'])
@stop

@section('content')

    @include('layouts.default.partials.errors')



{{--<div id="home-page" style="background-color:#ffffff;padding: 15px;">--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col-sm-6">--}}
                {{--<h2>New Product</h2>--}}
                {{--<br />--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="row col-md-10">--}}
                {{--@include('layouts.default.partials.errors')--}}
                {{--<hr />--}}

{{--                {{ Form::open(['route' => 'catalogue.product-definitions.store', 'class' => 'form-horizontal form-groups-bordered', 'files' => true]) }}--}}
            @if (!$company_id && $currentUser->hasAccess('cataloguing.products.admin'))
                <div class="well well-sm">
                    <h4>Select the customer.</h4>
                </div>
                {{ Form::open(['route' => 'catalogue.product-definitions.create', 'method' => 'get']) }}

                    <div class="col-md-6">

                        <label class="control-label" for="supplier_id"><strong>Action</strong></label>
                        <div class="input-group">
                            {{ Form::select('company_id', $companies, null, ['class'=>'form-control', 'id'=>'company_id', 'data-validate' => 'required']) }}

                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Go!</button>
                            </span>

                            <div class="" style="margin-left:20px;">
                                {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger pull-left')) }}
                            </div>

                        </div>

                    </div>

            @else

                @include('product-definitions.partials._form-wizard-new')

{{--                @if(!$request_type && (in_array('Customers',$groups) || in_array('Catalog Customers',$groups)))--}}
                    {{--<div class="well well-sm">--}}
                        {{--<h4>Select Request Type.</h4>--}}
                    {{--</div>--}}
{{--                    {{ Form::open(['route' => 'catalogue.product-definitions.create', 'method' => 'get']) }}--}}

                    {{--<div class="col-md-6">--}}


                        {{--<label class="control-label" for="supplier_id"><strong>Action</strong></label>--}}
                        {{--<div class="input-group">--}}
                            {{--<select id="request_type" name="request_type" class="form-control">--}}
                                {{--<option value="sourcing">Sourcing Request for Insight Team</option>--}}
                                {{--@if($currentUser->hasAccess('product-requests.3rd-party'))--}}
                                    {{--<option value="add">Add Product from your 3rd Party Supplier</option>--}}
                                {{--@endif--}}
                            {{--</select>--}}

                            {{--<span class="input-group-btn">--}}
                                {{--<button class="btn btn-primary" type="submit">Go!</button>--}}
                            {{--</span>--}}

                            {{--<div class="" style="margin-left:20px;">--}}
                                {{--{{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger pull-left')) }}--}}
                            {{--</div>--}}

                        {{--</div>--}}

                    {{--</div>--}}
                {{--@elseif((in_array('Customers',$groups) || in_array('Catalog Customers',$groups)) && $request_type == "sourcing" )--}}
{{--                    {{ Form::open(['route' => 'catalogue.product-definitions.add_resourcing_request', 'class' => 'form-wizard validate', 'files' => true]) }}--}}
                    {{--@include('product-definitions.partials._form-sourcing-request')--}}
                {{--@else--}}
                    {{--<div class="well well-sm">--}}
                        {{--<h4>Please fill the product details to create a new product cataloguing request.</h4>--}}
                    {{--</div>--}}
{{--                    {{ Form::open(['route' => 'catalogue.product-definitions.store', 'id' => 'rootwizard-2', 'name' => 'rootwizard-2', 'class' => 'form-wizard validate', 'files' => true]) }}--}}

                    <?php //$submit = 'Submit'; ?>

                    {{--@include('product-definitions.partials._form-wizard-new')--}}
                {{--@endif--}}

            {{--@endif--}}
{{--                {{ Form::close() }}--}}
        {{--</div>--}}













        @endif

    {{--</div>--}}
{{--</div>--}}

@stop

@section('bottomlinks')
    @parent

    <script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::asset('js/ckeditor/adapters/jquery.js') }}"></script>
    {{--<script src="{{ URL::asset('js/fileinput.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/joinable.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/resizeable.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/jquery.bootstrap.wizard.min.js') }}"></script>--}}
    <script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
    {{--<script src="{{ URL::asset('js/jquery.inputmask.bundle.min.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/jselectboxit/jquery.selectBoxIt.min.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/bootstrap-switch.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/bootstrap-switch.min.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('js/jquery.multi-select.js') }}"></script>--}}

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ URL::asset('metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metronic/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"></script>
    <script src="{{ URL::asset('metronic/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ URL::asset('metronic/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{ asset('metronic/assets/global/plugins/select2/select2.min.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('js/pages/product-definitions.js') }}"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
        jQuery(document).ready(function() {
           // initiate layout and plugins
           FormWizard.init();
        });
    </script>

        <script type="text/javascript">

    (function() {

    //    $("#actions").change(function() {
    //        var $action = $(this).val();
    //        alert($action);
    //
    //
    //    });


    //    $('#save').click(function(){
    //        $("#action").val('save');
    //        $("#status").val(1);
    //    });

        $('.save.btn.btn-primary').click(function(){
            $("#actions").val('save');
            //$("#status").val(1);
        })

    //    $('#assign-to-supplier').click(function(){
    //        $("#action").val('assign-to-supplier');
    //        $("#status").val(1);
    //        $("#rootwizard-2" ).submit();
    //    });
    //
    //    $('#assign-to-customer').click(function(){
    //        $("#action").val('assign-to-customer');
    //        $("#status").val(1);
    //        $("#rootwizard-2" ).submit();
    //    });
    //
    //    $('#submit').click(function(){
    //        $("#action").val('submit');
    //        $("#status").val(2);
    //    });
    //
    //    $('#process').click(function(){
    //        $("#action").val('process');
    //        $("#status").val(3);
    //    });


        var attributeSerial = 1; // serial to append to new element id
        var attributeCounter = 0; // current count/index of image
        var attributeLimit = 10;

        $('#add-attribute').click(function(){
            if ((attributeCounter) == attributeLimit)  {
                 alert("You have reached the limit of adding " + attributeLimit + " attributes");
            }
            else {
                 // add attribute name input
                 var newdiv = document.createElement('div');
                 var attnameid = 'attribute-name' + attributeSerial;
                 var attvalueid = 'attribute-value' + attributeSerial;
                 var inner = "<div class='col-md-1'>"
                    + "<label class='control-label'>&nbsp;</label>"
                     + "<p class='text-right'><span class='label label-info'>" + attributeSerial + "</span></p>"
                     + "</div>"
                     + "<div class='col-md-3'>"
                     + "<div class='form-group'>"
                     + "<label class='control-label' for='" + attnameid + "'>Name</label>";
                     if(attributeSerial === 1){
                        inner += "<input id='" + attnameid + "' name='" + attnameid + "' class='form-control' placeholder='e.g. Color, Size, Material' />"
                     } else {
                        inner += "<input id='" + attnameid + "' name='" + attnameid + "' class='form-control' />"
                     }
                 inner += "</div></div>"
                     + "<div class='col-md-6'>"
                     + "<div class='form-group'>"
                     + "<label class='control-label' for='" + attvalueid + "'>Value</label>";

                     if(attributeSerial === 1){
                         inner += "<input id='" + attvalueid + "' name='" + attvalueid + "' class='form-control' placeholder='e.g. White, Large, Steel' />"
                      } else {
                         inner += "<input id='" + attvalueid + "' name='" + attvalueid + "' class='form-control' />"
                      }
                 inner += "</div></div>";

                 newdiv.innerHTML = inner;
                 document.getElementById('new-attributes').appendChild(newdiv);
                 newdiv.className = 'row';
                 var bttn = document.getElementById('attribute-helper');
                 bttn.style.display = 'inline';

                 // increment counters
                 attributeSerial++;
                 attributeCounter++;
                 console.log(attributeSerial);
                 console.log(attributeCounter);
            }
        });

        $(document).ready(function() {




    // Had to disable below validation function because it was breaking the overall validation process
    // Need to look into how to add CKeditor validation using JQuery-validate.js


    //        $("#rootwizard-2").validate(
    //        {
    //          ignore: [],
    //          debug: false,
    //            rules: {
    //
    //                description:{
    //                     required: function()
    //                    {
    //                     CKEDITOR.instances.description.updateElement();
    //                    },
    //
    //                     minlength:10
    //                }
    //            },
    //            messages:
    //                {
    //
    //                description:{
    //                    required:"Please enter Text",
    //                    minlength:"Please enter 10 characters"
    //
    //
    //                }
    //            }
    //        });


            var cust = $("#company_id").val();
            console.log(cust);
            if(typeof cust !== "undefined" && cust !== ''){
    //            updateSupplierList();
            }



            // Populate Assigned User select based on Supplier selection
            $("#supplier_id").change(function() {
                var $userSelect = $("#assigned_user_id");
                $userSelect.empty();
                var supplier = $("#supplier_id").val();
                if(supplier )
                $.getJSON("../cataloguing/supplier-users/" + $("#company_id").val() + '/' + $("#supplier_id").val(), function(data) {
                    for (var company in data) {
                      if (data.hasOwnProperty(company)) {
                        $userSelect.append('<optgroup label="' + company + '">');

                        for (var key in data[company]) {
                          if (data[company].hasOwnProperty(key)) {
                            $userSelect.append('<option value="' + key +'">' + data[company][key] + '</option>');
                          }
                        }
                      }
                    }
                });

            });

            // Populate Assigned User and Supplier select based on customer selection
            $("#company_id").change(function() {
    //            var $userSelect = $("#assigned_user_id");
    //            $userSelect.empty();
    //            $.getJSON("../cataloguing/customer-users/" + $("#company_id").val(), function(data) {
    //                for (var company in data) {
    //                  if (data.hasOwnProperty(company)) {
    //                    $userSelect.append('<optgroup label="' + company + '">');
    //
    //                    for (var key in data[company]) {
    //                      if (data[company].hasOwnProperty(key)) {
    //                        $userSelect.append('<option value="' + key +'">' + data[company][key] + '</option>');
    //                      }
    //                    }
    //                  }
    //                }
    //            });

                updateSupplierList();

    //            var $supplierSelect = $("#supplier_id");
    //            $supplierSelect.empty();
    //            $.getJSON("../cataloguing/suppliers/" + $("#company_id").val(), function(data) {
    //                $supplierSelect.append('<option value="">[Select]</option>');
    //                $.each(data, function(index, value) {
    //                    $supplierSelect.append('<option value="' + index +'">' + value + '</option>');
    //                });
    //            });
            });

            $("#clear-images").click(function(event){
              event.preventDefault();
              $("#images").replaceWith("<input type='file' id='images' name='images[]' multiple />");
            });

            $("#uom").change(function() {
                if ( $( "#attribute-value-packaging" ).length ) {
                    $("#attribute-value-packaging").val($(this).val());
                }
            });
        });

        function updateSupplierList()
        {
            console.log('update supplier');
            var $supplierSelect = $("#supplier_id");
                $supplierSelect.empty();
            $.getJSON("../cataloguing/suppliers/" + $("#company_id").val(), function(data) {
                $supplierSelect.append('<option value="">[Select]</option>');
                $.each(data, function(index, value) {
                    $supplierSelect.append('<option value="' + index +'">' + value + '</option>');
                });
            });
        }
    })();
    </script>

    <script type="text/javascript">

        Element.prototype.remove = function() {
            this.parentElement.removeChild(this);
        }
        NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
            for(var i = 0, len = this.length; i < len; i++) {
                if(this[i] && this[i].parentElement) {
                    this[i].parentElement.removeChild(this[i]);
                }
            }
        }

        $("a.detach-image").click(function(e){
              e.preventDefault();
              var confirmdelete = confirm("Are you sure to want to delete this image?");
              if (confirmdelete ==true) {
                  var $id = $(this).attr('imageid');
                  document.getElementById('image'+$id).style.display = 'none';
                  document.getElementById('image'+$id).remove();
                  $.getJSON("/images/" + $id + "/delete",function(result){});
              }
        });

        $("a.detach-attachment").click(function(e){
              e.preventDefault();
              var confirmdelete = confirm("Are you sure to want to delete this file?");
              if (confirmdelete ==true) {
                  var $id = $(this).attr('attachmentid');
                  document.getElementById('attachment'+$id).style.display = 'none';
                  document.getElementById('attachment'+$id).remove();
                  $.getJSON("/attachments/" + $id + "/delete",function(result){});
              }
        });

        var imageSerial = 1; // serial to append to new element id
        var imageCounter = 0; // current count/index of image
        var imageLimit = 5; // max amount of images allowed
        var attachmentSerial = 1; // serial to append to new element id
        var attachmentCounter = 0; // current count/index of image
        var attachmentLimit = 5; // max amount of images allowed

        function addAttachmentInput(divName){
            if ((attachmentCounter + 1) == attachmentLimit)  {
                alert("You have reached the limit of adding " + attachmentLimit + " file attachments");
            }
            else {
                var newdiv = document.createElement('div');
                var divId = 'attachment-div' + attachmentSerial;
                var inner = "<div class='form-group'>"
                    + "<div class='col-md-6 col-md-offset-1'>"
                    + "<div class='fileinput fileinput-new' data-provides='fileinput'>"
                    + "<span class='btn btn-primary btn-file'>"
                    + "<span class='fileinput-new'>Select file</span><span class='fileinput-exists'>Change</span><input type='file' name='attachments[]'></span> "
                    + "<span class='fileinput-filename'></span>"
                    + "<a href='#' class='close fileinput-exists' data-dismiss='fileinput' style='float: none'>&times;</a>"
                    + "</div></div></div>";
                newdiv.innerHTML = inner;
                document.getElementById(divName).appendChild(newdiv);
                newdiv.className = 'row';
                newdiv.id = divId;
                attachmentSerial++;
                attachmentCounter++;
            }
        }

        function addImageInput(divName){
            if ((imageCounter + 1) == imageLimit)  {
                alert("You have reached the limit of adding " + imageLimit + " images");
            }
            else {
                var newdiv = document.createElement('div');
                var divId = 'image-div' + imageSerial;
                var inner = "<label class='col-sm-3 control-label'>&nbsp;</label>"
                    + "<div class='col-sm-5'>"
                    + "<div class='fileinput fileinput-new' data-provides='fileinput'>"
                    + "<div class='fileinput-new thumbnail' style='width: 150px; height: 150px;' data-trigger='fileinput'>"
                    + "<img src='http://placehold.it/150&text=Product+photo' alt='...'></div>"
                    + "<div class='fileinput-preview fileinput-exists thumbnail' style='max-width: 200px; max-height: 150px'></div>"
                    + "<div><span class='btn btn-primary btn-file'>"
                    + "<span class='fileinput-new'>Select image</span><span class='fileinput-exists'>Change</span><input type='file' name='images[]' accept='image/*'></span> "
                    + "<input type='button' class='btn btn-orange' value='Remove' onclick='deleteInput(\"" + divId + "\")' >"
                    + "</div></div></div>";
                newdiv.innerHTML = inner;
                document.getElementById(divName).appendChild(newdiv);
                newdiv.className = 'form-group';
                newdiv.id = divId;
                imageSerial++;
                imageCounter++;
            }
        }

        function deleteInput(id){
            var elem = document.getElementById(id);
            elem.parentNode.removeChild(elem);
            counter--;
        }


    </script>



@stop