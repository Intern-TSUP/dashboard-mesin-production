{{-- @if (auth()->user()->jobLvl == 'Administrator') --}}
@php
    use Illuminate\Support\Str;

    if (auth()->user()->hasCustomRole()) {
        $relation = auth()
            ->user()
            ->userCustomRoleIs()
            ->map(fn($rel) => $rel->customRole?->permission)
            ->filter()
            ->flatten();
    } else {
        $relation = auth()->user()->roles->permission;
    }

    $permissions = $relation;

    $canUser = $permissions->contains(fn($p) =>
        \Illuminate\Support\Str::is('admin.user.index', $p->url)
    );

    $canCustomRole = $permissions->contains(fn($p) =>
        \Illuminate\Support\Str::is('admin.user-has-custom-role.index', $p->url)
    );
    
    $customRoles = \App\Models\CustomRole::all();
@endphp
@foreach ($relation as $item)
    @if (Str::is('admin.*', $item->url))
        <!--begin:Menu item-->
        <div class="menu-item pt-5">
            <!--begin:Menu content-->
            <div class="menu-content">
                <span class="text-gray-800 fw-bold text-uppercase fs-8">Admin Tools</span>
            </div>
            <!--end:Menu content-->
        </div>
        <!--end:Menu item-->

        @foreach ($relation as $item)
            @if (Str::is('admin.department.index', $item->url))
                {{-- Department --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/department') ? 'active' : '' }}"
                        href="{{ route('admin.department.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-lock-2 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Department</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @foreach ($relation as $item)
            @if (Str::is('admin.roles.index', $item->url))
                {{-- Permission --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/roles*') ? 'active' : '' }}"
                        href="{{ route('admin.roles.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-lock-2 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Roles Management</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @foreach ($relation as $item)
            @if (Str::is('admin.permission.index', $item->url))
                {{-- Permission --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/permission') ? 'active' : '' }}"
                        href="{{ route('admin.permission.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-lock-2 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Permission Role Access</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @foreach ($relation as $item)
            @if (Str::is('admin.permissionLine.index', $item->url))
                {{-- Permission --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/permissionLine') ? 'active' : '' }}"
                        href="{{ route('admin.permissionLine.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-duotone ki-security-user fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Permission Line Access</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        <!-- @foreach ($relation as $item)
            @if (Str::is('admin.user.index', $item->url))
                {{-- Web Settings --}}
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/user*') ? 'active' : '' }}"
                        href="{{ route('admin.user.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user-tick fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Users Manage</span>
                    </a>
                </div>
                @break
            @endif
        @endforeach -->

        @if ($canUser || $canCustomRole)
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->is('admin/user*') || request()->is('admin/user-has-custom-role*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-user-tick fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                    <span class="menu-title fw-semibold">User Manage</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->

                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    @if ($canCustomRole)
                        @foreach ($customRoles as $customRole)
                            @php
                                $slug = \Illuminate\Support\Str::slug($customRole->name);
                            @endphp
                            <div class="menu-item">
                                <a class="menu-link {{ request()->is('admin/user-has-custom-role/' . $slug . '*') ? 'active' : '' }}"
                                href="{{ route('admin.user-has-custom-role.index', [$slug, $customRole->id]) }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">{{ $customRole->name }}</span>
                                </a>
                            </div>
                        @endforeach
                    @endif

                    @if ($canUser)
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/user') || request()->is('admin/user/*') ? 'active' : '' }}"
                            href="{{ route('admin.user.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">User</span>
                            </a>
                        </div>
                    @endif
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        @endif

        @foreach ($relation as $item)
            @if (Str::is('admin.settings.index', $item->url))
                {{-- Web Settings --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-gear fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Page Settings</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @break
    @endif
@endforeach
