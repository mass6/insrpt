@extends($layout)

@section('links')
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-icons/entypo/css/entypo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ asset('metronic/assets/admin/layout3/css/tiles.css') }}" rel="stylesheet" type="text/css">
    @parent


<style>
    div.page-content div.portlet * {
        font-size: 1em !important;
    }

</style>

@stop

@section('content')

<div class="row tiles">

    <div class="col-sm-4">
        <a href="{{ route('portal.orders.pending-approval') }}">
            <div class="tile-stats tile-red">
                <div class="icon"><i class="entypo-loop"></i></div>
                <div id="pending-approval-count" class="num" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">0</div>
                <span class="lab">AED </span><div id="pending-approval-value" class="val" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">0</div>

                <h3>Pending Approval</h3>
                <p>waiting to be approved.</p>
            </div>
        </a>
    </div>

    {{--<div class="col-sm-3">--}}
        {{--<a href="{{ route('portal.orders.period', 'today') }}">--}}
            {{--<div class="tile-stats tile-green">--}}
                {{--<div class="icon"><i class="entypo-basket"></i></div>--}}
                {{--<div id="orders-today-count" class="num" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">0</div>--}}
                {{--<span class="lab">AED </span><div id="orders-today-value" class="val" data-start="0" data-end="0" data-postfix="" data-duration="1500" data-delay="0">0</div>--}}

                {{--<h3>Orders Approved Today</h3>--}}
                {{--<p>approved so far today.</p>--}}
            {{--</div>--}}
        {{--</a>--}}

    {{--</div>--}}

    <div class="col-sm-4">
        <a href="{{ route('portal.orders.this-month') }}">
            <div class="tile-stats tile-aqua">
                <div class="icon"><i class="entypo-chart-area"></i></div>
                <div id="monthly-order-count" class="num" data-start="0" data-end="23" data-postfix="" data-duration="1500" data-delay="1200">0</div>
                <span class="lab">AED </span><div id="monthly-order-value" class="val" data-start="0" data-end="23" data-postfix="" data-duration="1500" data-delay="1200">0</div>

                <h3>Approved This Month</h3>
                <p>total orders so far this month.</p>
            </div>
        </a>
    </div>

@if($currentUser->hasAccess('portal.orders.thirdparty'))
    <div class="col-sm-4">
        <a href="{{ route('portal.orders.third-party-this-month') }}">
            <div class="tile-stats tile-blue">
                <div class="icon"><i class="entypo-export"></i></div>
                <div id="third-party-order-count" class="num" data-start="0" data-end="52" data-postfix="" data-duration="1500" data-delay="1800">0</div>
                <span class="lab">AED </span><div id="third-party-order-value" class="val" data-start="0" data-end="52" data-postfix="" data-duration="1500" data-delay="1800">0</div>

                <h3>Third Party Orders</h3>
                <p>orders to third-party suppliers this month.</p>
            </div>
        </a>
    </div>
@endif

</div>

<div id="permission_notice" class="alert alert-danger" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p>You don't have permission to view orders.
</div>

<br/>

<div class="row">
    <div class="col-sm-8">

        <!-- BEGIN Portlet PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart"></i>
                    <span class="caption-subject bold uppercase"> Daily Order Totals</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </div>
            </div>
            <span class="caption-helper">Last 90 Days</span>
            <div class="portlet-body">

                <div class="tab-pane active" id="bar_parent">
                    <div id="bar-chart" class="morrischart" style="height: 300px"></div>
                </div>

            </div>
        </div>
        <!-- END Portlet PORTLET-->

    </div>

    <div class="col-sm-4">

        <!-- BEGIN Portlet PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-pie-chart"></i>
                    <span class="caption-subject bold uppercase"> Spend by Category</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </div>
            </div>
            <span class="caption-helper">Last 90 Days</span>
            <div class="portlet-body">

                <div id="categorychart" style="height: 300px"></div>

            </div>
        </div>
        <!-- END Portlet PORTLET-->



    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart"></i>
                    <span class="caption-subject bold uppercase"> Products Ordered</span>
                </div>
                <div class="actions">
                    <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen"></a>
                </div>
            </div>
            <span class="caption-helper">Last 90 Days, Grouped by Month</span>
            <div class="portlet-body">
                <div class="tab-pane active" id="bar_parent">
                    <a href="{{ $productsOrderedReportUrl }}">
                        <div id="products_ordered_chart" style="height: 300px;"></div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@stop

@section('bottomlinks')
@parent
<script src="{{ asset('js/gsap/main-gsap.js') }}"></script>
<script src="{{ asset('js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/joinable.js') }}"></script>
<script src="{{ asset('js/resizeable.js') }}"></script>
<script src="{{ asset('js/neon-api.js') }}"></script>
<script src="{{ asset('js/neon-chat.js') }}"></script>
{{--<script src="{{ asset('js/neon-custom.js') }}"></script>--}}

<script src="{{ asset('js/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js/raphael-min.js') }}"></script>
<script src="{{ asset('js/morris.min.js') }}"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    function drawCharts() {
        function getTooltipHtml(product, formatter) {
            // make use of Morris chart styles
            return '<div class="morris-hover morris-default-style">'+
                    '<div class="morris-hover-row-label">' + product.period + '</div>'+
                    '<div class="morris-hover-point">' + product.name + '</div>'+
                    '<div class="morris-hover-point">UOM: ' + product.uom + '</div>'+
                    '<div class="morris-hover-point">Price: AED' + formatter.formatValue(product.unit_price) + '</div>'+
                    '<div class="morris-hover-point">Qty ordered: ' + formatter.formatValue(product.qty_ordered) + '</div>'+
                    '<div class="morris-hover-point">Ordered value: AED' + formatter.formatValue(product.ordered_value) + '</div>'+
                    '</div>';
        }
        var productsOrdered = Insight.productsOrdered.data;
        if (productsOrdered.length) {
            var data = new google.visualization.DataTable(),
                    rows = [],
                    productLimit = 10,
                    formatter = new google.visualization.NumberFormat({decimalSymbol: '.', groupingSymbol: ','});
            for (var i = 0; i < productsOrdered.length; i++) {
                var row = [productsOrdered[i].period];
                for (var j = 0; j < productLimit; j++) {
                    var product = productsOrdered[i].products_ordered.data[j];
                    if (product) {
                        product.period = row[0];
                        row.push(product.qty_ordered);
                        row.push(getTooltipHtml(product, formatter));
                    } else {
                        row.push(0);
                        row.push('');
                    }
                }
                rows.push(row);
            }
            data.addColumn('string', 'Period');
            for (var i = 0; i < productLimit; i++) {
                data.addColumn('number', 'prod' + i);
                data.addColumn({type: 'string', role: 'tooltip', p: {html: true}});
            }
            data.addRows(rows);
            var options = {
                height: 280,
                legend: 'none',
                chartArea: {width: '85%', height: '80%'},
                bar: {groupWidth: "85%"},
                tooltip: {isHtml: true},
                fontName: 'sans-serif',
                fontSize: 12,
                vAxis: {format: 'decimal', textStyle: {color: '#949494'}},
                hAxis: {textStyle: {color: '#949494'}},
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('products_ordered_chart'));
            chart.draw(data, options);
        }
    }
</script>
<script type="text/javascript">google.load("visualization", "1", {packages: ["corechart"], callback: drawCharts})</script>

<script type="text/javascript">
$(document).ready(function($)
{
    // set page-in animation class
    //document.body.className += ' ' + 'page-left-in';
    // Dashboard Panels

//    document.getElementById('orders-today-count').setAttribute('data-end', Insight.ordersTodayCount);
//    document.getElementById('orders-today-value').setAttribute('data-end', Insight.ordersTodayValue);
    document.getElementById('pending-approval-count').setAttribute('data-end', Insight.pendingApprovalCount);
    document.getElementById('pending-approval-value').setAttribute('data-end', Insight.pendingApprovalValue);
    $('#pending-approval-count').html(Insight.pendingApprovalCount);
    $('#pending-approval-value').html(Insight.pendingApprovalValue.toFixed(0));
    document.getElementById('monthly-order-count').setAttribute('data-end', Insight.monthlyOrderCount);
    document.getElementById('monthly-order-value').setAttribute('data-end', Insight.monthlyOrderValue);
    $('#monthly-order-count').html(Insight.monthlyOrderCount);
    $('#monthly-order-value').html(Insight.monthlyOrderValue.toFixed(0));
@if($currentUser->hasAccess('portal.orders.thirdparty'))
    document.getElementById('third-party-order-count').setAttribute('data-end', Insight.thirdPartyOrderCount);
    document.getElementById('third-party-order-value').setAttribute('data-end', Insight.thirdPartyOrderValue);
    $('#third-party-order-count').html(Insight.thirdPartyOrderCount);
    $('#third-party-order-value').html(Insight.thirdPartyOrderValue.toFixed(0));
@endif

    @if(Session::has('ordersView'))
        $('#permission_notice').css('display','block');
        <?php Session::forget('ordersView'); ?>
    @endif

    // Bar Charts
    var bar_chart = $("#bar-chart");

    var day = Insight.dailyOrderTotals;
    if (day.length){
        var bardata = [];
        for (var i = 0; i < day.length; i++)
        {
            bardata.push({d: day[i].day, v:day[i].total, c:day[i].count });
        }
    }

    var bar_parent = Morris.Bar({
        element: 'bar-chart',
        data: bardata,
        xkey: 'd',
        ykeys: ['v', 'c'],
        labels: ['Total AED', '# of orders']
    });

    bar_chart.parent().attr('style', '');


    // Donut Chart
//        var donut_chart_demo = $("#donut-chart-demo");
//
//        donut_chart_demo.parent().show();
//
//        var donut_chart = Morris.Donut({
//            element: 'donut-chart-demo',
//            data: [
//                {label: "Download Sales", value: getRandomInt(10,50)},
//                {label: "In-Store Sales", value: getRandomInt(10,50)},
//                {label: "Mail-Order Sales", value: getRandomInt(10,50)}
//            ],
//            colors: ['#707f9b', '#455064', '#242d3c']
//        });
//
//        donut_chart_demo.parent().attr('style', '');

    // Cateogory Donut Chart
    var cat = Insight.spendPerCategory;
    if (cat.length){
        var donutdata = [];
        for (var i = 0; i < cat.length; i++)
        {
            donutdata.push({label: cat[i].category, value: cat[i].total});
        }
    }


    Morris.Donut({
        element: 'categorychart',
        data: donutdata,
        colors: ['#f26c4f', '#00a651', '#00bff3', '#0072bc']
    });



});
</script>
@stop