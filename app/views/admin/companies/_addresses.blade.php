<h3 class="company-addresses">Addresses</h3>
<br/>

<div>
    {{ Form::submit(isset($submit)?$submit:'Submit', ['class' => 'btn btn-primary']) }}
    {{ link_to_route('admin.companies.index', 'Cancel', null, array('class'=>'btn btn-default')) }}
</div>

<!-- Address 1 -->
<div class="col-sm-offset-2">
    <h3>Address 1</h3>
</div>
<div class="form-group">
    {{ Form::label('address1_description', 'Description:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('address1_description', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('address1_body', 'Body:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::textarea('address1_body', null, ['class' => 'form-control', 'rows' => 6]) }}
    </div>
</div>

<!-- Address 2 -->
<div class="col-sm-offset-2">
    <h3>Address 2</h3>
</div>
<div class="form-group">
    {{ Form::label('address2_description', 'Description:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('address2_description', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('address2_body', 'Body:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::textarea('address2_body', null, ['class' => 'form-control', 'rows' => 6]) }}
    </div>
</div>

<!-- Address 3 -->
<div class="col-sm-offset-2">
    <h3>Address 3</h3>
</div>
<div class="form-group">
    {{ Form::label('address3_description', 'Description:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('address3_description', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('address3_body', 'Body:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::textarea('address3_body', null, ['class' => 'form-control', 'rows' => 6]) }}
    </div>
</div>

<!-- Address 4 -->
<div class="col-sm-offset-2">
    <h3>Address 4</h3>
</div>
<div class="form-group">
    {{ Form::label('address4_description', 'Description:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::text('address4_description', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('address4_body', 'Body:', ['class' => 'col-sm-3 control-label']) }}
    <div class="col-sm-5">
        {{ Form::textarea('address4_body', null, ['class' => 'form-control', 'rows' => 6]) }}
    </div>
</div>