<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('AdminLTE') }}/dist/img/n5logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">E-MTQ</span>
    </a>
  
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="#" class="img-circle elevation-2" alt="Foto Profil">
        </div>
        <div class="info">
          <a href="#" class="d-block">#</a>
          <span class="text-success"><i class="fas fa-circle nav-icon"></i> #</span>
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
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <a href="{{ route('home')}}" class="nav-link {{ (request()->is('home*')) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <li class="nav-item has-treeview {{ request()->is('tahunevent*') || request()->is('desa*') || request()->is('golongan*') || request()->is('nomorcabang*') || request()->is('ketentuanusia*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('tahunevent*') || request()->is('desa*') || request()->is('golongan*') || request()->is('nomorcabang*') || request()->is('ketentuanusia*') ? 'active' : '' }}">
                  <i class="nav-icon far fa-id-card"></i>
                  <p>
                    Data Master
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('tahunevent.index')}}" class="nav-link {{ request()->is('tahunevent*') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Data Tahun</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('desa.index')}}" class="nav-link {{ (request()->is('desa*')) ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Data Desa</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('nomorcabang.index')}}" class="nav-link {{ (request()->is('nomorcabang*')) ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>No. Cabang</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('golongan.index')}}" class="nav-link {{ (request()->is('golongan*')) ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Golongan</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('ketentuanusia.index')}}" class="nav-link {{ (request()->is('ketentuanusia*')) ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Ketentuan Usia</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon far fa-id-card"></i>
                  <p>
                    Pendaftaran
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Form Daftar</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Verifikasi Pendaftar</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List Peserta</p>
                    </a>
                  </li>
                </ul>
              </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Pengumuman
              </p>
            </a>
          </li>
          <li class="nav-header">PENGATURAN</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-id-card"></i>
              <p>
                User/ Pengguna
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profil Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Pengguna</p>
                </a>
              </li>
            </ul>
          </li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" class="nav-link">
            <button type="button" class="btn btn-block btn-danger btn-sm" id="logout-form"><i class="fas fa-sign-out-alt"></i> KELUAR</button>
            {{ csrf_field() }}
            </form>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>