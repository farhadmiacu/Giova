<div id="scrollbar">
    <div class="container-fluid">

        <div id="two-column-menu"></div>

        <ul class="navbar-nav" id="navbar-nav">

            {{-- Menu Title --}}
            <li class="menu-title"><span data-key="t-menu">Menu</span></li>

            {{-- Dashboard --}}
            @can('dashboard_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </li>
            @endcan

            {{-- Category Menu --}}
            @can('category_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.categories.*') ? '' : 'collapsed' }}" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}" aria-controls="sidebarCategory">
                        <i class="ri-folder-line"></i> <span>Category</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="sidebarCategory">
                        <ul class="nav nav-sm flex-column">
                            @can('category_create')
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.create') }}" class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                                        Add Category
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                    All Categories
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Brand Menu --}}
            @can('brand_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.brands.*') ? '' : 'collapsed' }}" href="#sidebarBrand" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('admin.brands.*') ? 'true' : 'false' }}" aria-controls="sidebarBrand">
                        <i class="ri-price-tag-3-line"></i> <span>Brand</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.brands.*') ? 'show' : '' }}" id="sidebarBrand">
                        <ul class="nav nav-sm flex-column">
                            @can('brand_create')
                                <li class="nav-item">
                                    <a href="{{ route('admin.brands.create') }}" class="nav-link {{ request()->routeIs('admin.brands.create') ? 'active' : '' }}">
                                        Add Brand
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">
                                    All Brands
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Product Menu --}}
            @can('product_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.products.*') ? '' : 'collapsed' }}" href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}" aria-controls="sidebarProduct">
                        <i class="ri-shopping-bag-3-line"></i> <span>Product</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.products.*') ? 'show' : '' }}" id="sidebarProduct">
                        <ul class="nav nav-sm flex-column">
                            @can('product_create')
                                <li class="nav-item">
                                    <a href="{{ route('admin.products.create') }}" class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                        Add Product
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                    All Products
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Settings Title --}}
            {{-- <li class="menu-title"><span data-key="t-menu">Settings</span></li> --}}
            @canany(['role_view', 'user_view', 'system-settings_edit'])
                <li class="menu-title"><span data-key="t-menu">Settings</span></li>
            @endcanany

            {{-- Role Menu --}}
            @can('role_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.roles.*') ? '' : 'collapsed' }}" href="#sidebarRole" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('admin.roles.*') ? 'true' : 'false' }}" aria-controls="sidebarRole">
                        <i class="ri-user-settings-line"></i> <span>Roles</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.roles.*') ? 'show' : '' }}" id="sidebarRole">
                        <ul class="nav nav-sm flex-column">
                            @can('role_create')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.create') }}" class="nav-link {{ request()->routeIs('admin.roles.create') ? 'active' : '' }}">
                                        Add Role
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                                    All Roles
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- User Menu --}}
            @can('user_view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.users.*') ? '' : 'collapsed' }}" href="#sidebarUser" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}" aria-controls="sidebarUser">
                        <i class="ri-user-line"></i> <span>Users</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="sidebarUser">
                        <ul class="nav nav-sm flex-column">
                            @can('user_create')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                        Add User
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    All Users
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            {{-- System Settings --}}

            @can('system-settings_edit')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.system-settings.edit') ? 'active' : '' }}" href="{{ route('admin.system-settings.edit') }}">
                        <i class="ri-settings-3-line"></i> <span>System Settings</span>
                    </a>
                </li>
            @endcan
            {{-- Mail Settings --}}
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs('admin.mail-settings.edit') ? 'active' : '' }}" href="{{ route('admin.mail-settings.edit') }}">
                    <i class="ri-mail-settings-line"></i> <span>Mail Settings</span>
                </a>
            </li>

        </ul>
    </div>
</div>
<div class="sidebar-background"></div>
