<h3 class="company-information">General Information</h3>
<br/>
<div>
    {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'btn btn-primary']) }}
    {{ link_to_route('admin.companies.index', 'Cancel', null, array('class'=>'btn btn-default')) }}
</div>
<!-- Company Name Form Input -->
<div class="form-group">
    {{ Form::label('name', 'name:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-8">
        {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
        {{ $errors->first('name', '<span class="text text-danger">* :message</span>') }}
    </div>
</div>

<!-- Company Type Form Input -->
<div class="form-group">
    {{ Form::label('type', 'type:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-8">
        {{ Form::text('type', null, ['class' => 'form-control', 'required']) }}
        {{ $errors->first('type', '<span class="text text-danger">* :message</span>') }}
    </div>
</div>
<!-- Primary Contact Form -->
@if(isset($is_edit))
    <div class="form-group">
        {{ Form::label('primary_contact', 'Primary Contact:', ['class' => 'col-sm-3 control-label']) }}
        <div class="col-sm-5">
            {{ Form::select('primary_contact_user_id', $userDropdown, null, ['class'=>'form-control', 'id'=>'primary_contact_user_id']) }}
            {{ $errors->first('primary_contact_user_id', '<span class="text text-danger">* :message</span>') }}
        </div>
    </div>
    @if(isset($unassignedSuppliers))
        <div class="form-group">

            <label class="col-sm-3 control-label">Associated Suppliers<br/>
                <small>Associated Suppliers on the right</small></label>

            <div class="col-md-8">
                <select multiple="multiple" name="supplier_ids[]" id="supplier-multiselect" class="form-control multi-select">

                        @foreach ($suppliers as $key=>$val)
                            <option value="{{ $key }}" selected>{{ $val }}</option>
                        @endforeach

                        @foreach ($unassignedSuppliers as $key=>$val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach

                </select>
            </div>
        </div>
    @endif

@endif
<!-- Notes Form Input -->
<div class="form-group">
    {{ Form::label('name', 'notes:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-8">
        {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2]) }}
    </div>
</div>