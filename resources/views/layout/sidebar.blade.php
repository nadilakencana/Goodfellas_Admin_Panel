@if(Sentinel::check())
@php
  $admin = Sentinel::getUser();
@endphp
@endif

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('asset/assets/image/LOGO PUTIH.png') }}" alt="Goodfellas Logo" class="brand-image img-circle elevation-3" style="opacity: .8; border-radius: 0;">
      <span class="brand-text font-weight-light">Goodfellas</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image" style="width: 22%">
          <img src="{{ asset('asset/tamplate/img/avatar2.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>

        <div class="info admin" data-admin = "{{ $admin->id }}">
          <a href="#" class="d-block">{{ $admin->nama }}</a>
        </div>

      </div>
      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item menu-open">
                <a href="#" class="nav-link ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p></p>
                    <i class="right fas fa-angle-left"></i>
                </a>
                
                <ul class="nav nav-treeview">
                    @if ($admin->level->level == 'Supervisor' ||  $admin->level->level == 'Developer'|| $admin->level->level == "Barista")
                    <li class="nav-item">
                        <a href="{{ route('pos') }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Dasboard POS</p>
                        </a>
                    </li>
                    @endif    
                    @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                    $admin->level->level == 'Directure')
                        <li class="nav-item">
                            <a href="{{ route('menu') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bahanBaku') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bahan Baku</p>
                            </a>
                        </li>
                        @if($admin->level->level =='Developer')
                        <li class="nav-item">
                            <a href="{{ route('kategori') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('subkategori') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sub Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dataModifier') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Modifier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Vip-Room') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>VIP Room</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('data-SalesType') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Type</p>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('data.booking') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Booking Room</p>
                        </a>
                    </li>
                @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                    $admin->level->level == 'Directure')
                        <li class="nav-item">
                            <a href="{{ route('data-tax') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Taxes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('data-Payment') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Type Payment</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('data-discount') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Discount</p>
                            </a>
                        </li>
                @endif
                    <li class="nav-item">
                        <a href="{{ route('contactUs') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Contact Us</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('Qr-table')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>QR Table</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a href="#" class="nav-link ">
                    <p>Report</p>
                    <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('order') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Orders</p>
                        </a>
                    </li>
                    @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                    $admin->level->level == 'Directure' || $admin->level->level ="Finance")

                        <li class="nav-item">
                            <a href="{{ route('report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Report Sales</p>
                            </a>
                        </li>
                    @endif
                        <li class="nav-item">
                            <a href="{{ route('cash_sift') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sift</p>
                            </a>
                        </li>

                   
                </ul>
            </li>
            <li class="nav-item menu-open">
                    <a href="#" class="nav-link ">
                       <p>Vocher</p>
                       <i class="right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                        $admin->level->level == 'Directure')
                            <li class="nav-item">
                                <a href="{{ route('vocher-gif') }}" class="nav-link ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Vocher Gift</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('claimVocher') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Vocher Claim User</p>
                            </a>
                        </li>
                    </ul>
            </li>

            @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                    $admin->level->level == 'Directure')
            <li class="nav-item menu-open">
                <a href="#" class="nav-link ">
                    <p>Setting Data User</p>
                    <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('dataUser') }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dataAdmin') }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Admin</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('LevelLog') }}" class="nav-link ">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Level Admin</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>

      </nav>

      <!-- /.sidebar-menu -->

    </div>

    <!-- /.sidebar -->

  </aside>

