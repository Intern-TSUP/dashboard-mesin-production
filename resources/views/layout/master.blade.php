<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href=""/>
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Formulir Monitoring Ruangan. Pantau Kondisi Suhu, RH, DP">
    <meta name="keywords" content="Monitoring, TSUP, MSTD, Kalbe, Kalbe-Farma, OneKalbe">
    <meta name="author" content="Ferdy Rahmat">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <link rel="shortcut icon" href="{{ asset('assets/logo/logo_only.png') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="{{ asset('assets/css/inter_font_api.css') }}" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    @yield('head')
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
    @stack('styles')  
</head>
<!--end::Head-->

<!--begin::Body-->
<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default"
    data-kt-app-sidebar-minimize="on"
    style="background-image: url('/assets/img/bglineB.svg'); background-repeat: repeat-y; background-size: cover;">

    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }

    </script>
    <!--end::Theme mode setup on page load-->

    <!--begin::Page loading(append to body)-->
    <div class="page-loading">
        <div class="page-loader flex-column bg-dark bg-opacity-25">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
        </div>
    </div>
    <!--end::Page loading-->

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
                data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
                data-kt-sticky-animation="false">

                <!--begin::Header container-->
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
                    id="kt_app_header_container">
                    <!--begin::Sidebar mobile toggle-->
                    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-abstract-14 fs-2 fs-md-1"></i>
                        </div>
                    </div>
                    <!--end::Sidebar mobile toggle-->
                    <!--begin::Mobile logo-->
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a class="d-lg-none">
                            <img alt="Logo" src="{{asset('assets/logo/kalbe_farma.png')}}" class="h-30px" />
                        </a>
                    </div>
                    <!--end::Mobile logo-->
                    <!--begin::Header wrapper-->
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                        <!--begin::Menu wrapper-->
                        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                            data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                            data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
                            data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                            data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                            data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                            <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                                id="kt_app_header_menu" data-kt-menu="true">
                                <!--begin:Menu item-->
                                <div class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
                                    <!--begin:Menu link-->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            @php
                                                $maintenance = App\Models\MaintenanceMode::first();
                                            @endphp
                                            @if ($maintenance->maintenance == true)
                                                <button id='btnMt' class="btn btn-light-danger btn-sm fs-5" data-bs-toggle="tooltip" data-bs-placement="right" title="Maintenance Mode is Active!">
                                                    Maintenance
                                                </button>
                                            @endif
                                        </ol>
                                    </nav>
                                    <!--end:Menu link-->
                                </div>
                            </div>
                            <!--end:Menu link-->
                        </div>
                        <!--end::Menu wrapper-->
                        <!--begin::Navbar-->
                        <div class="app-navbar flex-shrink-0">
                            <div class="app-navbar-item ms-1 ms-md-4" id="idle_time_display">
                                <span id="idle_time" class="text-muted">Idle Time: 00:00</span>
                            </div>
                            <!--begin::Notifications-->
                            <div class="app-navbar-item ms-1 ms-md-4">
                                {{-- Notify --}}
                                <!--begin::Menu- wrapper-->
                                <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
                                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                    data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
                                    <i class="ki-outline ki-notification-status fs-2"></i>
                                    @if(auth()->user()->notify()->count() > 0)
                                        <span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                                    @endif
                                </div>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-500px" data-kt-menu="true"
                                    id="kt_menu_notifications">
                                    <!--begin::Heading-->
                                    <div class="d-flex flex-column bgi-no-repeat rounded-top"
                                        style="background-image: url('/assets/media/misc/menu-header-bg.jpg'); background-size: cover; background-position: center;">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-stack p-5 my-2">
                                            <div class="d-flex">
                                                <h3 class="text-white fw-semibold px-9">Notifications
                                                    {{-- <span class="fs-8 opacity-75 ps-3">24 reports</span> --}}
                                                </h3>
                                            </div>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Tab content-->
                                    <div class="tab-content">
                                        <!--begin::Tab panel-->
                                        <div class="tab-pane fade show active" id="kt_topbar_notifications_3" role="tabpanel">
                                            <!--begin::Items-->
                                            <div class="scroll-y mh-300px my-5 px-8">
                                                {{-- Notify --}}
                                                @forelse (auth()->user()->notify() as $notif)
                                                    @php
                                                        // Cek apakah notifikasi dari hari ini
                                                        $isToday = Carbon\Carbon::parse($notif->created_at)->isToday();
                                                        // Format waktu relative seperti "2 min ago"
                                                        $relativeTime = Carbon\Carbon::parse($notif->created_at)->diffForHumans();
                                                    @endphp

                                                    <!--begin::Item-->
                                                    <div class="d-flex flex-stack">
                                                        <!--begin::Section-->
                                                        <div class="d-flex align-items-center">
                                                            <div class="card mb-2">
                                                                <div class="card-body">
                                                                    <!--begin::Label-->
                                                                    <span class="badge {{ $isToday ? 'badge-success' : 'badge-warning' }} fs-8 float-end">{{ $isToday ? 'Today' : 'Kemarin' }}</span>
                                                                    <!--end::Label-->
                                                                    <span class="text-sm text-muted">{{ $relativeTime }}</span>
                                                                    <h5 class="text-body mb-2 mt-2">{{ $notif->title }}</h5>
                                                                    <p class="mb-0">{{ $notif->message }}</p>
                                                                    <p class="mb-0">
                                                                        @if($notif->url)
                                                                            <a href="{{ $notif->url }}">
                                                                                <button class="btn btn-secondary btn-sm float-end">direct</button>
                                                                            </a>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Section-->
                                                    </div>
                                                    <!--end::Item-->
                                                @empty
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center w-100 justify-content-center">
                                                            <h6 class="text-body mb-2 mt-2 text-gray-600">Tidak Ada Notifikasi</h6>
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                            <!--end::Items-->
                                            <!--begin::View more-->
                                            <div class="py-1 text-center border-top">
                                                <button class="btn btn-color-gray-600 btn-active-color-danger w-100" onclick="clearNotif()">
                                                    Clear all notification
                                                </button>
                                            </div>
                                            <!--end::View more-->
                                        </div>
                                        <!--end::Tab panel-->
                                    </div>
                                    <!--end::Tab content-->
                                </div>
                                <!--end::Menu-->
                                <!--end::Menu wrapper-->
                            </div>
                            <!--end::Notifications-->

                            <!--begin::User menu-->
                            <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                                <div class="d-flex flex-column px-2 align-items-end">
                                    <div class="d-flex fs-6">
                                        {{auth()->user()->fullname}}
                                    </div>
                                    <div class="d-flex fs-8 fw-semibold">
                                        <span class="badge badge-light-info">{{auth()->user()->jobTitle}}</span>
                                    </div>
                                </div>
                                <!--begin::Menu wrapper-->
                                <div class="cursor-pointer symbol symbol-35px"
                                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                                    data-kt-menu-placement="bottom-end">
                                    <img src="/assets/media/avatars/avatar_gray.png" class="rounded-3" alt="user" />
                                </div>
                                <!--begin::User account menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-50px me-5">
                                                <img alt="Logo" src="/assets/media/avatars/avatar_gray.png" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Username-->
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold align-items-center fs-5">
                                                    {{auth()->user()->fullname}}
                                                </div>
                                                <small class="fw-semibold text-muted fs-7">{{ auth()->user()->email }}</small>
                                            </div>
                                            <!--end::Username-->
                                        </div>
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                        <a href="#" class="menu-link px-5">
                                            <span class="menu-title position-relative">Mode
                                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                                    <i class="ki-duotone ki-night-day theme-light-show fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                        <span class="path6"></span>
                                                        <span class="path7"></span>
                                                        <span class="path8"></span>
                                                        <span class="path9"></span>
                                                        <span class="path10"></span>
                                                    </i>
                                                    <i class="ki-duotone ki-moon theme-dark-show fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            </span>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                                    data-kt-value="light">
                                                    <span class="menu-icon" data-kt-element="icon">
                                                        <i class="ki-duotone ki-night-day fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                            <span class="path6"></span>
                                                            <span class="path7"></span>
                                                            <span class="path8"></span>
                                                            <span class="path9"></span>
                                                            <span class="path10"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">Light</span>
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                                    <span class="menu-icon" data-kt-element="icon">
                                                        <i class="ki-duotone ki-moon fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">Dark</span>
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                                    data-kt-value="system">
                                                    <span class="menu-icon" data-kt-element="icon">
                                                        <i class="ki-duotone ki-screen fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">System</span>
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Menu item-->

                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    {{-- <!--begin::Menu item-->
                                    <div class="menu-item px-5">
                                        <button onclick="changePassword()" class="btn menu-link w-100 px-5">
                                            <span class="menu-title position-relative">Change Password
                                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                                    <i class="ki-duotone ki-arrows-loop fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                    <!--end::Menu item--> --}}
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-5">
                                        <a href="{{route('logout')}}" class="menu-link px-5" onclick="showLoading()">Sign Out</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::User account menu-->
                                <!--end::Menu wrapper-->
                            </div>
                            <!--end::User menu-->

                            <!--begin::Header menu toggle-->
                            {{-- <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                                <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
                                    id="kt_app_header_menu_toggle">
                                    <i class="ki-outline ki-element-4 fs-1"></i>
                                </div>
                            </div> --}}
                            <!--end::Header menu toggle-->
                            <!--begin::Aside toggle-->
                            <!--end::Header menu toggle-->
                        </div>
                        <!--end::Navbar-->
                    </div>
                    <!--end::Header wrapper-->
                </div>
                <!--end::Header container-->
            </div>

            <script>
                function clearNotif()
                {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("user/clear-notifications") }}', // Update this route
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        cache: false,
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we process your request.',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            if (response.success == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Notifications Cleared',
                                    text: response.message,
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: true,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Something went wrong!',
                                    text: response.message
                                });
                            }
                        },
                    });
                }
            </script>
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                @include('layout.sidebar')
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                        @yield('page_title')
                                    </h1>
                                    <!--end::Title-->
                                    @yield('breadcrumb')
                                </div>
                                <!--end::Page title-->
                                <!--begin::Actions-->
                                <div class="d-flex align-items-center gap-2 gap-lg-3">
                                    @yield('action_button')
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Toolbar container-->
                        </div>
                        <!--end::Toolbar-->

                        @yield('main-content')
                    </div>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">
                        <!--begin::Footer container-->
                        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                            <!--begin::Copyright-->
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">2025&copy;</span>
                                <a href="" target="_blank" class="text-gray-800 text-hover-primary fs-6">Kalbe Farma &#9829; crafted by MSTD Team </a>
                            </div>
                            <!--end::Copyright-->
                            {{-- <!--begin::Menu-->
                            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                                <li class="menu-item">
                                    @php
                                        $isDevUrl = request()->is('dev*');
                                    @endphp
                                    @if (auth()->check() && auth()->user()->id !== null)
                                        <button onclick="devMenu('{{auth()->user()->id}}')" class="btn {{ $isDevUrl ? 'btn-dark' : 'btn-secondary' }} btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Developer Menu">
                                            <i class="ki-solid ki-setting-2 fs-3 m-0 p-0"></i>
                                        </button>
                                    @endif
                                </li>
                            </ul>
                            <!--end::Menu--> --}}
                        </div>
                        <!--end::Footer container-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->

            @yield('modal')
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->

    <!--begin::Javascript-->
    @php
$idleTimeSetting = \App\Models\MaintenanceMode::first();
$idleMinutes = $idleTimeSetting ? $idleTimeSetting->idle_time : 59; // Default to 60 minutes if not set
    @endphp

    <script>
        let idleTime = 0;
        let idleMin = 0;
        const idleLimit = 3540; // 60 minutes in seconds
        const idleDisplay = document.getElementById('idle_time');

        // Increment the idle time counter every second
        const idleInterval = setInterval(() => {
            idleTime++;

            // Calculate total idle time in minutes and seconds
            const totalIdleTime = idleTime % 3600; // Total seconds in an hour
            const minutes = Math.floor(totalIdleTime / 60);
            const seconds = totalIdleTime % 60;

            // Format minutes and seconds to always show two digits
            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(seconds).padStart(2, '0');

            idleDisplay.textContent = `Idle Time: ${formattedMinutes}:${formattedSeconds}`;

            if (minutes >= {{ $idleMinutes }}) {
                window.location.href = '{{ route("logout") }}'; // Update this route as needed
            }
        }, 1000); // Check every second

        // Reset the idle timer on user activity
        const resetIdleTime = () => {
            idleTime = 0;
            idleDisplay.textContent = 'Idle Time: 00:00'; // Reset display
        };

        // Listen for user activity
        document.addEventListener('mousemove', resetIdleTime);
        document.addEventListener('keypress', resetIdleTime);
        document.addEventListener('click', resetIdleTime);
        document.addEventListener('scroll', resetIdleTime);
        document.addEventListener('touchstart', resetIdleTime);
    </script>

    {{-- <script>
        // Function to check orientation and update display
        function checkOrientation() {
            if (window.innerHeight > window.innerWidth) {
                // Portrait mode
                document.getElementById('kt_app_content').style.display = 'none'; // Hide content
                alert("Please rotate your device to landscape mode."); // Show alert
            } else {
                // Landscape mode
                document.getElementById('kt_app_content').style.display = 'block'; // Show content
            }
        }

        // Initial check on page load
        checkOrientation();

        // Add event listener for orientation change
        window.addEventListener("orientationchange", function() {
            checkOrientation();
        });

        // Also listen for resize events (for browsers that don't support orientationchange)
        window.addEventListener("resize", function() {
            checkOrientation();
        });
    </script> --}}
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{asset('/assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('/assets/js/scripts.bundle.js')}}"></script>
    {{-- <script src="{{ asset('assets/js/check.js') }}"></script> --}}
    @include('layout.alert')
    <!--end::Global Javascript Bundle-->
    <script>
        $(document).ready(function() {
            $('.page-loading').fadeIn();
            setTimeout(function() {
                $('.page-loading').fadeOut();
            }, 1500); // Adjust the timeout duration as needed
        });

        function showLoading() {
            $('#page-loading').fadeIn();
        }
        function hideLoading() {
            $('#page-loading').fadeOut();
        }

        $('form').on('submit', function(e) {
            e.preventDefault(); // Mencegah submit form default

            // Disable tombol submit setelah form disubmit
            var $form = $(this);
            $form.find('button[type="submit"]').attr('disabled', true);
            $form.find('button[type="submit"]').text('Loading...');

            // Gunakan FormData untuk menyertakan file
            var formData = new FormData(this);

            // Submit data menggunakan AJAX
            $.ajax({
                type: $form.attr('method'), // Method form POST atau GET
                url: $form.attr('action'), // URL tujuan
                data: formData, // Gunakan FormData
                processData: false, // Jangan memproses data
                contentType: false, // Jangan set content type
                beforeSend: function() {
                    showLoading();
                    $form.find('button[type="submit"]').attr('disabled', true);
                    $form.find('button[type="submit"]').text('Loading...');
                },
                success: function(response) {
                    // Proses selesai, enable kembali tombol
                    $form.find('button[type="submit"]').attr('disabled', false);
                    $form.find('button[type="submit"]').text('Submit');
                    console.log(response);

                    // Opsional: tangani respons dari Laravel
                    if (response.success) {
                        // Menampilkan SweetAlert dengan pesan sukses
                        Swal.fire({
                            title: 'Success :)',
                            text: response.message,
                            icon: 'success',
                            allowOutsideClick: false, // Tidak bisa ditutup dengan klik luar
                            allowEscapeKey: false, // Tidak bisa ditutup dengan tombol escape
                            timer: 3000, // Timer 3 detik sebelum redirect
                            timerProgressBar: true, // Progress bar di bawah modal
                            didOpen: () => {
                                Swal
                                    .showLoading(); // Menampilkan loading di dalam modal
                            },
                            willClose: () => {
                                // Redirect ke halaman setelah timer selesai
                                window.location.href = response.redirect;
                            }
                        });
                    } else {
                        // Menampilkan SweetAlert dengan pesan error
                        Swal.fire({
                            title: 'Error System',
                            text: response.message,
                            icon: 'error',
                            allowOutsideClick: false, // Tidak bisa ditutup dengan klik luar
                            allowEscapeKey: false, // Tidak bisa ditutup dengan tombol escape
                        });

                        // Enable kembali tombol submit
                        $form.find('button[type="submit"]').attr('disabled', false).text(
                            'Submit');
                    }
                },
                error: function(xhr) {
                    // Enable kembali tombol submit
                    $form.find('button[type="submit"]').attr('disabled', false).text(
                        'Submit');

                    // Tangani error validasi dari Laravel
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;

                        // Hapus pesan error sebelumnya
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        var
                            firstErrorField; // Variabel untuk menyimpan elemen error pertama

                        // Tampilkan pesan error
                        $.each(errors, function(key, value) {
                            var inputField = $form.find(`[name="${key}"]`);
                            inputField.addClass('is-invalid');
                            inputField.after(
                                `<span class="invalid-feedback" role="alert"><strong>${value[0]}</strong></span>`
                            );

                            // Simpan elemen error pertama
                            if (!firstErrorField) {
                                firstErrorField = inputField;
                            }
                        });

                        // Scroll ke elemen error pertama
                        if (firstErrorField) {
                            $('html, body').animate({
                                scrollTop: firstErrorField.offset().top -
                                    100 // Offset agar tidak terlalu menempel di atas
                            }, 'slow');
                        }
                    } else {
                        alert('Terjadi kesalahan, coba lagi.');
                    }
                },
                complete: function() {
                    hideLoading();
                    $form.find('button[type="submit"]').attr('disabled', false);
                }
            });
        });

        $('#btnMt').on('click', function() {

        });
    </script>

    @yield('scripts')
    <!--end::Javascript-->
</body>
<!--end::Body-->
</html>
