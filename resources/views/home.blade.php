@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="content">
  <div class="container-fluid">
    @if (session('selected_event_id') && isset($selectedEvent))
      <!-- Dashboard untuk event terpilih -->
      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <h4>Event Terpilih: {{ $selectedEvent->nama_kegiatan_aktif }}</h4>
          @php $currentUser = auth()->user(); @endphp
          @if(!$currentUser || $currentUser->role !== 'peserta')
          <a href="{{ route('home.select_event', 0) }}" class="btn btn-secondary btn-sm">Pilih Event Lain</a>
          @endif
        </div>
      </div>
      @php $currentHomeUser = auth()->user(); @endphp
      @if($currentHomeUser && $currentHomeUser->role === 'administrator')
      <div class="row">
        <!-- Informasi jumlah peserta berdasarkan status verifikasi (hanya untuk administrator) -->
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-info">
            <span class="info-box-icon"><i class="far fa-bookmark"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Pendaftar</span>
              <span class="info-box-number">{{ $metrics['total'] ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="far fa-clock"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Belum Verifikasi</span>
              <span class="info-box-number">{{ $metrics['belum_verifikasi'] ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-info">
            <span class="info-box-icon"><i class="far fa-hourglass-half"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sedang Diverifikasi</span>
              <span class="info-box-number">{{ $metrics['sedang_diverifikasi'] ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Verifikasi Gagal</span>
              <span class="info-box-number">{{ $metrics['verifikasi_gagal'] ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-success">
            <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Verifikasi Berhasil</span>
              <span class="info-box-number">{{ $metrics['verifikasi_berhasil'] ?? 0 }}</span>
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Detail jadwal event -->
      <div class="row mt-4">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Jadwal Event</h3>
            </div>
            <div class="card-body">
              <ul class="list-unstyled">
                <li><strong>Nama Kegiatan:</strong> {{ $selectedEvent->nama_kegiatan_aktif }}</li>
                <li><strong>Tempat Pelaksanaan:</strong> {{ $selectedEvent->tempat_pelaksanaan ?? '-' }}</li>
                <li><strong>Pelaksanaan:</strong>
                  {{ optional($selectedEvent->waktu_pelaksanaan_mulai)->translatedFormat('d F Y') ?? '-' }} -
                  {{ optional($selectedEvent->waktu_pelaksanaan_selesai)->translatedFormat('d F Y') ?? '-' }}
                </li>
                <li><strong>Pendaftaran:</strong>
                  {{ optional($selectedEvent->pendaftaran_mulai)->translatedFormat('d F Y H:i') ?? '-' }} -
                  {{ optional($selectedEvent->pendaftaran_selesai)->translatedFormat('d F Y H:i') ?? '-' }}
                </li>
                <li><strong>Verifikasi Tahap 1:</strong>
                  {{ optional($selectedEvent->verif1_mulai)->translatedFormat('d F Y H:i') ?? '-' }} -
                  {{ optional($selectedEvent->verif1_selesai)->translatedFormat('d F Y H:i') ?? '-' }}
                </li>
                <li><strong>Verifikasi Tahap 2:</strong>
                  {{ optional($selectedEvent->verif2_mulai)->translatedFormat('d F Y H:i') ?? '-' }} -
                  {{ optional($selectedEvent->verif2_selesai)->translatedFormat('d F Y H:i') ?? '-' }}
                </li>
                <li><strong>Masa Sanggah:</strong>
                  {{ optional($selectedEvent->sanggah_mulai)->translatedFormat('d F Y H:i') ?? '-' }} -
                  {{ optional($selectedEvent->sanggah_selesai)->translatedFormat('d F Y H:i') ?? '-' }}
                </li>
                <li><strong>Pengumuman Verifikasi:</strong>
                  {{ optional($selectedEvent->pengumuman_verifikasi)->translatedFormat('d F Y H:i') ?? '-' }}
                </li>
                <li><strong>Technical Meeting:</strong>
                  {{ optional($selectedEvent->technical_meeting)->translatedFormat('d F Y H:i') ?? '-' }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {{-- Inbox pengumuman untuk event terpilih --}}
      @if(isset($announcements) && $announcements->count())
      <div class="row mt-4">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h3 class="card-title">Inbox Pengumuman</h3>
              {{-- Link untuk melihat semua pengumuman --}}
              <a href="{{ route('announcements.index') }}" class="btn btn-link btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
              @foreach($announcements as $announcement)
                <div class="mb-3">
                  <h5 class="mb-1">{{ $announcement->title }}</h5>
                  <p class="mb-1">{!! \Illuminate\Support\Str::limit(strip_tags($announcement->content), 150) !!}</p>
                  <small class="text-muted">{{ $announcement->created_at->format('d M Y H:i') }}</small>
                  @if(!$loop->last)
                  <hr>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Daftar peserta event -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Peserta</h3>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Nama Peserta</th>
                    <th>Desa</th>
                    <th>Cabang</th>
                    <th>Golongan</th>
                    <th>Status Verifikasi</th>
                    <th>Permintaan Data</th>
                    <th>Alasan Penolakan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @php $currentUser = auth()->user(); @endphp
                  @forelse($eventParticipants as $participant)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $participant->user->name ?? '-' }}</td>
                      <td>{{ $participant->user->desa->nama ?? '-' }}</td>
                      <td>{{ $participant->cabang->nama ?? '-' }}</td>
                      <td>{{ $participant->golongan->nama ?? '-' }}</td>
                      <td>
                        @switch($participant->status_verifikasi)
                          @case('belum_verifikasi')
                            <span class="badge badge-warning">Belum Verifikasi</span>
                            @break
                          @case('sedang_diverifikasi')
                            <span class="badge badge-info">Sedang Diverifikasi</span>
                            @break
                          @case('verifikasi_gagal')
                            <span class="badge badge-danger">Verifikasi Gagal</span>
                            @break
                          @case('verifikasi_berhasil')
                            <span class="badge badge-success">Verifikasi Berhasil</span>
                            @break
                          @default
                            <span class="badge badge-secondary">-</span>
                        @endswitch
                      </td>
                      <td>
                        {{ $participant->request_message ?? '—' }}
                      </td>
                      <td>
                        {{-- Tampilkan alasan verifikasi gagal untuk peserta --}}
                        {{ $participant->catatan_verifikasi ?? '—' }}
                      </td>
                      <td>
                        @php
                          // Tentukan hak aksi berdasarkan peran dan asal desa
                          $canVerify = false;
                          $canReject = false;
                          $canUpload = false;
                          $canEdit = false;
                          $canRequestChange = false;
                          // Administrator: semua hak
                          if ($currentUser->role === 'administrator') {
                              $canVerify = true;
                              $canReject = true;
                              $canEdit = true;
                              if ($participant->status_verifikasi !== 'verifikasi_berhasil') {
                                  $canRequestChange = true;
                              }
                          } elseif ($currentUser->role === 'admin_desa' && $participant->user && $participant->user->desa_id === $currentUser->desa_id) {
                              // Admin desa tidak dapat memverifikasi/menolak
                              // Mereka hanya dapat mengedit peserta dan meminta perubahan sebelum verifikasi berhasil
                              if ($participant->status_verifikasi !== 'verifikasi_berhasil') {
                                  $canEdit = true;
                                  $canRequestChange = true;
                              }
                              // Admin desa dapat mengunggah berkas untuk peserta dari desanya
                              if ($participant->status_verifikasi !== 'verifikasi_berhasil') {
                                  $canUpload = true;
                              }
                          }
                          // Peserta dapat mengunggah berkas sendiri jika belum verifikasi atau verifikasi gagal
                          if ($currentUser->role === 'peserta' && $participant->user && $participant->user->id === $currentUser->id) {
                              if (in_array($participant->status_verifikasi, ['belum_verifikasi', 'verifikasi_gagal'])) {
                                  $canUpload = true;
                                  $canRequestChange = true;
                              }
                          }
                          // Administrator juga dapat mengunggah berkas jika status belum berhasil
                          if ($currentUser->role === 'administrator' && $participant->status_verifikasi !== 'verifikasi_berhasil') {
                              $canUpload = true;
                          }
                          // Batasi aksi berdasarkan status event: jika bukan Aktif, non-administrator tidak dapat melakukan aksi
                          if (isset($selectedEventStatus) && $selectedEventStatus !== 'Aktif' && $currentUser->role !== 'administrator') {
                              $canUpload = false;
                              $canRequestChange = false;
                              $canEdit = false;
                              $canVerify = false;
                              $canReject = false;
                          }
                        @endphp
                        {{-- Tampilkan tombol upload berkas untuk peserta --}}
                        @if($canUpload)
                          <a href="{{ route('event-participant.upload.form', $participant->id) }}" class="btn btn-primary btn-sm mb-1">Upload Berkas</a>
                        @endif
                        {{-- Peserta atau admin dapat meminta perubahan data jika diizinkan --}}
                        @if($canRequestChange)
                          <a href="{{ route('event-participant.request-change.form', $participant->id) }}" class="btn btn-warning btn-sm mb-1">Minta Perubahan</a>
                        @endif
                        {{-- Admin dan operator dapat mengedit data peserta sebelum verifikasi berhasil --}}
                        @if($canEdit)
                          <a href="{{ route('peserta.edit', $participant->user->id) }}" class="btn btn-info btn-sm mb-1">Edit Data</a>
                        @endif
                        {{-- Verifikasi dan penolakan oleh admin/operator ketika status sedang diverifikasi --}}
                        @if($participant->status_verifikasi === 'sedang_diverifikasi')
                          @if($canVerify)
                            <form action="{{ route('event-participant.verify', $participant->id) }}" method="POST" style="display:inline-block;">
                              @csrf
                              <button type="submit" class="btn btn-success btn-sm mb-1" onclick="return confirm('Terima verifikasi berkas peserta ini?')">Verifikasi</button>
                            </form>
                          @endif
                          @if($canReject)
                            <form action="{{ route('event-participant.reject', $participant->id) }}" method="POST" style="display:inline-block;">
                              @csrf
                              <input type="text" name="reason" class="form-control form-control-sm mb-1" placeholder="Catatan penolakan" required>
                              <button type="submit" class="btn btn-danger btn-sm mb-1">Tolak</button>
                            </form>
                          @endif
                        @endif
                        {{-- Jika tidak ada tindakan tersedia, tampilkan strip --}}
                        @if(!$canUpload && !$canRequestChange && !$canEdit && $participant->status_verifikasi !== 'sedang_diverifikasi')
                          &mdash;
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center">Belum ada peserta untuk event ini.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Daftar event atau pesan untuk peserta -->
      @php $u = auth()->user(); @endphp
      @if($u && $u->role === 'peserta')
        <!-- Jika pengguna adalah peserta dan tidak memiliki event terpilih,
             tampilkan pesan bahwa tidak ada event aktif yang diikuti -->
        <div class="alert alert-info">Anda belum terdaftar pada event aktif mana pun.</div>
      @else
        <!-- Daftar event dengan filter tahun -->
        <div class="row mb-3">
          <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Daftar Event</h4>
            @if(auth()->user()->role === 'administrator')
              <a href="{{ route('event.create') }}" class="btn btn-primary">Buat Event Baru</a>
            @endif
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-12 col-md-4">
            <form method="GET" action="{{ route('home') }}">
              <div class="form-group">
                <label for="year">Filter Tahun</label>
                <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                  <option value="">Semua Tahun</option>
                  @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ (isset($year) && $year == $yr) ? 'selected' : '' }}>{{ $yr }}</option>
                  @endforeach
                </select>
              </div>
            </form>
          </div>
        </div>
        <div class="row">
          @forelse($filteredEvents as $event)
            <div class="col-md-4 col-sm-6 mb-3">
              <div class="card border-{{ $event->statusClass() }}">
                <div class="card-body">
                  <h5 class="card-title">{{ $event->nama_kegiatan_aktif }}</h5>
                  <p class="mb-1"><small class="text-muted">Pendaftaran:</small></p>
                  <p class="mb-2">
                    <small>
                      {{ optional($event->pendaftaran_mulai)->translatedFormat('d M Y H:i') ?? '-' }}
                      &ndash;
                      {{ optional($event->pendaftaran_selesai)->translatedFormat('d M Y H:i') ?? '-' }}
                    </small>
                  </p>
                  <p class="mb-2">Status: <span class="badge badge-{{ $event->statusClass() }}">{{ $event->status() }}</span></p>
                  @php
                    $role = auth()->user()->role;
                    $status = $event->status();
                    $btnLabel = 'Kelola Event';
                    if ($role !== 'administrator') {
                        // Untuk operator desa dan peserta: label bergantung pada status event
                        if ($status === 'Aktif') {
                            $btnLabel = 'Masuk Event';
                        } else {
                            $btnLabel = 'Lihat Event';
                        }
                    }
                  @endphp
                  <a href="{{ route('home.event', $event->slug) }}" class="btn btn-primary btn-sm">{{ $btnLabel }}</a>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <p class="text-muted">Belum ada event untuk tahun ini.</p>
            </div>
          @endforelse
        </div>
      @endif
    @endif
  </div>
</section>
@endsection