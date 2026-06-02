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

    $canCustomRole = $permissions->contains(fn($p) =>
        \Illuminate\Support\Str::is('admin.user-has-custom-role.index', $p->url)
    );
    
    $customRoles = \App\Models\CustomRole::all();

    $urls = collect($relation)->pluck('url')->all();

    $hasLocalUserAccess = in_array('admin.localUser.index', $urls, true);
@endphp
@foreach ($relation as $item)
    @if (Str::is('admin.*', $item->url))
        <div class="menu-item pt-5">
            <div class="menu-content">
                <span class="text-gray-800 fw-bold text-uppercase fs-8">Admin Tools</span>
            </div>
        </div>

        @foreach ($relation as $item)
            @if (Str::is('admin.roles.index', $item->url))
                <div class="menu-item">
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
                </div>
                @break
            @endif
        @endforeach

        @foreach ($relation as $item)
            @if (Str::is('admin.permission.index', $item->url))
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/permission') ? 'active' : '' }}" href="{{ route('admin.permission.index') }}">
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
                </div>
                @break
            @endif
        @endforeach

        @foreach ($relation as $item)
            @if (Str::is('admin.permissionLine.index', $item->url))
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/permissionLine') ? 'active' : '' }}"
                        href="{{ route('admin.permissionLine.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-security-user fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Permission Line Access</span>
                    </a>
                </div>
                @break
            @endif
        @endforeach

        @if($hasLocalUserAccess)
            <div class="menu-item">
                <a class="menu-link {{ request()->is('admin/localUser') ? 'active' : '' }}"
                    href="{{ route('admin.localUser.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-user fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title fw-semibold">Local User</span>
                </a>
            </div>
        @endif

        @if ($canCustomRole)
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/user*') || request()->is('admin/user-has-custom-role*') ? 'here show' : '' }}">
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
                </div>
            </div>
        @endif

        @foreach ($relation as $item)
            @if (Str::is('admin.settings.index', $item->url))
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-gear fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Page Settings</span>
                    </a>
                </div>
                @break
            @endif
        @endforeach

        @break
    @endif
@endforeach
