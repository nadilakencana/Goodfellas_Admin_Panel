<nav class="main-header navbar navbar-expand navbar-dark">

    <!-- Left navbar links -->

    <ul class="navbar-nav">

      <li class="nav-item">

        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>

      </li>

      <li class="nav-item  d-sm-inline-block">

        <a href="{{ route('Dashboard') }}" class="nav-link">Dashboard</a>

      </li>
      <li class="nav-item d-sm-inline-block">

        <a href="{{ route('logout') }}" class="nav-link">Logout</a>

      </li>

      

    </ul>



    <!-- Right navbar links -->

    <ul class="navbar-nav ml-auto">

      <li class="nav-item d-flex justify-content-between align-content-center">
        <div class="notif-status-connet" id="notif-status"></div>
        <div id="status">Checking...</div>
       
      </li>

      

    </ul>

  </nav>

