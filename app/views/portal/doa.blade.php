@extends($layout)

@section('links')
    @parent
    <!-- BEGIN PACE PLUGIN FILES -->
    <script type="text/javascript" src="{{ asset('metronic/assets/global/plugins/pace/pace.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('metronic/assets/global/plugins/pace/themes/pace-theme-big-counter.css') }}"/>
    <!-- END PACE PLUGIN FILES -->
@stop

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Portal Reports', 'subheading' => 'Delegation of Authority'])
@stop

@section('content')

    @if (isset($doa))

    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-bubbles"></i>
                <span class="caption-subject bold uppercase"> Delegation of Authority</span>
                <span class="caption-helper">order approval rules</span>
            </div>
            <div class="actions">
                <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="scroller" style="max-height:500px;" data-always-visible="1" data-rail-visible="1" data-handle-color="grey">
                <table class="table table-bordered">
                            <thead>
                                <th>No</th>
                                <th>Customer</th>
                                <th>Name</th>
                                <th>Rule Condition</th>
                                <th>L1</th>
                                <th>L2</th>
                                <th>L3</th>
                                <th>L4</th>
                                <th>L5</th>
                            </thead>
                            @foreach ($doa as $rule)
                                <tr>
                                    <td>{{ $rule['rule_id'] }}</td>
                                    <td>{{ $rule['customer'] }}</td>
                                    <td>{{ $rule['name'] }}</td>
                                    <td>{{ $rule['orderamount'] }}</td>
                                    <td>{{ $rule['l1'] }}</td>
                                    <td>{{ $rule['l2'] }}</td>
                                    <td>{{ $rule['l3'] }}</td>
                                    <td>{{ $rule['l4'] }}</td>
                                    <td>{{ $rule['l5'] }}</td>
                                </tr>
                            @endforeach
                        </table>
            </div>
        </div>
    </div>
    <!-- END Portlet PORTLET-->

    @endif

@stop
