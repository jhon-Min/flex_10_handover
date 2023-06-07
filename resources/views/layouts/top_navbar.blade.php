<nav class="top-toolbar navbar navbar-mobile navbar-tablet">
    <ul class="navbar-nav nav-left">
        <li class="nav-item">
            <a href="javascript:void(0)" data-toggle-state="aside-left-open">
                <i class="icon dripicons-align-left"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav nav-center site-logo">
        <li>
            <a href="index.html">
                <div class="logo_mobile">

                </div>
                <span class="brand-text">FlexibleDrive</span>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav nav-right">
        <li class="nav-item">
            <a href="javascript:void(0)" data-toggle-state="mobile-topbar-toggle">
                <i class="icon dripicons-dots-3 rotate-90"></i>
            </a>
        </li>
    </ul>
</nav>
<nav class="top-toolbar navbar navbar-desktop flex-nowrap">

    <ul class="navbar-nav nav-right">
        <li class="nav-item dropdown">
            <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">
                <img src="{{ Auth::user()->profile_image ? Auth::user()->image_url : asset('assets/img/avatars/1.jpg') }}"
                    class="w-35 rounded-circle" alt="Albert Einstein">
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-accout">
                <div class="dropdown-header pb-3">
                    <div class="media d-user">
                        <img class="align-self-center mr-3 w-40 rounded-circle"
                            src="{{ Auth::user()->profile_image ? Auth::user()->image_url : asset('assets/img/avatars/1.jpg') }}"
                            alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                        <div class="media-body">
                            <h5 class="mt-0 mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
                <!-- <a class="dropdown-item" href="apps.messages.html"><i class="icon dripicons-mail"></i> Message <span class="badge badge-accent rounded-circle w-15 h-15 p-0 font-size-10">4</span></a>-->
                <a class="dropdown-item" href="{{ route('my-profile') }}"><i class="icon dripicons-user"></i>
                    Profile</a>
                <!--a class="dropdown-item" href="pages.my-account.html"><i class="icon dripicons-gear"></i> Account Settings </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"><i class="icon dripicons-lock"></i> Lock Account</a> -->
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                        class=" icon dripicons-lock-open"></i> Sign Out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
</nav>
