<div class="well">
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="name">Name <i style="color: red;">(*)</i></label>
                {{ Form::text('name', Input::old('name') ? Input::old('name') : null, ['class' => 'form-control','id' => 'name', 'data-validate' => 'required', 'placeholder' => 'Name as it shall be cataloged in the portal']) }}
                {{ $errors->first('name', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="category">Category</label>
                {{ Form::text('category', Input::old('category') ? Input::old('category') : null, ['class'=>'form-control','id'=>'category','placeholder'=>'Category that this product should be classified in']) }}
                {{ $errors->first('category', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
            </div>
        </div>

    </div>

    <div class="well">
        {{ $errors->first('short_description', '<span class="label label-danger">:message</span>') }}
        <h3>Short Description</h3>
        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <p style="font-size: 16px;" class="text text-info">Provide a short one or two line product description. <i style="color: red;font-size: 12px;">(*)</i></p>
                    <textarea class="form-control" name="short_description" id="short_description" rows="5" placeholder="Short summary of the product">{{{ Input::old('short_description') ? Input::old('short_description') : '' }}}</textarea>
                </div>
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="uom">UOM</label>
                {{ Form::text('uom', Input::old('uom') ? Input::old('uom') : null, ['class'=>'form-control','id'=>'uom','placeholder'=>'e.g. Each, Pack, Carton']) }}
                {{ $errors->first('uom', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Price</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <span>AED</span>
                    </div>
                    {{ Form::text('price', Input::old('price') ? Input::old('price') : null, ['class'=>'form-control','id'=>'price','data-validate'=>'number']) }}
                </div>
                {{ $errors->first('price', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
            </div>
        </div>

    </div>

</div>
<div class="well">
    <div class="row">
        <h3>Product Photos <small>1MB max file size per photo</small></h3>
        <br />
    </div>

    <?php $customImageLabels = $company->settings()->get('productDefinitions.customImageLabels'); ?>
    <div class="row col-sm-12">

        {{-- Image 1 --}}
        <div id="image-div0" class="form-group col-md-3">
            <h5>{{ isset($customImageLabels['imageLabel1']) ? $customImageLabels['imageLabel1'] : 'Primary Image' }} <i style="color: red;">(*)</i></h5>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;" data-trigger="fileinput">
                    <img src="http://placehold.it/150&text=Product+photo" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                <div>
                            <span class="btn btn-primary btn-file">
                                <span class="fileinput-new">Select image </span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image1" accept="image/*">
                            </span>
                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>
            {{ $errors->first('image1', '<span class="label label-danger">:message</span><br/><br/>') }}
        </div>
        {{-- Image 2--}}
        <div id="image-div1" class="form-group col-md-3">
            <h5>{{ isset($customImageLabels['imageLabel2']) ? $customImageLabels['imageLabel2'] : 'Image 2' }}</h5>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;" data-trigger="fileinput">
                    <img src="http://placehold.it/150&text=Product+photo" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                <div>
                            <span class="btn btn-primary btn-file">
                                <span class="fileinput-new">Select image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image2" accept="image/*">
                            </span>
                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>
        </div>

        {{-- Image 3 --}}
        <div id="image-div2" class="form-group col-md-3">
            <h5>{{ isset($customImageLabels['imageLabel3']) ? $customImageLabels['imageLabel3'] : 'Image 3' }}</h5>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;" data-trigger="fileinput">
                    <img src="http://placehold.it/150&text=Product+photo" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                <div>
                            <span class="btn btn-primary btn-file">
                                <span class="fileinput-new">Select image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image3" accept="image/*">
                            </span>
                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>
        </div>


        {{-- Image 4 --}}
        <div id="image-div3" class="form-group col-md-3">
            <h5>{{ isset($customImageLabels['imageLabel4']) ? $customImageLabels['imageLabel4'] : 'Image 4' }}</h5>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;" data-trigger="fileinput">
                    <img src="http://placehold.it/150&text=Product+photo" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                <div>
                            <span class="btn btn-primary btn-file">
                                <span class="fileinput-new">Select image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="image4" accept="image/*">
                            </span>
                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="request_type" value="{{$request_type}}">
<div class="">
    <button id="save" type="submit" class="save btn btn-primary">Save</button>
    {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger', 'style' => '')) }}
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

    var imageSerial = 1; // serial to append to new element id
    var imageCounter = 0; // current count/index of image
    var imageLimit = 5; // max amount of images allowed


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
<!-- Bottom Scripts -->


<link rel="stylesheet" href="{{URL::asset('js/selectboxit/jquery.selectBoxIt.css')}}">

<script src="{{ URL::asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL::asset('js/ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ URL::asset('js/fileinput.js') }}"></script>
<script src="{{ URL::asset('js/joinable.js') }}"></script>
<script src="{{ URL::asset('js/resizeable.js') }}"></script>
<script src="{{ URL::asset('js/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ URL::asset('js/jselectboxit/jquery.selectBoxIt.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-switch.js') }}"></script>
<script src="{{ URL::asset('js/jquery.multi-select.js') }}"></script>