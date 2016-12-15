<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="background-image: url('/images/header-profile.png')">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" src="{{url('images/profile_small.jpg')}}" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"><span class="block m-t-xs"><strong class="font-bold">{{Auth::user()['name']}}</strong></span><span class="text-muted text-xs block" style="{{ Request::is( '*/password*') ? "color:white" : ""}}">Admin<b class="caret"></b></span></span>
                    </a>
                    <ul class="dropdown-menu animated m-t-xs">
                        <li><a href="/admin/password">Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    TT
                </div>
            </li>
            <li class="{{ Request::is( '*/dashboard') ? "active" : ""}}">
                <a href="/admin/dashboard"><i class="fa fa-th-large"></i><span class="nav-label">Dashboard</span></a>
            </li>
            <li><a href="{{ route("profiles.index") }}"><span class="nav-label">Visit Site</span></a></li>
            <li class="{{ Request::is( '*/permission/*') ? "active" : ""}}">
                <a href=""><i class="fa fa-plus"></i><span class="nav-label">Permissions</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ Request::is( '*/permission/add*') ? "active" : ""}}"><a href="/admin/permission/add">Add Permission</a></li>
                    <li class="{{ Request::is( '*/permission/view*') ? "active" : ""}}"><a href="/admin/permission/view">View / Edit Permissions</a></li>
                </ul>
            </li>
            <li class="{{ Request::is( '*/role/*') ? "active" : ""}}">
                <a href=""><i class="fa fa-plus"></i><span class="nav-label">Roles</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ Request::is( '*/role/add*') ? "active" : ""}}"><a href="/admin/role/add">Add Role</a></li>
                    <li class="{{ Request::is( '*/role/view*') ? "active" : ""}}"><a href="/admin/role/view">View / Edit Roles</a></li>
                </ul>
            </li>
            @role(('admin'))
            <li><a href="{{ route('profile_types.index')}}"><span class="nav-label">Profile Types</span></a></li>
            <li><a href="{{ route('profile_attributes.index')}}"><span class="nav-label">Profile Attributes</span></a></li>
            <li><a href="{{ route('templates.index')}}"><span class="nav-label">Templates</span></a></li>
            <li><a href="{{ route('privacies.index')}}"><span class="nav-label">Privacy</span></a></li>
            @endrole
        </ul>
    </div>
</nav>