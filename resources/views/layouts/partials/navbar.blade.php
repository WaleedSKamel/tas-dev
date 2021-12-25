<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @if (Auth::guard('supervisor')->check())
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('supervisor.logout') }}" class="nav-link">Logout</a>
            </li>
        @else
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.logout') }}" class="nav-link">Logout</a>
            </li>
        @endif

    </ul>


</nav>
