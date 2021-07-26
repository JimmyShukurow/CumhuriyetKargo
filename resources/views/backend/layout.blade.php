<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!-- This Page Designed By Niko -->
<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!doctype html>
<html lang="tr">
@php $user = GetLayoutInformaiton() @endphp

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="tr">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CKG-Sis - @yield('title')</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <meta name="description" content="Cumhuriyet Kargo Online Portalı.">
    <meta name="author" content="CKG-Team">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="/backend//assets/css/main.8d288f825d8dffbbe55e.css" rel="stylesheet">
    <link rel="icon" href="/backend/assets/images/ck-ico-white.png" type="image/x-icon"/>
    <link rel="stylesheet" href="/backend/assets/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="/backend/assets/css/datatables.min.css">
    <link href="/backend/assets/css/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/backend/assets/css/custom.css?v=1">
    <script src="/backend/assets/scripts/jquery.js"></script>
    <script src="/backend/assets/scripts/sweetalert.js"></script>
    <script src="/backend/assets/scripts/general-up.js"></script>

    {{--PWA START--}}
    <link rel="manifest" href="/backend/assets/scripts/pwa/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="prototurk.com">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)"
          rel="apple-touch-startup-image"/>
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)"
          rel="apple-touch-startup-image"/>
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/backend/assets/images/ck-ico-white.png"
          media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link rel="apple-touch-icon" sizes="128x128" href="/backend/assets/images/ck-ico-white.png">
    <link rel="apple-touch-icon-precomposed" sizes="128x128" href="/backend/assets/images/ck-ico-white.png">
    <link rel="icon" sizes="192x192" href="/backend/assets/images/ck-ico-white.png">
    <link rel="icon" sizes="128x128" href="/backend/assets/images/ck-ico-white.png">
    {{--PWA END--}}
    <script src="/backend/assets/scripts/service-worker.js"></script>



    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/backend/assets/scripts/service-worker.js?v=3');
            });
        }
    </script>


    @stack('css')

</head>

<body>
<div id="appContainer" class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    {{-- $$$ Awsome Header ;) $$$ --}}
    <div class="app-header header-shadow bg-asteroid header-text-light">
        <div class="app-header__logo">
            <div style="margin-bottom: 15px" class="logo-src">
                <a href="{{route(getUserFirstPage())}}">
                    <img id="main-logo" style="width: 10rem;" src="/backend/assets/images/ck-logo-white.png" alt="">
                </a>
            </div>
            {{--id="panelMlAuto" --}}
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
                <span>
                    <button type="button"
                            class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
        </div>
        <div class="app-header__content">
            <div class="app-header-left">
                <div class="search-wrapper">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Type to search">
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
            </div>
            <div class="app-header-right">

                <div class="header-dots">
                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
                                class="p-0 mr-2 btn btn-link">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-primary"></span>
                                    <i class="icon text-primary ion-android-apps"></i>
                                </span>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-plum-plate">
                                    <div class="menu-header-image"
                                         style="background-image: url('assets/images/dropdown-header/abstract4.jpg');">
                                    </div>
                                    <div class="menu-header-content text-white">
                                        <h5 class="menu-header-title">Grid Dashboard</h5>
                                        <h6 class="menu-header-subtitle">Easy grid navigation inside dropdowns</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="grid-menu grid-menu-xl grid-menu-3col">
                                <div class="no-gutters row">
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                            class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                            <i
                                                class="pe-7s-world icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3"></i>
                                            Automation
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                            class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                            <i
                                                class="pe-7s-piggy icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i>
                                            Reports
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                            class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                            <i
                                                class="pe-7s-config icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i>
                                            Settings
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                            class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                            <i
                                                class="pe-7s-browser icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i>
                                            Content
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                            class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                            <i
                                                class="pe-7s-hourglass icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i>
                                            Activity
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                            class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                            <i
                                                class="pe-7s-world icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i>
                                            Contacts
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav flex-column">
                                <li class="nav-item-divider nav-item"></li>
                                <li class="nav-item-btn text-center nav-item">
                                    <button class="btn-shadow btn btn-primary btn-sm">Follow-ups</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
                                class="p-0 mr-2 btn btn-link">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-danger"></span>
                                    <i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>
                                </span>
                            @if(Auth::user()->unReadNotifications->count())
                                <span id="BadgeNotificationQuantity" style="position: absolute; padding: 4px 4px;"
                                      class="badge badge-danger">
                                    {{Auth::user()->unReadNotifications->count()}}
                                </span>
                            @endif

                        </button>
                        {{-- Notification--}}
                        @include('backend.personel.notification')
                    </div>
                    <div class="dropdown">
                        <button type="button" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-focus"></span>
                                    <span class="language-icon opacity-8 flag large TR"></span>
                                </span>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false"
                                class="p-0 btn btn-link dd-chart-btn">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-success"></span>
                                    <i class="icon text-success ion-ios-analytics"></i>
                                </span>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-premium-dark">
                                    <div class="menu-header-image"
                                         style="background-image: url('assets/images/dropdown-header/abstract4.jpg');">
                                    </div>
                                    <div class="menu-header-content text-white">
                                        <h5 class="menu-header-title">Users Online
                                        </h5>
                                        <h6 class="menu-header-subtitle">Recent Account Activity Overview
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-chart">
                                <div class="widget-chart-content">
                                    <div class="icon-wrapper rounded-circle">
                                        <div class="icon-wrapper-bg opacity-9 bg-focus">
                                        </div>
                                        <i class="lnr-users text-white">
                                        </i>
                                    </div>
                                    <div class="widget-numbers">
                                        <span>344k</span>
                                    </div>
                                    <div class="widget-subheading pt-2">
                                        Profile views since last login
                                    </div>
                                    <div class="widget-description text-danger">
                                            <span class="pr-1">
                                                <span>176%</span>
                                            </span>
                                        <i class="fa fa-arrow-left"></i>
                                    </div>
                                </div>
                                <div class="widget-chart-wrapper">
                                    <div id="dashboard-sparkline-carousel-3-pop"></div>
                                </div>
                            </div>
                            <ul class="nav flex-column">
                                <li class="nav-item-divider mt-0 nav-item">
                                </li>
                                <li class="nav-item-btn text-center nav-item">
                                    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm">
                                        <i class="fa fa-cog fa-spin mr-2">
                                        </i>
                                        View Details
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                       class="p-0 btn">
                                        <img id="first-profile-avatar" width="42" class="rounded-circle"
                                             src="/backend/assets/images/ck-ico-white.png" alt="">
                                        <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2"
                                                     style="background-image: url('/backend/assets/images/dropdown-header/city3.jpg');">
                                                </div>
                                                <div class="menu-header-content text-left">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-3">
                                                                <img width="42" class="rounded-circle"
                                                                     src="/backend/assets/images/ck-ico-white.png"
                                                                     alt="">
                                                            </div>
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">
                                                                    {{ Auth::user()->name_surname }}
                                                                </div>
                                                                <div class="widget-subheading opacity-8">
                                                                    {{ $user['role']->display_name }}
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right mr-2">
                                                                <a href="{{ route('admin.Logout') }}">
                                                                    <button
                                                                        class="btn-pill btn-shadow btn-shine btn btn-focus">
                                                                        Çıkış Yap
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="scroll-area-xs" style="height: 150px;">
                                            <div class="scrollbar-container ps">
                                                <ul class="nav flex-column">

                                                    <li style="text-transform: capitalize"
                                                        class="nav-item-header nav-item">Hesabım
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{route('personel.AccountSettings')}}"
                                                           class="nav-link">Hesap
                                                            Ayarlarım
                                                            <div class="ml-auto badge badge-success">New</div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Mesajlar
                                                            <div class="ml-auto badge badge-warning">512
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{route('personel.LastLogs')}}" class="nav-link">Hesap
                                                            Harektlerim
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider mb-0 nav-item"></li>
                                        </ul>
                                        <div class="grid-menu grid-menu-2col">
                                            <div class="no-gutters row">

                                                <div class="col-sm-12">
                                                    <button
                                                        class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i
                                                            class="pe-7s-ticket icon-gradient bg-love-kiss btn-icon-wrapper mb-2"></i>
                                                        <b>Destek & Tickets</b>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading">
                                    {{ Auth::user()->name_surname }}
                                </div>
                                <div class="widget-subheading"> {{ $user['role']->display_name }} </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{--THEME SECTION--}}
    @yield("theme-settings")

    <div class="app-main">
        {{-- HERE THE AWSOME HEADER :) --}}
        <div class="app-sidebar sidebar-shadow bg-asteroid sidebar-text-light">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                    <span>
                        <button type="button"
                                class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
            </div>

            <div class="scrollbar-sidebar">
                <div id="SideBar" class="app-sidebar__inner">
                    <ul class="vertical-nav-menu">
                        @php $module = GetTitles()  @endphp
                        @php $firstURL = get_just_first_url()  @endphp

                        @foreach ($module as $title)
                            <li class="app-sidebar__heading">{{ $title->title }}</li>

                            @php $module_names = GetModuleNames($title->title)@endphp
                            @php $index = $loop->index @endphp


                            @foreach ($module_names as $mname)

                                @php $index2 = $loop->index @endphp


                                <li
                                    class="{{ set_module_active($mname->id, $firstURL, 'mm-active') }} big-parent">

                                    <a href="#">
                                        <i class="metismenu-icon {{ $mname->ico }}"></i>
                                        {{ $mname->module_name }}
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>

                                    @php $sub_module_names = GetModulesAllInfo($mname->module_name)  @endphp
                                    <ul>
                                        @foreach ($sub_module_names as $subname)
                                            <li style="display: none;" class="sub-items">
                                                <a href="{{ route("$subname->link") }}"
                                                   class="{{ is_active($subname->link, 'mm-active') }}">
                                                    <i class="metismenu-icon">
                                                    </i>{{ $subname->sub_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

        <div class="app-main__outer">

            {{-- HEADER END --}}

            @yield('content')

            {{-- FOOTER START --}}

            <div class="app-wrapper-footer">
                <div class="app-footer">
                    <div class="app-footer__inner">
                        <div class="app-footer-left">
                            <div class="footer-dots">
                                <div class="dropdown">
                                    <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
                                       class="dot-btn-wrapper">
                                        <i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
                                        <div class="badge badge-dot badge-abs badge-dot-sm badge-danger">
                                            Notifications
                                        </div>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="dropdown-menu-xl rm-pointers dropdown-menu">
                                        <div class="dropdown-menu-header mb-0">
                                            <div class="dropdown-menu-header-inner bg-deep-blue">
                                                <div class="menu-header-image opacity-1"
                                                     style="background-image: url('/backend/assets/images/dropdown-header/city3.jpg');">
                                                </div>
                                                <div class="menu-header-content text-dark">
                                                    <h5 class="menu-header-title">Notifications</h5>
                                                    <h6 class="menu-header-subtitle">You have <b>21</b> unread
                                                        messages</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <ul
                                            class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                            <li class="nav-item">
                                                <a role="tab" class="nav-link active" data-toggle="tab"
                                                   href="#tab-messages-header1">
                                                    <span>Messages</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a role="tab" class="nav-link" data-toggle="tab"
                                                   href="#tab-events-header1">
                                                    <span>Events</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a role="tab" class="nav-link" data-toggle="tab"
                                                   href="#tab-errors-header1">
                                                    <span>System Errors</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab-messages-header1" role="tabpanel">
                                                <div class="scroll-area-sm">
                                                    <div class="scrollbar-container">
                                                        <div class="p-3">
                                                            <div class="notifications-box">
                                                                <div
                                                                    class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                                    <div
                                                                        class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">All Hands
                                                                                    Meeting</h4><span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <p>Yet another one, at <span
                                                                                        class="text-success">15:00
                                                                                            PM</span></p><span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-success vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">Build the
                                                                                    production release
                                                                                    <span
                                                                                        class="badge badge-danger ml-2">NEW</span>
                                                                                </h4>
                                                                                <span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-primary vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">Something
                                                                                    not important
                                                                                    <div
                                                                                        class="avatar-wrapper mt-2 avatar-wrapper-overlap">
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/1.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/2.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/3.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/4.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/5.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/9.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/7.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <img
                                                                                                    src="/backend/assets/images/avatars/8.jpg"
                                                                                                    alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="avatar-icon-wrapper avatar-icon-sm avatar-icon-add">
                                                                                            <div
                                                                                                class="avatar-icon">
                                                                                                <i>+</i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </h4>
                                                                                <span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-info vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">This dot
                                                                                    has an info state</h4><span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">All Hands
                                                                                    Meeting</h4><span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <p>Yet another one, at <span
                                                                                        class="text-success">15:00
                                                                                            PM</span></p><span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-success vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">Build the
                                                                                    production release
                                                                                    <span
                                                                                        class="badge badge-danger ml-2">NEW</span>
                                                                                </h4>
                                                                                <span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="vertical-timeline-item dot-dark vertical-timeline-element">
                                                                        <div><span
                                                                                class="vertical-timeline-element-icon bounce-in"></span>
                                                                            <div
                                                                                class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">This dot
                                                                                    has a dark state</h4><span
                                                                                    class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-events-header1" role="tabpanel">
                                                <div class="scroll-area-sm">
                                                    <div class="scrollbar-container">
                                                        <div class="p-3">
                                                            <div
                                                                class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-success">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">All Hands
                                                                                Meeting</h4>
                                                                            <p>Lorem ipsum dolor sic amet, today at
                                                                                <a href="javascript:void(0)">12:00
                                                                                    PM</a>
                                                                            </p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-warning">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <p>Another meeting today, at <b
                                                                                    class="text-danger">12:00 PM</b>
                                                                            </p>
                                                                            <p>Yet another one, at <span
                                                                                    class="text-success">15:00
                                                                                        PM</span></p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-danger">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">Build the
                                                                                production release</h4>
                                                                            <p>Lorem ipsum dolor sit
                                                                                amit,consectetur eiusmdd tempor
                                                                                incididunt ut labore et dolore magna
                                                                                elit enim at minim veniam quis
                                                                                nostrud</p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-primary">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title text-success">
                                                                                Something not important</h4>
                                                                            <p>Lorem ipsum dolor sit
                                                                                amit,consectetur elit enim at minim
                                                                                veniam quis nostrud</p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-success">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">All Hands
                                                                                Meeting</h4>
                                                                            <p>Lorem ipsum dolor sic amet, today at
                                                                                <a href="javascript:void(0);">12:00
                                                                                    PM</a>
                                                                            </p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-warning">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <p>Another meeting today, at <b
                                                                                    class="text-danger">12:00 PM</b>
                                                                            </p>
                                                                            <p>Yet another one, at <span
                                                                                    class="text-success">15:00
                                                                                        PM</span></p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-danger">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title">Build the
                                                                                production release</h4>
                                                                            <p>Lorem ipsum dolor sit
                                                                                amit,consectetur eiusmdd tempor
                                                                                incididunt ut labore et dolore magna
                                                                                elit enim at minim veniam quis
                                                                                nostrud</p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span
                                                                            class="vertical-timeline-element-icon bounce-in"><i
                                                                                class="badge badge-dot badge-dot-xl badge-primary">
                                                                                </i></span>
                                                                        <div
                                                                            class="vertical-timeline-element-content bounce-in">
                                                                            <h4 class="timeline-title text-success">
                                                                                Something not important</h4>
                                                                            <p>Lorem ipsum dolor sit
                                                                                amit,consectetur elit enim at minim
                                                                                veniam quis nostrud</p><span
                                                                                class="vertical-timeline-element-date"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-errors-header1" role="tabpanel">
                                                <div class="scroll-area-sm">
                                                    <div class="scrollbar-container">
                                                        <div class="no-results pt-3 pb-0">
                                                            <div
                                                                class="swal2-icon swal2-success swal2-animate-success-icon">
                                                                <div class="swal2-success-circular-line-left"
                                                                     style="background-color: rgb(255, 255, 255);">
                                                                </div>
                                                                <span class="swal2-success-line-tip"></span>
                                                                <span class="swal2-success-line-long"></span>
                                                                <div class="swal2-success-ring"></div>
                                                                <div class="swal2-success-fix"
                                                                     style="background-color: rgb(255, 255, 255);">
                                                                </div>
                                                                <div class="swal2-success-circular-line-right"
                                                                     style="background-color: rgb(255, 255, 255);">
                                                                </div>
                                                            </div>
                                                            <div class="results-subtitle">All caught up!</div>
                                                            <div class="results-title">There are no system errors!
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider nav-item"></li>
                                            <li class="nav-item-btn text-center nav-item">
                                                <button
                                                    class="btn-shadow btn-wide btn-pill btn btn-focus btn-sm">View
                                                    Latest Changes
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dots-separator"></div>
                                <div class="dropdown">
                                    <a class="dot-btn-wrapper" aria-haspopup="true" data-toggle="dropdown"
                                       aria-expanded="false">
                                        <i class="dot-btn-icon lnr-earth icon-gradient bg-happy-itmeo">
                                        </i>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="rm-pointers dropdown-menu">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner pt-4 pb-4 bg-focus">
                                                <div class="menu-header-image opacity-05"
                                                     style="background-image: url('/backend/assets/images/dropdown-header/city2.jpg');">
                                                </div>
                                                <div class="menu-header-content text-center text-white">
                                                    <h6 class="menu-header-subtitle mt-0">
                                                        Sistem Dilini Seçin
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 tabindex="-1" class="dropdown-header">
                                            POPÜLER DİLLER
                                        </h6>
                                        <button type="button" tabindex="0" class="dropdown-item">
                                            <span class="mr-3 opacity-8 flag large US"></span>
                                            English
                                            <div class="ml-2 badge badge-pill badge-primary">Yakında</div>
                                        </button>


                                        <div tabindex="-1" class="dropdown-divider"></div>
                                        <h6 tabindex="-1" class="dropdown-header">GEÇERLİ DİL</h6>
                                        <button type="button" tabindex="0" class="dropdown-item active">
                                            <span class="mr-3 opacity-8 flag large TR"></span>
                                            Türkçe
                                        </button>
                                    </div>
                                </div>
                                <div class="dots-separator"></div>
                                <div class="dropdown">
                                    <a class="dot-btn-wrapper dd-chart-btn-2" aria-haspopup="true"
                                       data-toggle="dropdown" aria-expanded="false">
                                        <i class="dot-btn-icon lnr-pie-chart icon-gradient bg-love-kiss"></i>
                                        <div class="badge badge-dot badge-abs badge-dot-sm badge-warning">
                                            Notifications
                                        </div>
                                    </a>
                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                         class="dropdown-menu-xl rm-pointers dropdown-menu">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-premium-dark">
                                                <div class="menu-header-image"
                                                     style="background-image: url('/backend/assets/images/dropdown-header/abstract4.jpg');">
                                                </div>
                                                <div class="menu-header-content text-white">
                                                    <h5 class="menu-header-title">Users Online
                                                    </h5>
                                                    <h6 class="menu-header-subtitle">Recent Account Activity
                                                        Overview
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-chart">
                                            <div class="widget-chart-content">
                                                <div class="icon-wrapper rounded-circle">
                                                    <div class="icon-wrapper-bg opacity-9 bg-focus">
                                                    </div>
                                                    <i class="lnr-users text-white">
                                                    </i>
                                                </div>
                                                <div class="widget-numbers">
                                                    <span>344k</span>
                                                </div>
                                                <div class="widget-subheading pt-2">
                                                    Profile views since last login
                                                </div>
                                                <div class="widget-description text-danger">
                                                        <span class="pr-1">
                                                            <span>176%</span>
                                                        </span>
                                                    <i class="fa fa-arrow-left"></i>
                                                </div>
                                            </div>
                                            <div class="widget-chart-wrapper">
                                                <div id="dashboard-sparkline-carousel-4-pop"></div>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider mt-0 nav-item">
                                            </li>
                                            <li class="nav-item-btn text-center nav-item">
                                                <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm">
                                                    <i class="fa fa-cog fa-spin mr-2">
                                                    </i>
                                                    View Details
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="app-footer-right">
                            <ul class="header-megamenu nav">
                                <li class="nav-item">
                                    <a href="/SystemUpdates"
                                       class="nav-link">
                                        <b>CKG-Sis V{{getSystemVersion()}}</b>
                                        <div style="text-transform: none ;" class="badge bg-ck ml-0 ml-1">
                                            <small>Powered By CKG-Team</small>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="app-drawer-overlay d-none animated fadeIn"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/backend/assets/scripts/toastr.js"></script>
<script>
    @if (Session::has('error'))
    ToastMessage('error', '{{ session('error') }}', 'Hata!');
    @elseif(Session::has('success'))
    ToastMessage('success', '{{ session('success') }}', 'İşlem Başarılı!');
    @endif

    @foreach ($errors->all() as $error)
    ToastMessage('error', '{{ $error }}', 'Hata!');
    @endforeach
</script>


</body>
</html>

@yield('js')

<script>

    // var vid = document.getElementById("bildirimSes");
    // vid.muted = true;
    // vid.play();
    // vid.muted = false;
    // vid.play();

    function sendMarkRequest(id = null) {
        return $.ajax("{{route('personel.markAsRead')}}", {
            method: 'POST',
            data: {
                _token: token,
                id: id
            }
        });
    }

    $(function () {

        $('.mark-as-read').click(function () {
            var id = $(this).data('id');
            let request = sendMarkRequest(id);

            request.done((response) => {
                $('#markReadBtn-' + id).remove();
                $('#NotfContent-' + id + ', #aNotfContent-' + id).removeClass('text-primary').addClass('text-dark');
                $('#NotfHumenTime-' + id + ', #spanNotfHumenTime-' + id + ', .date-of-notf-' + id)
                    .removeClass('text-primary').addClass('text-dark');
                $('#markRead-' + id).removeClass('mark-as-read').removeClass('mark-as-read2').removeClass('fake-link').removeClass('text-primary').addClass('text-dark');
                $('#NotfDot-' + id).removeClass('dot-primary').addClass('dot-dark');
                $('#BadgeNotificationQuantity, #TitleNotificationQuantity, #spanNotificationQuantity').html(response)
            });
        });
        $('html').on('click', '.mark-as-read2', function () {
            var id = $(this).data('id');
            let request = sendMarkRequest(id);

            request.done((response) => {
                $('#markReadBtn-' + id).remove();
                $('#NotfContent-' + id + ', #aNotfContent-' + id).removeClass('text-primary').addClass('text-dark');
                $('#NotfHumenTime-' + id + ', #spanNotfHumenTime-' + id + ', .date-of-notf-' + id)
                    .removeClass('text-primary').addClass('text-dark');
                $('#markRead-' + id).removeClass('mark-as-read').removeClass('mark-as-read2').removeClass('fake-link').removeClass('text-primary').addClass('text-dark');
                $('#NotfDot-' + id).removeClass('dot-primary').addClass('dot-dark');
                $('#BadgeNotificationQuantity, #TitleNotificationQuantity, #spanNotificationQuantity').html(response)
            });
        });

        $('.markAllRead').click(function () {
            $('.markAllRead').remove();
            var id = null;
            let request = sendMarkRequest(id);

            request.done((response) => {
                $('div.mark-as-read').remove();
                $('.NotfContent').removeClass('text-primary').addClass('text-dark');
                $('.NotfHumenTime').removeClass('text-primary').addClass('text-dark');
                $('.NotfDot').removeClass('dot-primary').addClass('dot-dark');
                $('.fake-link').removeClass('mark-as-read').removeClass('mark-as-read2').removeClass('fake-link').removeClass('text-primary').addClass('text-dark');
                $('#BadgeNotificationQuantity, #TitleNotificationQuantity, #spanNotificationQuantity').html(response)
            });
        });
    });

    // function play() {
    //     var audio = new Audio('/backend/assets/notification.mp3');
    //     audio.muted = true;
    //     audio.play();
    //     audio.muted = false;
    //     audio.play();
    // }
    //
    // document.body.addEventListener("load", function () {
    //     play();
    // })

</script>

@if(!isset($notBootstrap))
    <script src="/backend/assets/scripts/bootstrap.min.js"></script>
@endif
<script src="/backend/assets/scripts/popper.min.js"></script>
<script src="/backend/assets/scripts/general.js"></script>
<script type="text/javascript" src="/backend/assets/scripts/main.8d288f825d8dffbbe55e.js"></script>
<script>
    @if(\Illuminate\Support\Facades\Session::exists('virtual-login'))
    $(document).on('click', '#virtualCloseBtn', function () {
        confirmBox("Sanal Giriş Sonlandırılacak devam edilsin mi?", "", "{{route("closeTheVirtualLogin", Crypte4x(\Illuminate\Support\Facades\Session::get('virtual-login')))}}")
    });

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "2300",
        "hideDuration": "1000",
        "timeOut": "999999",
        "extendedTimeOut": "0",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr["warning"]('Hesaba sanal giriş yaptınız. Çıkmak için butona tıklayın.</br></br> <button id="virtualCloseBtn" type="button" onclick="" class="btn btn-primary ">Sanal Oturumu Kapat</button>');
    @endif
</script>

@yield('modals')

<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!-- This Page Designed By Niko -->
<!-- =========>>>>>>>>><<<<<<<<<========= -->
<!-- =========>>>>>>>>><<<<<<<<<========= -->
