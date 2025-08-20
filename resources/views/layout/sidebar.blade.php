<div id="kt_app_sidebar" class="app-sidebar flex-column shadow" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6 pt-10" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a>
            <img alt="Logo" src="{{asset('assets/logo/Logo-Kalbe-&-BSB_Original.png')}}"
                class="w-100 app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{asset('assets/logo/Logo-Kalbe-&-BSB_Original.png')}}"
                class="w-100 app-sidebar-logo-default theme-dark-show" style="filter: contrast(0);" />
            <img alt="Logo" src="{{asset('assets/logo/logo_only.png')}}" class="h-50px app-sidebar-logo-minimize" />
        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->

        <!-- <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate
            {{-- {{ request()->is('v1/formulir') ? 'active' : '' }} --}}"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div> -->
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">

                    @php
                        $profile = auth()->user()->profile;

                        if ($profile && $profile->line) {
                            $relation = $profile->line->permission; 
                        } else {
                            $relation = auth()->user()->roles->permission; 
                        }
                    @endphp

                    <div class="separator mt-5"></div>
                    {{-- <div class="menu-item pt-5">
                        <div class="card card-flush border-2">
                            <div class="card-body">

                            </div>
                        </div>
                    </div> --}}

                    @foreach ($relation as $item)
                        @if (Str::is('v1.dashboard', $item->url))
                            <!--begin:Menu item-->
                            <div class="menu-item pt-5">
                                <!--begin:Menu content-->
                                <div class="menu-content">
                                    <span class="text-gray-800 fw-bold text-uppercase fs-8">Navigation</span>
                                </div>
                                <!--end:Menu content-->
                            </div>
                            <!--end:Menu item-->
                            @break
                        @endif
                    @endforeach

                    @foreach ($relation as $item)
                        @if (Str::is('v1.dashboard', $item->url))
                            {{-- Dashboard --}}
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('v1') ? 'active' : '' }}" href="{{ route('v1.dashboard') }}">
                                    <span class="menu-icon">
                                        <i class="ki-outline ki-home-2 fs-2"></i>
                                    </span>
                                    <span class="menu-title fw-semibold">Dashboard</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            @break
                        @endif
                    @endforeach

                    @foreach ($relation as $item)
                        @if (Str::is('v1.mesin.*', $item->url))
                            <!--begin:Menu item-->
                            <div class="menu-item pt-5">
                                <!--begin:Menu content-->
                                <div class="menu-content">
                                    <span class="text-gray-800 fw-bold text-uppercase fs-8">Data Master</span>
                                </div>
                                <!--end:Menu content-->
                            </div>
                            <!--end:Menu item-->
                            @break
                        @endif
                    @endforeach

                    @foreach ($relation as $item)
                        @if (Str::is('v1.*', $item->url))
                            {{-- Mesin --}}
                            <!--begin:Menu item-->
                            <div class="menu-item">

                                @foreach ($relation as $item)
                                    @if (Str::is('v1.mesin.index', $item->url))
                                        {{-- Mesin --}}
                                        <!--begin:Menu link-->
                                        <a class="menu-link {{ request()->is('v1/mesin') ? 'active' : '' }}" href="{{ route('v1.mesin.index') }}">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-category fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title fw-semibold">Data Mesin</span>
                                        </a>
                                        <!--end:Menu link-->
                                        @break
                                    @endif
                                @endforeach
                                
                                @foreach ($relation as $item)
                                    @if (Str::is('v1.line.index', $item->url))
                                        {{-- Line --}}
                                        <!--begin:Menu link-->
                                        <a class="menu-link {{ request()->is('v1/line') ? 'active' : '' }}" href="{{ route('v1.line.index') }}">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-category fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title fw-semibold">Line</span>
                                        </a>
                                        <!--end:Menu link-->
                                        @break
                                    @endif
                                @endforeach

                                @foreach ($relation as $item)
                                    @if (Str::is('v1.proses.index', $item->url))
                                        {{-- Proses --}}
                                        <!--begin:Menu link-->
                                        <a class="menu-link {{ request()->is('v1/proses') ? 'active' : '' }}" href="{{ route('v1.proses.index') }}">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-category fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                            <span class="menu-title fw-semibold">Proses</span>
                                        </a>
                                        <!--end:Menu link-->
                                        @break
                                    @endif
                                @endforeach

                            </div>
                            <!--end:Menu item-->
                            @break
                        @endif
                    @endforeach

                    {{-- Sidebar Admin --}}
                    @include('layout.sidebar.admin')

                    <!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="text-gray-800 fw-bold text-uppercase fs-8">Other</span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                    <!--end:Menu item-->
                    
                    {{-- Contact Us --}}
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->is('v1/contactUs*') ? 'active' : '' }}" href="{{ route('v1.contactUs.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-address-book fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title fw-semibold">Contact US</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    
                    {{-- Audit Trail --}}
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->is('v1/auditTrail*') ? 'active' : '' }}" href="{{ route('v1.auditTrail.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-files-tablet fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title fw-semibold">Audit Trail</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
</div>
