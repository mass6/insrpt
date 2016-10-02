<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />

    <title>{{ isset($title)?$title:'Insight' }}</title>


    @include('layouts.default.partials._links')
    <link rel="stylesheet" href="{{ URL::asset('css/skins/blue.css') }}">
</head>
<body class="page-body" data-url="">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    @include('layouts.default.partials._sidebar')
    <div class="main-content">

        @include('layouts.default.partials._topbar')
        <!--@include('layouts.default.partials._breadcrumbs')-->
        @include('flash::message')

        @yield('content')

        <!-- Footer -->
        @include('layouts.default.partials._footer')

    </div>


</body>
</html>