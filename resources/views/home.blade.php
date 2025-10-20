@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="row">
            <div class="col-md-3 col-sm-5 col-15">
              <div class="info-box bg-info">
                <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Total Pendaftar</span>
                  <span class="info-box-number">41,410</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                  </div>
                  <span class="progress-description">
                    70% Dari Pendaftar
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box bg-success">
                <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Lolos Verifikasi</span>
                  <span class="info-box-number">41,410</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                  </div>
                  <span class="progress-description">
                    70% Dari Pendaftar
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-5 col-15">
              <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Belum Diverifikasi</span>
                  <span class="info-box-number">41,410</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 20%"></div>
                  </div>
                  <span class="progress-description">
                    70% Dari Pendaftar
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-comments"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Tidak Lolos Verifikasi</span>
                  <span class="info-box-number">41,410</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                  </div>
                  <span class="progress-description">
                    70% Dari Pendaftar
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  <div class="col-12">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                <i class="fas fa-text-width"></i>
                Jadwal Pendaftaran
                @if($eventAktif)
                    {{ $eventAktif->judul ?? 'Event Aktif' }} ({{ $eventAktif->tahun }})
                @else
                    Tidak Ada Event Aktif
                @endif
                </h3>
            </div>
            <div class="card-body">
                @if($eventAktif && $eventAktif->detail)
                <ul>
                    <li><strong>Nama Kegiatan:</strong> {{ $eventAktif->detail->nama_kegiatan_aktif }}</li>
                    <li><strong>Waktu Pelaksanaan:</strong>
                    {{ optional($eventAktif->detail->waktu_pelaksanaan_mulai)->translatedFormat('d F Y') }}
                    -
                    {{ optional($eventAktif->detail->waktu_pelaksanaan_selesai)->translatedFormat('d F Y') }}
                    </li>
                    <li><strong>Tempat:</strong> {{ $eventAktif->detail->tempat_pelaksanaan }}</li>
                    <li><strong>Batas Umur:</strong>
                        @if($eventAktif && $eventAktif->detail && $eventAktif->detail->batas_umur_tanggal_patokan)
                            {{
                            \Carbon\Carbon::parse($eventAktif->detail->batas_umur_tanggal_patokan)
                                ->diffInYears(\Carbon\Carbon::now())
                            }} tahun
                        @else
                            Tidak ditentukan
                        @endif
                    </li>
                    <li><strong>Pendaftaran:</strong>
                    {{ optional($eventAktif->detail->pendaftaran_mulai)->translatedFormat('d F Y H:i') }}
                    -
                    {{ optional($eventAktif->detail->pendaftaran_selesai)->translatedFormat('d F Y H:i') }}
                    </li>
                    <li><strong>Verifikasi Tahap 1:</strong>
                    {{ optional($eventAktif->detail->verif1_mulai)->translatedFormat('d F Y H:i') }}
                    -
                    {{ optional($eventAktif->detail->verif1_selesai)->translatedFormat('d F Y H:i') }}
                    </li>
                    <li><strong>Verifikasi Tahap 2:</strong>
                    {{ optional($eventAktif->detail->verif2_mulai)->translatedFormat('d F Y H:i') }}
                    -
                    {{ optional($eventAktif->detail->verif2_selesai)->translatedFormat('d F Y H:i') }}
                    </li>
                    <li><strong>Masa Sanggah:</strong>
                    {{ optional($eventAktif->detail->sanggah_mulai)->translatedFormat('d F Y H:i') }}
                    -
                    {{ optional($eventAktif->detail->sanggah_selesai)->translatedFormat('d F Y H:i') }}
                    </li>
                    <li><strong>Pengumuman Verifikasi:</strong> {{ optional($eventAktif->detail->pengumuman_verifikasi)->translatedFormat('d F Y H:i') }}</li>
                    <li><strong>Technical Meeting:</strong> {{ optional($eventAktif->detail->technical_meeting)->translatedFormat('d F Y H:i') }}</li>
                </ul>
                @else
                <p class="text-muted">Belum ada event aktif saat ini.</p>
                @endif
            </div>
            </div>

        <!-- /.card -->
      </div>
      <!-- ./col -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-text-width"></i>
              Notifikasi (0)
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <ol>
              <li>Lorem ipsum dolor sit amet</li>
              <li>Consectetur adipiscing elit</li>
              <li>Integer molestie lorem at massa</li>
              <li>Facilisis in pretium nisl aliquet</li>
              <li>Nulla volutpat aliquam velit
                <ol>
                  <li>Phasellus iaculis neque</li>
                  <li>Purus sodales ultricies</li>
                  <li>Vestibulum laoreet porttitor sem</li>
                  <li>Ac tristique libero volutpat at</li>
                </ol>
              </li>
              <li>Faucibus porta lacus fringilla vel</li>
              <li>Aenean sit amet erat nunc</li>
              <li>Eget porttitor lorem</li>
            </ol>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- ./col -->
    </div>
  </div>
  </section>
@endsection

@section('javascript')
        <script>
          $(function () {
            $("#example1").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": true,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
          });
        </script>
@endsection
