@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<section class="content">
  <div class="container-fluid">
    @if (session('selected_event_id') && isset($selectedEvent))
      <!-- Event-specific dashboard -->
      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <h4>Event Terpilih: {{ $selectedEvent->tahun_event }}</h4>
          <a href="{{ route('home.select_event', 0) }}" class="btn btn-secondary btn-sm">Pilih Event Lain</a>
        </div>
      </div>
      <div class="row">
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
          <div class="info-box bg-success">
            <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Lolos Verifikasi</span>
              <span class="info-box-number">{{ $metrics['verified'] ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Belum Diverifikasi</span>
              <span class="info-box-number">{{ $metrics['pending'] ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tidak Lolos</span>
              <span class="info-box-number">{{ $metrics['rejected'] ?? 0 }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Jadwal Pendaftaran</h3>
            </div>
            <div class="card-body">
              @if($selectedEvent->detail)
                <ul>
                  <li><strong>Nama Kegiatan:</strong> {{ $selectedEvent->detail->nama_kegiatan_aktif }}</li>
                  <li><strong>Waktu Pelaksanaan:</strong>
                    {{ optional($selectedEvent->detail->waktu_pelaksanaan_mulai)->translatedFormat('d F Y') }} -
                    {{ optional($selectedEvent->detail->waktu_pelaksanaan_selesai)->translatedFormat('d F Y') }}
                  </li>
                  <li><strong>Tempat:</strong> {{ $selectedEvent->detail->tempat_pelaksanaan }}</li>
                  <li><strong>Pendaftaran:</strong>
                    {{ optional($selectedEvent->detail->pendaftaran_mulai)->translatedFormat('d F Y H:i') }} -
                    {{ optional($selectedEvent->detail->pendaftaran_selesai)->translatedFormat('d F Y H:i') }}
                  </li>
                  <li><strong>Verifikasi Tahap 1:</strong>
                    {{ optional($selectedEvent->detail->verif1_mulai)->translatedFormat('d F Y H:i') }} -
                    {{ optional($selectedEvent->detail->verif1_selesai)->translatedFormat('d F Y H:i') }}
                  </li>
                  <li><strong>Verifikasi Tahap 2:</strong>
                    {{ optional($selectedEvent->detail->verif2_mulai)->translatedFormat('d F Y H:i') }} -
                    {{ optional($selectedEvent->detail->verif2_selesai)->translatedFormat('d F Y H:i') }}
                  </li>
                  <li><strong>Masa Sanggah:</strong>
                    {{ optional($selectedEvent->detail->sanggah_mulai)->translatedFormat('d F Y H:i') }} -
                    {{ optional($selectedEvent->detail->sanggah_selesai)->translatedFormat('d F Y H:i') }}
                  </li>
                  <li><strong>Pengumuman Verifikasi:</strong> {{ optional($selectedEvent->detail->pengumuman_verifikasi)->translatedFormat('d F Y H:i') }}</li>
                  <li><strong>Technical Meeting:</strong> {{ optional($selectedEvent->detail->technical_meeting)->translatedFormat('d F Y H:i') }}</li>
                </ul>
              @else
                <p class="text-muted">Detail event belum diatur.</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Event selection list -->
      <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <h4>Pilih Tahun Event</h4>
          <a href="{{ route('tahunevent.create') }}" class="btn btn-primary">Buat Event Baru</a>
        </div>
      </div>
      <div class="row">
        @forelse($events as $event)
          <div class="col-md-3 col-sm-6 mb-3">
            <div class="card {{ $event->is_active ? 'bg-light border-success' : 'bg-light' }}">
              <div class="card-body">
                <h5 class="card-title">{{ $event->tahun_event }}</h5>
                <p>Status: {{ $event->is_active ? 'Aktif' : 'Selesai' }}</p>
                <a href="{{ route('home.select_event', $event->id) }}" class="btn btn-primary btn-sm">Kelola Event</a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <p class="text-muted">Belum ada data event. Klik tombol "Buat Event Baru" untuk membuatnya.</p>
          </div>
        @endforelse
      </div>
    @endif
  </div>
</section>
@endsection