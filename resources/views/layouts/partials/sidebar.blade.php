<div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('assets/') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            @if (Auth::guard('supervisor')->check())
                <a href="#" class="d-block">{{ Auth::guard('supervisor')->user()->username }}</a>
                @else
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>

            @endif
        </div>
    </div>


    <!-- Sidebar Menu -->
@include('layouts.partials.menu')
<!-- /.sidebar-menu -->
</div>
