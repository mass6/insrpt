<div class="col-md-2 col-md-offset-2">
    {{ Form::label("settings[order_report][field_definitions][{$key}][formatting][decimal_places]", 'Decimal Places:', ['class' => 'formatting-label']) }}
</div>
<div class="col-md-1">
    {{ Form::text("settings[order_report][field_definitions][{$key}][formatting][decimal_places]", array_get($field,"formatting.decimal_places", '' ), ['class' => 'form-control', 'id' => "{$key}_formatting_decimal_places_input"]) }}
</div>
<div class="col-md-2">
    {{ Form::label("settings[order_report][field_definitions][{$key}][formatting][thousands_separator]", 'Thousands Separator:', ['class' => 'formatting-label']) }}
</div>
<div class="col-md-1">
    {{ Form::checkbox("settings[order_report][field_definitions][{$key}][formatting][thousands_separator]", true, array_get($field,"formatting.thousands_separator", false ), ['class' => 'form-control', 'id' => "{$key}_formatting_thousands_separator_input"]) }}
</div>
