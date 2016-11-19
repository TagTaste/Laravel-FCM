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
        </ul>
    </div>
</nav>