{{-- References --}}
<div class="row">
    @if(
        array_get($company_settings, 'product-requests.reference1.enabled', false)
        || array_get($company_settings, 'product-requests.reference2.enabled', false)
        || array_get($company_settings, 'product-requests.reference3.enabled', false)
        || array_get($company_settings, 'product-requests.reference4.enabled', false)
    )


            <!-- Reference1 Form Input -->
            @if(array_get($company_settings, 'product-requests.reference1.enabled', false))
                <div class="col-md-3">
                    {{ Form::label('reference1', array_get($company_settings, 'product-requests.reference1.label', 'Reference Field 1')) }}
                    {{ Form::text('reference1', $product_request->reference1, [
                            'class' => 'form-control',
                            'id' => 'reference_1_input'
                            , 'readonly'
                        ])
                    }}
                </div>
            @endif

            <!-- Reference2 Form Input -->
            @if(array_get($company_settings, 'product-requests.reference2.enabled', false))
                <div class="col-md-3">
                    {{ Form::label('reference2', array_get($company_settings, 'product-requests.reference2.label', 'Reference Field 2')) }}
                    {{ Form::text('reference2', $product_request->reference2, [
                            'class' => 'form-control',
                            'id' => 'reference_2_input',
                            'readonly'
                        ])
                    }}

                </div>
            @endif

            <!-- Reference3 Form Input -->
            @if(array_get($company_settings, 'product-requests.reference3.enabled', false))
                <div class="col-md-3">
                    {{ Form::label('reference3', array_get($company_settings, 'product-requests.reference3.label', 'Reference Field 3')) }}
                    {{ Form::text('reference3', $product_request->reference3, [
                            'class' => 'form-control',
                            'id' => 'reference_3_input',
                            'readonly'
                        ])
                    }}
                </div>
            @endif

            <!-- Reference4 Form Input -->
            @if(array_get($company_settings, 'product-requests.reference4.enabled', false))
                <div class="col-md-3">
                    {{ Form::label('reference4', array_get($company_settings, 'product-requests.reference4.label', 'Reference Field 4')) }}
                    {{ Form::text('reference4', $product_request->reference4, [
                            'class' => 'form-control',
                            'id' => 'reference_4_input',
                            'readonly'
                        ])
                    }}
                </div>
            @endif


</div>
@endif