<!DOCTYPE html>
<html lang="en">
<head>
    <base href=""/>
    <title>@yield('title') - PT Kalbe Farma, Tbk.</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Formulir Monitoring Ruangan. Pantau Kondisi Suhu, RH, DP">
    <meta name="keywords" content="Monitoring, TSUP, MSTD, Kalbe, Kalbe-Farma, OneKalbe">
    <meta name="author" content="Ferdy Rahmat">
    <link rel="shortcut icon" href="{{ asset('assets/logo/logo_only.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/inter_font_api.css') }}" />
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />

    @yield('head')

    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    @stack('styles')

    <style>
        .user-toggle .btn {
            background-color: #e9ecef !important;
            border-color: #dee2e6 !important;
            color: #495057 !important;
        }

        .user-toggle .btn-check:checked + .btn {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #ffffff !important;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        }

        .user-toggle .btn:hover {
            background-color: #adb5bd !important;
            color: #fff !important;
        }
    </style>
</head>

<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default" data-kt-app-sidebar-minimize="on" style="background-image: url('/assets/img/bglineB.svg'); background-repeat: repeat-y; background-size: cover;">

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

    <div class="page-loading">
        <div class="page-loader flex-column bg-dark bg-opacity-25">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
        </div>
    </div>

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
                    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-abstract-14 fs-2 fs-md-1"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a class="d-lg-none">
                            <img alt="Logo" src="{{asset('assets/logo/kalbe_farma.png')}}" class="h-30px" />
                        </a>
                    </div>
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                            <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
                                <div class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
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
                                </div>
                            </div>
                        </div>

                        <div class="app-navbar flex-shrink-0">
                            <div class="app-navbar-item ms-1 ms-md-4" id="idle_time_display">
                                <span id="idle_time" class="text-muted">Idle Time: 00:00</span>
                            </div>
                            <div class="app-navbar-item ms-1 ms-md-4">
                                <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
                                    <i class="ki-outline ki-notification-status fs-2"></i>
                                    @if(auth()->user()->notify()->count() > 0)
                                        <span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                                    @endif
                                </div>

                                <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-500px" data-kt-menu="true" id="kt_menu_notifications">
                                    <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image: url('/assets/media/misc/menu-header-bg.jpg'); background-size: cover; background-position: center;">
                                        <div class="d-flex flex-stack p-5 my-2">
                                            <div class="d-flex">
                                                <h3 class="text-white fw-semibold px-9">Notifications</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="kt_topbar_notifications_3" role="tabpanel">
                                            <div class="scroll-y mh-300px my-5 px-8">
                                                @forelse (auth()->user()->notify() as $notif)
                                                    @php
                                                        $isToday = Carbon\Carbon::parse($notif->created_at)->isToday();
                                                        $relativeTime = Carbon\Carbon::parse($notif->created_at)->diffForHumans();
                                                    @endphp

                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center">
                                                            <div class="card mb-2">
                                                                <div class="card-body">
                                                                    <span class="badge {{ $isToday ? 'badge-success' : 'badge-warning' }} fs-8 float-end">{{ $isToday ? 'Today' : 'Kemarin' }}</span>
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
                                                    </div>
                                                @empty
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center w-100 justify-content-center">
                                                            <h6 class="text-body mb-2 mt-2 text-gray-600">Tidak Ada Notifikasi</h6>
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                            <div class="py-1 text-center border-top">
                                                <button class="btn btn-color-gray-600 btn-active-color-danger w-100" onclick="clearNotif()">
                                                    Clear all notification
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                                <div class="d-flex flex-column px-2 align-items-end">
                                    <div class="d-flex fs-6">
                                        {{auth()->user()->fullname}}
                                    </div>
                                    <div class="d-flex fs-8 fw-semibold">
                                        <span class="badge badge-light-info">{{auth()->user()->jobTitle}}</span>
                                    </div>
                                </div>

                                <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <img src="/assets/media/avatars/avatar_gray.png" class="rounded-3" alt="user" />
                                </div>

                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3">

                                            <div class="symbol symbol-50px me-5">
                                                <img alt="Logo" src="/assets/media/avatars/avatar_gray.png" />
                                            </div>

                                            <div class="d-flex flex-column">
                                                <div class="fw-bold align-items-center fs-5">
                                                    {{auth()->user()->fullname}}
                                                </div>
                                                <small class="fw-semibold text-muted fs-7">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator my-2"></div>

                                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
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

                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
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

                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
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
                                        </div>
                                    </div>

                                    <div class="separator my-2"></div>

                                    @if(auth()->user()->is_local == true)
                                        <div class="menu-item px-5">
                                            <a type="button" class="menu-link px-5" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">Ganti Password</a>
                                        </div>
                                        
                                        <div class="separator my-2"></div>
                                    @endif

                                    <div class="menu-item px-5">
                                        <a href="{{route('logout')}}" class="menu-link px-5" onclick="showLoading()">Sign Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function clearNotif()
                {
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("user/clear-notifications") }}',
                        data: {
                            _token: '{{ csrf_token() }}'
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

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                @include('layout.sidebar')

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                        @yield('page_title')
                                    </h1>
                                    @yield('breadcrumb')
                                </div>
                                <div class="d-flex align-items-center gap-2 gap-lg-3">
                                    @yield('action_button')
                                </div>
                            </div>
                        </div>

                        @yield('main-content')

                    </div>

                    <div id="kt_app_footer" class="app-footer">
                        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">2025&copy;</span>
                                <a href="" target="_blank" class="text-gray-800 text-hover-primary fs-6">Kalbe Farma &#9829; crafted by MSTD Team </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @yield('modal')

            <div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form id="updatePasswordForm" class="modal-content" action="{{ route('updatePassword') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Ganti Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="********" required>
                                    <button type="button" class="input-group-text" id="showPassword">
                                        <i class="ki-duotone ki-eye fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="********" required>
                                    <button type="button" class="input-group-text" id="showConfirmPassword">
                                        <i class="ki-duotone ki-eye fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    @php
        $idleTimeSetting = \App\Models\MaintenanceMode::first();
        $idleMinutes = $idleTimeSetting ? $idleTimeSetting->idle_time : 59;
    @endphp

    <script>
        let idleTime = 0;
        let idleMin = 0;
        const idleLimit = 3540;
        const idleDisplay = document.getElementById('idle_time');

        const idleInterval = setInterval(() => {
            idleTime++;

            const totalIdleTime = idleTime % 3600;
            const minutes = Math.floor(totalIdleTime / 60);
            const seconds = totalIdleTime % 60;

            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(seconds).padStart(2, '0');

            idleDisplay.textContent = `Idle Time: ${formattedMinutes}:${formattedSeconds}`;

            if (minutes >= {{ $idleMinutes }}) {
                window.location.href = '{{ route("logout") }}';
            }
        }, 1000);

        const resetIdleTime = () => {
            idleTime = 0;
            idleDisplay.textContent = 'Idle Time: 00:00';
        };

        document.addEventListener('mousemove', resetIdleTime);
        document.addEventListener('keypress', resetIdleTime);
        document.addEventListener('click', resetIdleTime);
        document.addEventListener('scroll', resetIdleTime);
        document.addEventListener('touchstart', resetIdleTime);
    </script>

    <script src="{{asset('/assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('/assets/js/scripts.bundle.js')}}"></script>

    @include('layout.alert')

    <script>
        $(document).ready(function() {
            $('.page-loading').fadeIn();
            setTimeout(function() {
                $('.page-loading').fadeOut();
            }, 1500);
        });

        function showLoading() {
            $('#page-loading').fadeIn();
        }
        function hideLoading() {
            $('#page-loading').fadeOut();
        }

        $('form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            $form.find('button[type="submit"]').attr('disabled', true);
            $form.find('button[type="submit"]').text('Loading...');

            var formData = new FormData(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    showLoading();
                    $form.find('button[type="submit"]').attr('disabled', true);
                    $form.find('button[type="submit"]').text('Loading...');
                },
                success: function(response) {
                    $form.find('button[type="submit"]').attr('disabled', false);
                    $form.find('button[type="submit"]').text('Submit');
                    console.log(response);

                    if (response.success) {
                        Swal.fire({
                            title: 'Success :)',
                            text: response.message,
                            icon: 'success',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal
                                    .showLoading();
                            },
                            willClose: () => {
                                window.location.href = response.redirect;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error System',
                            text: response.message,
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        });

                        $form.find('button[type="submit"]').attr('disabled', false).text(
                            'Submit');
                    }
                },
                error: function(xhr) {
                    $form.find('button[type="submit"]').attr('disabled', false).text(
                        'Submit');

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;

                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        var firstErrorField;

                        $.each(errors, function(key, value) {
                            var inputField = $form.find(`[name="${key}"]`);
                            inputField.addClass('is-invalid');
                            inputField.after(
                                `<span class="invalid-feedback" role="alert"><strong>${value[0]}</strong></span>`
                            );

                            if (!firstErrorField) {
                                firstErrorField = inputField;
                            }
                        });

                        if (firstErrorField) {
                            $('html, body').animate({
                                scrollTop: firstErrorField.offset().top - 100
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

        ["showPassword", "showConfirmPassword"].forEach(btnId => {
            const btn = document.getElementById(btnId);
            
            if (btn) {
                btn.addEventListener("click", function () {
                    const input = this.closest('.input-group').querySelector('input');
                    const icon = this.querySelector("i");

                    const isPassword = input.type === "password";
                    input.type = isPassword ? "text" : "password";

                    icon.classList.toggle("ki-eye", !isPassword);
                    icon.classList.toggle("ki-eye-slash", isPassword);
                });
            }
        });
    </script>

    @yield('scripts')

</body>
</html>
