<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">&nbsp;</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" id="dashboard" class="nav-link page active" data-route="{{ route('client.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" id="search-history" class="nav-link page" data-route="{{ route('client.search-history') }}">
                        <i class="nav-icon fas fa-search"></i>
                        <p>
                            Search History
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" id="archived-list" class="nav-link page" data-route="{{ route('client.archived-list') }}">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>
                            Archived List
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" id="inactive-list" class="nav-link page" data-route="{{ route('client.inactive-list') }}">
                        <i class="nav-icon fas fa-times-circle"></i>
                        <p>
                            Inactive List
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>