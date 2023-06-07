<div class="sidebar-content">
    <div class="aside-toolbar">
        <ul class="site-logo">
            <li>
                <!-- START LOGO -->
                <a href="{{route('dashboard')}}">
                    <div class="logo">

                    </div>
                    <span class="brand-text">FlexibleDrive</span>
                </a>
                <!-- END LOGO -->
            </li>
        </ul>
        <ul class="header-controls">
            <!-- <li class="nav-item menu-trigger">
                <button type="button" class="btn btn-link btn-menu" data-toggle-state="mini-sidebar" data-key="leftSideBar">
                    <i class="la la-dot-circle-o"></i>
                </button>
            </li> -->
        </ul>
    </div>
    <nav class="main-menu" data-scroll="minimal-light">
        <ul class="nav metismenu">
            <li class="sidebar-header"><span>NAVIGATION</span></li>
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}"><a href="{{route('dashboard')}}"><i class="icon dripicons-meter"></i><span>Dashboard</span></a></li>
            <li class="{{ (Request::segment(1) == 'user') ? 'active' : '' }}"><a href="{{route('users')}}"><i class="zmdi zmdi-accounts-alt zmdi-hc-fw"></i><span>Users</span></a></li>
            <li class="{{ (Request::segment(1) == 'order') ? 'active' : '' }}"><a href="{{route('orders')}}"><i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i><span>Orders</span></a></li>
            <li class="{{ (Request::segment(1) == 'abandoned-cart') ? 'active' : '' }}"><a href="{{route('abandoned-cart')}}"><i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i><span>Abandoned Orders</span></a></li>
            <li class="{{ (Request::segment(1) == 'market-intel') ? 'active' : '' }}"><a href="{{route('market-intels')}}"><i class="zmdi zmdi-blogger zmdi-hc-fw"></i><span>Market Intel</span></a></li>
            <li class="{{ (Request::segment(1) == 'banner-management') ? 'active' : '' }}"><a href="{{route('banners')}}"><i class="zmdi zmdi-image-alt zmdi-hc-fw"></i><span>Banner Management</span></a></li>
            <li class="{{ (Request::segment(1) == 'note') ? 'active' : '' }}"><a href="{{route('notes')}}"><i class="zmdi zmdi-assignment zmdi-hc-fw"></i><span>Product Notes</span></a></li>
            <li class="{{ (Request::segment(1) == 'analytics') ? 'active' : '' }}"><a href="{{route('analytics.dashboard')}}"><i class="zmdi zmdi-chart zmdi-hc-fw"></i><span>Analytics</span></a></li>
        </ul>
    </nav>
</div>