<?php
use Carbon\Carbon;$formats = [];
    $now = Carbon::now();
    $formats['d-M-y'] = $now->format('d-M-y');
    $formats['Y-m-d H:i:s'] = $now->format('Y-m-d H:i:s');
    $formats['m/d/y H:i:s'] = $now->format('m/d/y H:i:s');
    $formats['d-m-Y'] = $now->format('d-m-Y');
    $formats['d-M-y H:i:s'] = $now->format('d-M-y H:i:s');
    $formats['d-m-y h:i:s'] = $now->format('d-m-y h:i:s');
    $formats['D, M-d Y \a\t h:i:s a'] = $now->format('D, M-d Y \a\t h:i:s a');
    $formats['jS \o\f F, Y g:i:s a'] = $now->format('jS \o\f F, Y g:i:s a');
?>

<div class="col-md-2 col-md-offset-2">
    {{ Form::label("settings[order_report][field_definitions][{$key}][formatting][date_format]", 'Date Format:', ['class' => 'formatting-label']) }}
</div>
<div class="col-md-4">
    {{ Form::select(
            "settings[order_report][field_definitions][{$key}][formatting][date_format]",
            $formats,
            array_get($field,"formatting.date_format", null ),
            ['class' => 'form-control', 'id' => "{$key}_formatting_date_format_input"]
    ) }}

</div>
