<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('AdminLTE') }}/dist/img/logolptq2.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><strong>E-MTQ Kec.Rajeg</strong></span>
    </a>
  
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('AdminLTE') }}/dist/img/avatar5.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8" class="img-circle elevation-2" alt="Foto Profil">
        </div>
        <div class="info">
          <a href="#" class="d-block">Camat Rajeg</a>
          <span class="text-success"><i class="fas fa-circle nav-icon"></i> Administrator</span>
        </div>
      </div>
  
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
  
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard always visible -->
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->is('home*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Only show event-related menus if an event has been selected -->
          @if(session('selected_event_id'))
            @php $role = auth()->user()->role; @endphp
            <!-- Data Master: only administrator can manage cabang & golongan -->
            @if($role === 'administrator')
            <li class="nav-item has-treeview {{ request()->is('cabang*') || request()->is('golongan*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->is('cabang*') || request()->is('golongan*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  Data Master
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('cabang.index') }}" class="nav-link {{ request()->is('cabang*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Cabang</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('golongan.index') }}" class="nav-link {{ request()->is('golongan*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Golongan</p>
                  </a>
                </li>
              </ul>
            </li>
            @endif

            <!-- Pendaftaran: accessible to administrator and admin_desa -->
            @if(in_array($role, ['administrator', 'admin_desa']))
            <li class="nav-item has-treeview {{ request()->is('peserta/create') || (request()->is('home*') && session('selected_event_id')) || request()->is('peserta') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ request()->is('peserta/create') || (request()->is('home*') && session('selected_event_id')) || request()->is('peserta') ? 'active' : '' }}">
                <i class="nav-icon fas fa-edit"></i>
                <p>
                  Pendaftaran
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('peserta.create') }}" class="nav-link {{ request()->is('peserta/create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Form Daftar</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('home') }}" class="nav-link {{ request()->is('home*') && session('selected_event_id') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Verifikasi Pendaftar</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('peserta.index') }}" class="nav-link {{ request()->is('peserta') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List Peserta</p>
                  </a>
                </li>
              </ul>
            </li>
            @endif

            <!-- Pengumuman: visible to all roles -->
            <li class="nav-item">
              <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bullhorn"></i>
                <p>Pengumuman</p>
              </a>
            </li>
          @endif

          <li class="nav-header">PENGATURAN</li>
          @if($role === 'administrator')
          <!-- Kelompok Desa: hanya administrator dapat kelola desa & operator -->
          <li class="nav-item has-treeview {{ request()->is('desa*') || request()->is('operator*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('desa*') || request()->is('operator*') ? 'active' : '' }}">
              <i class="nav-icon far fa-id-card"></i>
              <p>
                Desa
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('desa.index') }}" class="nav-link {{ request()->is('desa*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Desa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('operator.index') }}" class="nav-link {{ request()->is('operator*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Operator</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

          <!-- Logout button -->
          <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-block btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> KELUAR</button>
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>