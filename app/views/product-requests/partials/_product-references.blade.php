{{-- References --}}
<div class="row">
    @if(
        array_get($company_settings, 'product-requests.reference1.enabled', false)
        || array_get($company_settings, 'product-requests.reference2.enabled', false)
        || array_get($company_settings, 'product-requests.reference3.enabled', false)
        || array_get($company_settings, 'product-requests.reference4.enabled', false)
    )
	<div class="col-md-12">

		<div class="panel panel-info" data-collapsed="0">

			<div class="panel-heading">
				<div class="panel-title">
					<h4>Product References</h4>
				</div>

				<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				</div>
			</div>

			<div class="panel-body">

				<div class="row">

                    <!-- Reference1 Form Input -->
                    @if(array_get($company_settings, 'product-requests.reference1.enabled', false))
                        <div class="col-md-3">
                            {{ Form::label('reference1', array_get($company_settings, 'product-requests.reference1.label', 'Reference Field 1')) }}
                            {{ Form::text('reference1', null, [
                                    'class' => 'form-control',
                                    'id' => 'reference_1_input'
                                ])
                            }}
                            {{ $errors->first('reference1', '<span class="text text-danger">* :message</span>') }}
                        </div>
                    @endif

                    <!-- Reference1 Form Input -->
                    @if(array_get($company_settings, 'product-requests.reference2.enabled', false))
                        <div class="col-md-3">
                            {{ Form::label('reference2', array_get($company_settings, 'product-requests.reference2.label', 'Reference Field 2')) }}
                            {{ Form::text('reference2', null, [
                                    'class' => 'form-control',
                                    'id' => 'reference_2_input'
                                ])
                            }}
                            {{ $errors->first('reference2', '<span class="text text-danger">* :message</span>') }}

                        </div>
                    @endif

                    <!-- Reference1 Form Input -->
                    @if(array_get($company_settings, 'product-requests.reference3.enabled', false))
                        <div class="col-md-3">
                            {{ Form::label('reference3', array_get($company_settings, 'product-requests.reference3.label', 'Reference Field 3')) }}
                            {{ Form::text('reference3', null, [
                                    'class' => 'form-control',
                                    'id' => 'reference_3_input'
                                ])
                            }}
                            {{ $errors->first('reference3', '<span class="text text-danger">* :message</span>') }}
                        </div>
                    @endif

                    <!-- Reference1 Form Input -->
                    @if(array_get($company_settings, 'product-requests.reference4.enabled', false))
                        <div class="col-md-3">
                            {{ Form::label('reference4', array_get($company_settings, 'product-requests.reference4.label', 'Reference Field 4')) }}
                            {{ Form::text('reference4', null, [
                                    'class' => 'form-control',
                                    'id' => 'reference_4_input'
                                ])
                            }}
                            {{ $errors->first('reference4', '<span class="text text-danger">* :message</span>') }}
                        </div>
                    @endif

				</div>

			</div>

        </div>

    </div>
</div>
@endif