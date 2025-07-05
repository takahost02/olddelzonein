<!DOCTYPE html>
<html>
    <!--
    @copyright

  Fleet Manager v7.0

  Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
  Design and developed by Hyvikk Solutions <https://hyvikk.com/>  -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ trans('installer_messages.title') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo-40.png') }}"  type="icon_img">
    <link rel="icon"  href="{{ asset('assets/images/logo-40.png') }}" type="icon_img">
    <link rel="icon"  href="{{ asset('assets/images/logo-40.png') }}" type="icon_img">


<meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('assets/css/installer-style.min.css') }}" rel="stylesheet"/>
    @yield('style')

</head>
<body>
<div class="master">
    <div class="box" style="width: 50% !important;">
        <div class="header">
            <img src="{{ asset('/assets/images/logo.png') }}" height="55px" alt="">
            <h1 class="header__title">@yield('title')</h1>
        </div>

        <div class="main">
            @yield('container')
        </div>
    </div>
</div>
</body>
@yield('scripts')
</html>
