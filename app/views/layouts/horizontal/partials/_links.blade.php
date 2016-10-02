@section('links')

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    {{--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">--}}
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
    {{--<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>--}}
    <link href="{{ asset('metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic/assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="{{ asset('metronic/assets/global/css/components-rounded.css') }}" id="style_components" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic/assets/global/css/plugins.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic/assets/admin/layout3/css/layout.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic/assets/admin/layout3/css/themes/blue-steel.css') }}" rel="stylesheet" type="text/css" id="style_color">
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

@show

@section('style-overrides')
    <link href="{{ asset('metronic/assets/admin/layout3/css/custom.css') }}" rel="stylesheet" type="text/css">

@show