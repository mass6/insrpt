@extends($layout)

@section('links')
@parent
<link rel="stylesheet" href="{{ URL::asset('css/font-icons/font-awesome/css/font-awesome.min.css') }}">
@stop

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <h1>Executive Dashboard <small class="pull-right">{{ Carbon::now()->format('d/m/Y') }}</small></h1>
        </div>
    </div>
</div>

<div class="row">

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
        <a href="{{ route('portal.orders.period', 'this-month') }}">
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
        <a href="{{ route('portal.orders.period', 'third-party-this-month') }}">
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

<br />

<div id="permission_notice" class="alert alert-danger" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p>You don't have permission to view orders.
</div>

<br/>
<div id="spend-analysis" class="page-header">
    <h3>Spend Analysis</h3>
</div>
<div class="row">
    <div class="col-sm-8">

        <div class="panel panel-primary" id="charts_env">

            <div class="panel-heading">
                <div class="panel-title">Last 90 Days - Daily Order Totals</div>

                <div class="panel-options">
                    <ul class="nav nav-tabs">
                        <!--                        <li class=""><a href="#area-chart" data-toggle="tab">By Cateory</a></li>-->
                        <li class="active"><a href="#line-chart" data-toggle="tab">By Order Date</a></li>
                        <!--                        <li class=""><a href="#pie-chart" data-toggle="tab">Pie Chart</a></li>-->
                    </ul>
                </div>
            </div>

            <div class="panel-body">

                <div class="tab-content">

                    <div class="tab-pane" id="area-chart">
                        <div id="area-chart-demo" class="morrischart" style="height: 300px"></div>
                    </div>

                    <div class="tab-pane active" id="bar_parent">
                        <div id="bar-chart" class="morrischart" style="height: 300px"></div>
                    </div>

                    <!--                    <div class="tab-pane" id="pie-chart">-->
                    <!--                        <div id="donut-chart-demo" class="morrischart" style="height: 300px;"></div>-->
                    <!--                    </div>-->

                </div>

            </div>



        </div>

    </div>
</div>

<br/>
<div id="products_ordered_stat" class="page-header">
    <h3>Reports</h3>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-8">
            @if ($currentUser->hasAccess('portal.orders.products-ordered*'))
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Top 10 products ordered last three months</h4>
                    </div>
                    <div class="panel-options">
                        <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                    </div>
                </div>
                <div class="panel-body no-padding">
                    <a href="{{ $productsOrderedReportUrl }}"><div id="products_ordered_chart"></div></a>
                </div>
            </div>
            @endif
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>
                            Spend by Category
                            <br />
                            <small>current month to date</small>
                        </h4>
                    </div>
                    <div class="panel-options">
                        <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        <a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
                    </div>
                </div>
                <div class="panel-body no-padding">
                    <div id="categorychart" style="height: 250px"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($)
{
    // set page-in animation class
    //document.body.className += ' ' + 'page-left-in';
    // Dashboard Panels

//    document.getElementById('orders-today-count').setAttribute('data-end', Insight.ordersTodayCount);
//    document.getElementById('orders-today-value').setAttribute('data-end', Insight.ordersTodayValue);
    document.getElementById('pending-approval-count').setAttribute('data-end', Insight.pendingApprovalCount);
    document.getElementById('pending-approval-value').setAttribute('data-end', Insight.pendingApprovalValue);
    document.getElementById('monthly-order-count').setAttribute('data-end', Insight.monthlyOrderCount);
    document.getElementById('monthly-order-value').setAttribute('data-end', Insight.monthlyOrderValue);
@if($currentUser->hasAccess('portal.orders.thirdparty'))
    document.getElementById('third-party-order-count').setAttribute('data-end', Insight.thirdPartyOrderCount);
    document.getElementById('third-party-order-value').setAttribute('data-end', Insight.thirdPartyOrderValue);
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

    // products ordered last month donut chart
    {{--@if ($currentUser->hasAccess('portal.orders.products-ordered*'))--}}
//        var products = Insight.productsOrderedLastMonth;
//        if (products.length){
//            var donutdata = [];
//            for (var i = 0; i < products.length; i++)
//            {
//                donutdata.push({label: products[i].name, value: products[i].qty});
//            }
//        }
//
//        Morris.Donut({
//            element: 'products_ordered_chart',
//            data: donutdata,
//            colors: ['#f26c4f', '#00a651', '#00bff3', '#0072bc']
//        });
    {{--@endif--}}


    // Area Chart
    var area_chart_demo = $("#area-chart-demo");

    area_chart_demo.parent().show();

    var area_chart = Morris.Area({
        element: 'area-chart-demo',
        data: [
            { y: '2014-01', a: 550, b: 110 },
            { y: '2014-02', a: 495,  b: 135 },
            { y: '2014-03', a: 750,  b: 120 },
            { y: '2014-04', a: 635,  b: 120 },
            { y: '2014-05', a: 705,  b: 85 },
            { y: '2014-06', a: 790,  b: 105 }
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        lineColors: ['#303641', '#576277']
    });

    area_chart_demo.parent().attr('style', '');

});


function getRandomInt(min, max)
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
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
                height: 400,
                legend: 'none',
                chartArea: {width: '85%', height: '90%'},
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
@stop

@section('bottomlinks')
@parent
<script src="{{ URL::asset('js/jquery.sparkline.min.js') }}"></script>
<script src="{{ URL::asset('js/raphael-min.js') }}"></script>
<script src="{{ URL::asset('js/morris.min.js') }}"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("visualization", "1", {packages: ["corechart"], callback: drawCharts})</script>
@stop