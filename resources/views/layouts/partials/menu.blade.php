<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('admin.home') }}" class="nav-link {{ checkRoute('admin.home') }}">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    Home
                </p>
            </a>
        </li>

        <li class="nav-item {{ checkRoute('admin/supervisor*','url','ul') }}">
            <a href="#" class="nav-link {{ checkRoute('admin/supervisor*','route','all') }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Supervisor
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.supervisor.index') }}" class="nav-link {{ checkRoute('admin.supervisor.index','route','ul') }}">
                        <i class="far fa-eye nav-icon"></i>
                        <p>View</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.supervisor.create') }}" class="nav-link {{ checkRoute('admin.supervisor.create','route','ul') }}">
                        <i class="fa fa-plus nav-icon"></i>
                        <p>Add</p>
                    </a>
                </li>
            </ul>
        </li>


    </ul>
</nav>
