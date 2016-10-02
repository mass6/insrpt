<!-- Created by UserId -->
{{Form::hidden('user_id', $user->id)}}
<!-- Currency hard-coded to AED -->
{{Form::hidden('currency', 'AED')}}
<!-- Attributes: //Todo: add to form as input -->
{{Form::hidden('attributes', '') }}

{{ var_dump($errors) }}
<!-- Buttons -->
<div class="form-group">
    {{ Form::label('', '', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        <!-- Submit button -->
        {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'form-control btn btn-primary']) }}
        <!-- Cancel button -->
        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'form-control btn btn-warning')) }}
    </div>
</div>


{{-- Show company select box if usertype is internal --}}
@if($internalUser)
    <!-- Customer -->
    <div class="form-group">
        {{ Form::label('company_id', 'Customer:', ['class' =>'col-sm-3 control-label']) }}
        <div class="col-sm-5">
            {{ Form::select('company_id', $companies, null, ['class'=>'form-control', 'id'=>'company_id', 'required']) }}
        </div>
    </div>
@else
    <!-- Owned by CompanyID -->
    {{Form::hidden('company_id', $user->company->id, ['id' => 'company_id'])}}
@endif

<!-- Code  -->
<div class="form-group">
    {{ Form::label('code', 'Product Code:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('code', Input::old('code') ? Input::old('code') : null, ['class' => 'form-control step1', 'id' => 'code', 'required', 'placeholder' => 'Item Code, Product Code, or SKU']) }}
        {{ $errors->first('code', '<span class="label label-warning">:message</span>') }}
    </div>
</div>

<!-- Name -->
<div class="form-group">
    {{ Form::label('name', 'Name:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('name', Input::old('name') ? Input::old('name') : null, ['class' => 'form-control', 'required', 'placeholder' => 'Name as it shall be cataloged in the portal']) }}
    </div>
</div>

<!-- Category -->
<div class="form-group">
    {{ Form::label('category', 'Category:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('category', Input::old('category') ? Input::old('category') : null, ['class' => 'form-control', 'placeholder' => 'Category that this product should be classified in.']) }}
    </div>
</div>

<!-- Uom -->
<div class="form-group">
    {{ Form::label('uom', 'UOM:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('uom', Input::old('uom') ? Input::old('uom') : null, ['class' => 'form-control', 'id' => 'uom', 'placeholder' => 'e.g. Each, Pack, Carton']) }}
    </div>
</div>

<!-- Price -->
<div class="form-group">
    {{ Form::label('price', 'Price per UOM:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        <div class="input-group">
            <span class="input-group-addon">AED</span>
            {{ Form::text('price', Input::old('price') ? Input::old('price') : null, ['class' => 'form-control']) }}
            <span class="input-group-addon">.00</span>
        </div>
    </div>
</div>



{{-- Attributes --}}

{{--<input id="add-attribute" type="button" class="btn btn-success" value="+ add attribute" onClick="addNewAttributeInputs('new-attributes');">--}}
<div id="new-attributes">

</div>


<script type="text/javascript">
    var attributeSerial = 1; // serial to append to new element id
    var attributeCounter = 0; // current count/index of image
    var attributeLimit = 5; // max amount of images allowed

    function addNewAttributeInputs(divName){
        if ((attributeCounter) == attributeLimit)  {
            alert("You have reached the limit of adding " + attachmentLimit + " attributes");
        }
        else {
            // add attribute name input
            var newdiv = document.createElement('div');
            var attnameid = 'attribute-name' + attributeSerial;
            var attvalueid = 'attribute-value' + attributeSerial;
            var inner = "<div class='form-group'>"
                + "<label class='col-sm-3 control-label' for='" + attnameid + "'>Attribute " + attributeSerial + ": Name </label>"
                + "<div class='col-sm-5'>"
                + "<input id='" + attnameid + "' name='" + attnameid + "' class='form-control' placeholder='e.g. Color, Size, Material' />"
                + "</div></div>"
                + "<div class='form-group'>"
                + "<label class='col-sm-3 control-label' for='" + attvalueid + "'>Attribute " + attributeSerial + ": Value </label>"
                + "<div class='col-sm-5'>"
                + "<input id='" + attvalueid + "' name='" + attvalueid + "' class='form-control' placeholder='e.g. White, Large, Steel' />"
                + "</div></div><br/><hr/>";
            newdiv.innerHTML = inner;
            document.getElementById(divName).appendChild(newdiv);
            newdiv.className = 'form-group attribute-group';

            // increment counters
            attributeSerial++;
            attributeCounter++;
        }
    }
</script>



<!-- Description -->
<div class="form-group">
    <label for="description" class="col-sm-3 control-label">Description</label>
    <div class="col-sm-8">
        <textarea id="description" class="form-control" data-stylesheet-url="{{ URL::asset('css/wysihtml5-color.css') }} " name="description" id="description">{{{ Input::old('description') ? Input::old('description') : '' }}}</textarea>
    </div>
</div>

<!-- Short Description -->
<div class="form-group">
    <label for="short_description" class="col-sm-3 control-label">Short Description</label>
    <div class="col-sm-8">
        <textarea id="short_description" class="form-control" data-stylesheet-url="{{ URL::asset('css/wysihtml5-color.css') }} " name="short_description" id="short_description">{{{ Input::old('short_description') ? Input::old('short_description') : '' }}}</textarea>
    </div>
</div>

<!-- Images -->

<!-- Add new Images -->
<div id="image-div0" class="form-group">
    {{ Form::label('images', 'Product Images:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">

        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;" data-trigger="fileinput">
                <img src="http://placehold.it/150&text=Product+photo" alt="...">
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
            <div>
                <span class="btn btn-primary btn-file">
                    <span class="fileinput-new">Select image</span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="images[]" accept="image/*">
                </span>
                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
            </div>
        </div>

    </div>
</div>

<div id="dynamicImageInput">
</div>

<div class="form-group">
    <div class="col-sm-offset-2">
        <input id="add-image-input" type="button" class="btn btn-success" value="+ add image" onClick="addImageInput('dynamicImageInput');">
    </div>
</div>


<!-- Attachments -->
<!-- Add new Attachments -->
<div class="form-group">
    <label class="col-sm-3 control-label">Product Attachments</label>
    <div class="col-sm-5">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn btn-primary btn-file">
                <span class="fileinput-new">Select file</span>
                <span class="fileinput-exists">Change</span>
                <input type="file" name="attachments[]" />
            </span>
            <span class="fileinput-filename"></span>
            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
        </div>
    </div>
</div>

<div id="dynamicAttachmentInput">
</div>

<div class="form-group">
    <div class="col-sm-offset-2">
        <input id="add-image-input" type="button" class="btn btn-success" value="+ add file" onClick="addAttachmentInput('dynamicAttachmentInput');">
    </div>
</div>



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
            var inner = "<label class='col-sm-3 control-label'>&nbsp;</label>"
                + "<div class='col-sm-5'>"
                + "<div class='fileinput fileinput-new' data-provides='fileinput'>"
                + "<span class='btn btn-primary btn-file'>"
                + "<span class='fileinput-new'>Select file</span><span class='fileinput-exists'>Change</span><input type='file' name='attachments[]'></span> "
                + "<span class='fileinput-filename'></span>"
                + "<a href='#' class='close fileinput-exists' data-dismiss='fileinput' style='float: none'>&times;</a>"
                + "</div></div>";
            newdiv.innerHTML = inner;
            document.getElementById(divName).appendChild(newdiv);
            newdiv.className = 'form-group';
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


<!-- Remarks -->
<div class="form-group">
    {{ Form::label('remarks', 'Remarks:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-7">
        <textarea class="form-control" name="remarks">{{{ Input::old('remarks') ? Input::old('remarks') : '' }}}</textarea>
    </div>
</div>

<!-- Supplier -->
<div class="form-group">
    {{ Form::label('supplier_id', 'Supplier:', ['class' =>'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::select('supplier_id', $suppliers, Input::old('supplier_id') ? Input::old('supplier_id') : null, ['class'=>'form-control', 'id'=>'supplier_id']) }}
    </div>
</div>

<!-- Assigned To -->
<div class="form-group">
    {{ Form::label('assigned_user_id', 'Assigned To:', ['class' =>'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{--<select id="assigned_user_id" name="assigned_user_id" class="form-control">--}}

        {{--</select>--}}

        <select class="form-control" id="assigned_user_id" name="assigned_user_id">
          @foreach($assignableUsersList as $index => $company)
              {
                <optgroup label="{{$index}}">
                    @foreach($company as $id => $name)
                            <option value="{{$id}}">{{$name}}</option>
                    @endforeach
                </optgroup>
              }
          @endforeach
        </select>



{{--        {{ Form::select('assigned_user_id', $assignableUsersList, null, ['class'=>'form-control', 'id'=>'assigned_user_id']) }}--}}
    </div>
</div>

<!-- Status -->
<div class="form-group">
    {{ Form::label('status', 'Status:', ['class' =>'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::select('status', $statuses, Input::old('status') ? Input::old('status') : null, ['class'=>'form-control', 'id'=>'status']) }}
    </div>
</div>

<!-- Buttons -->
<div class="form-group">
    {{ Form::label('', '', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        <!-- Submit button -->
        {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'form-control btn btn-primary']) }}
        <!-- Cancel button -->
        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'form-control btn btn-warning')) }}
    </div>
</div>



<script type="text/javascript">

    $(document).ready(function() {

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
            var $userSelect = $("#assigned_user_id");
            $userSelect.empty();
            $.getJSON("../cataloguing/customer-users/" + $("#company_id").val(), function(data) {
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

            var $supplierSelect = $("#supplier_id");
            $supplierSelect.empty();
            $.getJSON("../cataloguing/suppliers/" + $("#company_id").val(), function(data) {
                $supplierSelect.append('<option value="">[Select]</option>');
                $.each(data, function(index, value) {
                    $supplierSelect.append('<option value="' + index +'">' + value + '</option>');
                });
            });
        });



    // Page Tour
        // Instanciate the tour
        var tour = new Tour({
            name: "product_definitions_tour",
            debug: "true",
            steps: [
                {
                    element: "#tour-intro",
                    title: "Welcome",
                    content: "Content of code step",
                    placement: "right",
                    orphan: true,
                    backdrop: true
                },
                {
                    element: "#code",
                    title: "Title of code step",
                    content: "Content of code step",
                    placement: "right"
                },
                {
                    element: "#uom",
                    title: "Title of my step",
                    content: "Content of my step"
                }
          ]
        });

        // Initialize the tour
        tour.init();

        // Start the tour
        tour.start();

        // Restart tour button
        $('#help').click(function(e){
            tour.restart();
            e.preventDefault();
        });

        $("#clear-images").click(function(event){
          event.preventDefault();
          $("#images").replaceWith("<input type='file' id='images' name='images[]' multiple />");
        });
    });

</script>

<!-- Bottom Scripts -->
<link rel="stylesheet" href="{{URL::asset('js/wysihtml5/bootstrap-wysihtml5.css')}}">
<script src="{{ URL::asset('js/fileinput.js') }}"></script>
<script src="{{ URL::asset('js/wysihtml5/wysihtml5-0.4.0pre.min.js') }}"></script>
<script src="{{ URL::asset('js/wysihtml5/bootstrap-wysihtml5.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-tour/build/js/bootstrap-tour.min.js') }}"></script>