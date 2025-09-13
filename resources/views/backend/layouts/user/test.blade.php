<div id="scrollbar">
    <div class="container-fluid">

        <div id="two-column-menu">
        </div>
        <ul class="navbar-nav" id="navbar-nav">

            {{-- Menu start  --}}
            <li class="menu-title"><span data-key="t-menu">Menu</span></li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Category</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarDashboards">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.create') }}" class="nav-link" data-key="t-analytics"> Category Create </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link" data-key="t-crm"> Categories </a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarBrand" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBrand">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Brand</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarBrand">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.brands.create') }}" class="nav-link" data-key="t-analytics"> Brand Create </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.brands.index') }}" class="nav-link" data-key="t-crm"> Brands </a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProduct">
                    <i class="ri-store-2-line"></i> <span data-key="t-products">Product</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarProduct">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.products.create') }}" class="nav-link" data-key="t-create"> Product Create </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.products.index') }}" class="nav-link" data-key="t-list"> Products </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Menu end  --}}

            {{-- Settings start  --}}

            <li class="menu-title"><span data-key="t-menu">Settings</span></li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarrole" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarrole">
                    <i class="ri-store-2-line"></i> <span data-key="t-products">Role</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarrole">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.create') }}" class="nav-link" data-key="t-create"> Role Create </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link" data-key="t-list"> Roles </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebaruser" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebaruser">
                    <i class="ri-store-2-line"></i> <span data-key="t-products">User</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebaruser">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.users.create') }}" class="nav-link" data-key="t-create"> User Create </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link" data-key="t-list"> Users </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarSetting" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSetting">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">System Settings</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarSetting">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.system-settings.edit') }}" class="nav-link" data-key="t-analytics"> System Setting</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Settings end  --}}

            <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                    <i class="ri-account-circle-line"></i> <span data-key="t-authentication">Authentication</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarAuth">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignIn" data-key="t-signin"> Sign In
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarSignIn">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="auth-signin-basic.html" class="nav-link" data-key="t-basic"> Basic
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="auth-signin-cover.html" class="nav-link" data-key="t-cover"> Cover
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignUp" data-key="t-signup"> Sign Up
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarSignUp">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="auth-signup-basic.html" class="nav-link" data-key="t-basic"> Basic
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="auth-signup-cover.html" class="nav-link" data-key="t-cover"> Cover
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
    <!-- Sidebar -->
</div>

{{-- <div class="sidebar-background"></div> --}}
