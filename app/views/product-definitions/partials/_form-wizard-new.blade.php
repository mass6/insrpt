<div class="row">
    <div class="col-md-12">
        <div class="portlet light" id="form_wizard_1">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-primary-36s bold uppercase">
                    <i class="fa fa-compass"></i> Product Cataloguing Wizard - <span class="step-title">Step 1 of 4 </span>
                    </span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </div>
            </div>
            <div class="portlet-body form">
                {{--<form action="javascript:;" class="form-horizontal" id="submit_form" method="POST">--}}
                {{ Form::open(['route' => 'catalogue.product-definitions.store', 'id' => 'submit_form', 'class' => 'form-horizontal', 'files' => true]) }}
                    <div class="form-wizard">
                        <div class="form-body">
                            <ul class="nav nav-pills nav-justified steps">
                                <li>
                                    <a href="#tab1" data-toggle="tab" class="step">
                                    <span class="number">
                                    1 </span>
                                    <span class="desc">
                                    <i class="fa fa-check"></i> Basic Info </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab2" data-toggle="tab" class="step">
                                    <span class="number">
                                    2 </span>
                                    <span class="desc">
                                    <i class="fa fa-check"></i> Description </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab3" data-toggle="tab" class="step active">
                                    <span class="number">
                                    3 </span>
                                    <span class="desc">
                                    <i class="fa fa-check"></i> Product Photos </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab4" data-toggle="tab" class="step">
                                    <span class="number">
                                    4 </span>
                                    <span class="desc">
                                    <i class="fa fa-check"></i> File Attachments </span>
                                    </a>
                                </li>
                                @if($customAttributes)
                                <li>
                                    <a href="#tab5" data-toggle="tab" class="step">
                                    <span class="number">
                                    5 </span>
                                    <span class="desc">
                                    <i class="fa fa-check"></i> Attributes </span>
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a href="#tab6" data-toggle="tab" class="step">
                                    <span class="number">
                                    6 </span>
                                    <span class="desc">
                                    <i class="fa fa-check"></i> Submit </span>
                                    </a>
                                </li>
                            </ul>
                            <div id="bar" class="progress progress-striped" role="progressbar">
                                <div class="progress-bar progress-bar-primary">
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <div class="alert alert-success display-none">
                                    <button class="close" data-dismiss="alert"></button>
                                    Your form validation is successful!
                                </div>

                                {{-- Tab 1 - Basic Info --}}
                                <div class="tab-pane active" id="tab1">
                                    <div class="row">
                                        <h3>Basic Product Information</h3>
                                        <br />
                                        <br />
                                    </div>

                                    <div class="well">
                                        <div class="row">
                                            @if($currentUser->hasAccess('cataloguing.products.admin'))
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label" for="full_name">Customer</label>
                                                        {{ Form::text('customer-select', $companies[$company_id], ['class'=>'form-control', 'disabled']) }}
                                                        {{ Form::hidden('company_id', $company_id, ['id'=>'company_id']) }}
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Owned by CompanyID -->
                                                {{Form::hidden('company_id', $currentUser->company->id)}}
                                            @endif

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="supplier_id">Supplier</label>
                                                    {{ Form::select('supplier_id', $suppliers, Input::old('supplier_id') ? Input::old('supplier_id') : (Session::get('selectedSupplier', null)), ['class'=>'form-control', 'id'=>'supplier_id']) }}
                                                    {{ $errors->first('supplier_id', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
                                                </div>
                                            </div>

                                            @if($company->type == "customer")
                                                <div class="col-md-4">
                                                    <label class="control-label">&nbsp;</label>
                                                    <div class="form-group">
                                                        {{ link_to_route('admin.companies.create_supplier', 'Add new supplier', array($company->id), array('class'=>'save btn btn-primary add-supplier', 'style' => '')) }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="code">Code</label>
                                                    {{ Form::text('code', Input::old('code') ? Input::old('code') : null, ['class' => 'form-control step1', 'id' => 'code', 'data-validate' => 'required', 'placeholder' => 'Item Code, Product Code, or SKU']) }}
                                                    {{ $errors->first('code', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="control-label" for="name">Name</label>
                                                    {{ Form::text('name', Input::old('name') ? Input::old('name') : null, ['class' => 'form-control','id' => 'name', 'data-validate' => 'required', 'placeholder' => 'Name as it shall be cataloged in the portal']) }}
                                                    {{ $errors->first('name', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="category">Category</label>
                                                    {{ Form::text('category', Input::old('category') ? Input::old('category') : null, ['class'=>'form-control','id'=>'category','placeholder'=>'Category that this product should be classified in']) }}
                                                    {{ $errors->first('category', '<p class="error-label"><span class="label label-danger">:message</span></p>') }}
                                                </div>
                                            </div>
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

                                            {{Form::hidden('currency', 'AED')}}

                                        </div>

                                    </div>

                                    <div class="">
                                        <button id="save" type="submit" class="save btn btn-primary">Save Draft</button>
                                        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger', 'style' => '')) }}
                                    </div>


                                </div>

                                {{-- Tab 2 - Description --}}
                                <div class="tab-pane" id="tab2">

                                    <div class="row">
                                        <h2>Product Description <small>(see example) &rightarrow;</small> <a href="{{URL::asset('images/products/product-description-sample.png')}}" target="_blank"><img src="{{URL::asset('images/products/product-description-sample.png')}}" width="70" style="border:1px solid #DDDDDD;"></a></h2>
                                        <br />
                                        <br />
                                    </div>

                                    <div class="well">
                                        {{ $errors->first('short_description', '<span class="label label-danger">:message</span>') }}
                                        <h3>Short Description</h3>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <p style="font-size: 16px;" class="text text-info">Provide a short one or two line product description.</p>
                                                    <textarea class="form-control" name="short_description" id="short_description" rows="5" placeholder="Short summary of the product">{{{ Input::old('short_description') ? Input::old('short_description') : '' }}}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="well">
                                        {{ $errors->first('description', '<span class="label label-danger">:message</span>') }}
                                        <h3>Full Description</h3>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <p style="font-size: 16px;" class="text text-info">Here is where you list the full product details. Be as descriptive as possible. Format the description as you wish it to appear on the portal.</p>
                                                    <textarea class="form-control ckeditor" name="description" id="description">{{{ Input::old('description') ? Input::old('description') : '' }}}</textarea>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="">
                                        <button id="save" type="submit" class="save btn btn-primary">Save Draft</button>
                                        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger', 'style' => '')) }}
                                    </div>


                                </div>

                                {{-- Tab 3 - Product Photos --}}
                                <div class="tab-pane" id="tab3">

                                    <div class="row">
                                        <h3>Product Photos <small>1MB max file size per photo</small></h3>
                                        <br />
                                    </div>

                                    <?php $customImageLabels = $company->settings()->get('productDefinitions.customImageLabels'); ?>
                                    <div class="row col-sm-12">

                                        {{-- Image 1 --}}
                                        <div id="image-div0" class="form-group col-md-3">
                                            <h5>{{ isset($customImageLabels['imageLabel1']) ? $customImageLabels['imageLabel1'] : 'Primary Image' }}</h5>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;" data-trigger="fileinput">
                                                    <img src="http://placehold.it/150&text=Product+photo" alt="...">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                                <div>
                                                    <span class="btn btn-primary btn-file">
                                                        <span class="fileinput-new">Select image</span>
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
                                    <br/>
                                    <br/>

                                    <div class="">
                                        <button id="save" type="submit" class="save btn btn-primary">Save Draft</button>
                                        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger', 'style' => '')) }}
                                    </div>



                                </div>

                                {{-- Tab 4 - File Attachments --}}
                                <div class="tab-pane" id="tab4">

                                    <div class="row">
                                        <h3>File Attachments <small>2MB max file size per attachment</small></h3>
                                        <br />
                                    </div>

                                   <div class="well">
                                       <h3>Attach up to 5 files <small><em>(2MB max file size per attachment)</em></small></h3>
                                       <br/>

                                        <?php $customAttachmentLabels = $company->settings()->get('productDefinitions.customAttachmentLabels'); ?>
                                        <ul class="list-unstyled">

                                            <li style="margin-bottom: 10px;">
                                                <div class="row col-md-12">
                                                    {{ isset($customAttachmentLabels['attachmentLabel1']) ? '<h5>' . $customAttachmentLabels['attachmentLabel1'] . '</h5>' : '' }}
                                                    {{ $errors->first('attachment1', '<span class="label label-danger">:message</span><br/><br/>') }}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <p class="text-right" style="font-size: 14px; font-weight: bold;">1.</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div  class="form-group">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                               <span class="btn btn-sm btn-primary btn-file">
                                                                   <span class="fileinput-new">Select file</span>
                                                                   <span class="fileinput-exists">Change</span>
                                                                   <input type="file" name="attachment1" />
                                                               </span>
                                                               <span class="fileinput-filename"></span>
                                                               <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </li>

                                            <li style="margin-bottom: 10px;">

                                                <div class="row col-md-12">
                                                    {{ isset($customAttachmentLabels['attachmentLabel2']) ? '<h5>' . $customAttachmentLabels['attachmentLabel2'] . '</h5>' : '' }}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <p class="text-right" style="font-size: 14px; font-weight: bold;">2.</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div  class="form-group">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                               <span class="btn btn-sm btn-primary btn-file">
                                                                   <span class="fileinput-new">Select file</span>
                                                                   <span class="fileinput-exists">Change</span>
                                                                   <input type="file" name="attachment2" />
                                                               </span>
                                                               <span class="fileinput-filename"></span>
                                                               <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </li>

                                            <li style="margin-bottom: 10px;">

                                                <div class="row col-md-12">
                                                    {{ isset($customAttachmentLabels['attachmentLabel3']) ? '<h5>' . $customAttachmentLabels['attachmentLabel3'] . '</h5>' : '' }}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <p class="text-right" style="font-size: 14px; font-weight: bold;">3.</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div  class="form-group">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                               <span class="btn btn-sm btn-primary btn-file">
                                                                   <span class="fileinput-new">Select file</span>
                                                                   <span class="fileinput-exists">Change</span>
                                                                   <input type="file" name="attachment3" />
                                                               </span>
                                                               <span class="fileinput-filename"></span>
                                                               <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </li>

                                            <li style="margin-bottom: 10px;">

                                                <div class="row col-md-12">
                                                    {{ isset($customAttachmentLabels['attachmentLabel4']) ? '<h5>' . $customAttachmentLabels['attachmentLabel4'] . '</h5>' : '' }}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <p class="text-right" style="font-size: 14px; font-weight: bold;">4.</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div  class="form-group">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                               <span class="btn btn-sm btn-primary btn-file">
                                                                   <span class="fileinput-new">Select file</span>
                                                                   <span class="fileinput-exists">Change</span>
                                                                   <input type="file" name="attachment4" />
                                                               </span>
                                                               <span class="fileinput-filename"></span>
                                                               <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </li>

                                            <li style="margin-bottom: 10px;">

                                                <div class="row col-md-12">
                                                    {{ isset($customAttachmentLabels['attachmentLabel5']) ? '<h5>' . $customAttachmentLabels['attachmentLabel5'] . '</h5>' : '' }}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <p class="text-right" style="font-size: 14px; font-weight: bold;">5.</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div  class="form-group">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                               <span class="btn btn-sm btn-primary btn-file">
                                                                   <span class="fileinput-new">Select file</span>
                                                                   <span class="fileinput-exists">Change</span>
                                                                   <input type="file" name="attachment5" />
                                                               </span>
                                                               <span class="fileinput-filename"></span>
                                                               <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </li>

                                        </ul>

                                   </div>

                                    <div class="">
                                        <button id="save" type="submit" class="save btn btn-primary">Save Draft</button>
                                        {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger', 'style' => '')) }}
                                    </div>


                                </div>

                            @if($customAttributes)
                                {{-- Tab 5 - Attributes --}}
                                <div class="tab-pane" id="tab5">

                                    <div class="row">
                                        <h3>Product Attributes</h3>
                                        <br />
                                        <p style="font-size: 16px">You can define additional product attributes to help define the product more specifically. Attributes
                                        you define here will be included with the product details that are uploaded to the portal. Attributes
                                        help users find locate and identify products by their key product characteristics.</p>
                                        <br/>
                                    </div>
                                    <div class="row">
                                        @if($customAttributes)
                                            @include('product-definitions.partials._' . strtolower($customAttributes) . '-attributes-form')
                                        @else
                                            @if($add_attributes)
                                                <input id="add-attribute" type="button" class="btn btn-success" value="+ add Attribute" > <span id="attribute-helper" style="display: none"><em>&leftarrow; Click to add more attributes</em></span><br/><br/>
                                            @endif
                                            <div id="new-attributes" class="well" style="min-height: 200px;">
                                            </div>
                                        @endif

                                        <div class="">
                                            <button id="save" type="submit" class="save btn btn-primary">Save Draft</button>
                                            {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger', 'style' => '')) }}
                                        </div>

                                    </div>

                                </div>
                            @endif

                                {{-- Tab 6 - Submit --}}
                                <div class="tab-pane" id="tab6">

                                    <div class="row">
                                        <h3>Review & Assign</h3>
                                        <br />
                                        <p style="font-size: 16px;">Good job!</p>
                                        <p style="font-size: 16px;">You're nearly done. If you wish to review the form, you can do by clicking
                                        on the <label class="label label-default">&lt; Previous</label> button below, or by clicking any of the step numbers listed above to go to that specific section.</p>
                                        <p style="font-size: 16px;">Once you are finished reviewing and have ensured that all the required
                                        fields have been completed, you can submit your cataloguing request now for processing by 36S. Alternatively,
                                        you can save it as a draft and come back later to complete it.</p>
                                    </div>
                                    <div id="div-remarks" class="row">
                                        <div class="col-md-8">
                                            {{ $errors->first('remarks', '<span class="label label-danger">:message</span>') }}
                                            <h3>Remarks</h3>
                                            <div class="form-group">
                                                <p>Add any remarks or comments you have here.</p>
                                                <textarea class="form-control" name="remarks" id="remarks" rows="3">{{{ Input::old('remarks') ? Input::old('remarks') : '' }}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <br/>

                                    <div class="row">
                                        <div class="col-md-4">




                                            {{--<div class="form-group">--}}
                                                {{--@if($user->hasAccess('cataloguing.products.catalogue'))--}}
                                                    {{--<button id="assign-to-customer" type="submit" class="btn btn-primary">Assign to Customer</button>--}}
                                                    {{--<button id="assign-to-supplier" type="submit" class="btn btn-gold">Assign to Supplier</button>--}}
                                                {{--@else--}}
                                                    {{--@if($user->hasAccess('cataloguing.products.submit'))--}}
                                                        {{--<button id="submit" type="submit" class="btn btn-primary">Submit Now</button>--}}
                                                    {{--@endif--}}
                                                {{--@endif--}}
                                                {{--@if($user->hasAccess('cataloguing.products.catalogue'))--}}
                                                    {{--<button id="process" type="submit" class="btn btn-green">Submit for Processing</button>--}}
                                                {{--@endif--}}

                                            {{--</div>--}}

                                            <label class="control-label" style="font-size: 14px;" for="supplier_id"><strong>Select Action</strong></label>
                                            <div class="input-group">
                                                <select name="action" class="form-control" id="actions">
                                                    <option value="reviewing">Submit For Catalogue Team Review</option>
                                                    <option value="save">Save Draft</option>
                                                    @if($currentUser->hasAccess('cataloguing.products.catalogue'))
                                                        <option value="assign-to-customer">Assign to Customer</option>
                                                    @endif
                                                    @if($currentUser->hasAccess('cataloguing.products.assign-supplier'))
                                                        <option value="assign-to-supplier">Assign to Supplier</option>
                                                    @endif
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="submit">Go!</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="cancel" style="font-size: 14px;" class="control-label">&nbsp;</label>
                                            <div class="input-group">
                                                {{ link_to_route('catalogue.product-definitions.index.company_id', 'Cancel', 'all', array('class'=>'btn btn-danger')) }}
                                            </div>
                                        </div>

                                    </div>


                                </div>


                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-9 col-md-3">
                                    <a href="javascript:;" class="btn default button-previous">
                                    <i class="m-icon-swapleft"></i> Back </a>
                                    <a href="javascript:;" class="btn blue button-next">
                                    Next <i class="m-icon-swapright m-icon-white"></i>
                                    </a>
                                    <a href="javascript:;" class="btn green button-submit">
                                    Submit <i class="m-icon-swapright m-icon-white"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>