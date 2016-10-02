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


</head>
<body class="page-body" data-url="">

<div class="container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->


    <div class="main-content">


        @yield('content')


    </div>


    @include('layouts.default.partials._footerlinks')

</div>


</body>
</html>