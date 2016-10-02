<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>{{ isset($title)?$title:'Insight Reporting' }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="Insight Client Management Portal" name="description"/>
<meta content="36S" name="author"/>

    {{-- CSS Styles and Reference Links --}}
    @include('layouts.horizontal.partials._links')

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-header-menu-fixed">
<!-- Begin Google Analytics -->
@include('common._google-analytics')
<!-- end Google Analytics -->

<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				{{--<a href="index.html"><img src="metronic/assets/admin/layout3/img/logo-default.png" alt="logo" class="logo-default"></a>--}}
                <a href="{{ route('home') }}">
                    <img src="{{ URL::asset('/images/insight_logo_sidebar.png') }}" width="118" alt="Insight Reporting" class="logo-default" />
                </a>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->

            @include('layouts.horizontal.partials._top-menu')

		</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu">
		<div class="container">
			<!-- BEGIN HEADER SEARCH BOX -->
			{{ Form::open(['route'=>'portal.orders.search', 'method'=>'GET', 'class' => 'search-form', 'role' => 'form' ]) }}
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search web orders" name="s">
					<span class="input-group-btn">
					<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
					</span>
				</div>
			{{ Form::close() }}
			<!-- END HEADER SEARCH BOX -->

            @include('layouts.horizontal.partials._mega-menu')


		</div>
	</div>
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	@yield('page-head')
	{{--<div class="page-head">--}}
		{{--<div class="container">--}}
			{{--<!-- BEGIN PAGE TITLE -->--}}
			{{--<div class="page-title">--}}
				{{--<h1>Blank Page Layout <small>blank page sample</small></h1>--}}
			{{--</div>--}}
			{{--<!-- END PAGE TITLE -->--}}

            {{--@include('layouts.horizontal.partials._toolbar')--}}

		{{--</div>--}}
	{{--</div>--}}
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			@include('layouts.horizontal.partials._breadcrumbs')
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row">
				<div class="col-md-12">
					<div id="alert-wrapper">
						@include('flash::message')
					</div>
                    @yield('content')
				</div>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->

    @include('layouts.horizontal.partials._pre-footer')

    @include('layouts.horizontal.partials._footer')

    @yield('subfooter')

    @include('layouts.horizontal.partials._js')


</body>
<!-- END BODY -->
</html>