@extends('layouts.app')

@section('title', 'Verifikasi Pendaftar')

@section('content')
<section class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-between align-items-center">
        <h4>Verifikasi Pendaftar - {{ $selectedEvent->nama_kegiatan_aktif ?? '-' }}</h4>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
      </div>
    </div>
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
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
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
                  @php
                    // Tentukan hak aksi berdasarkan peran dan asal desa
                    $canVerify = false;
                    $canReject = false;
                    $canUpload = false;
                    $canEdit = false;
                    $canRequestChange = false;
                    if ($currentUser->role === 'administrator') {
                        $canVerify = true;
                        $canReject = true;
                        $canEdit = true;
                    } elseif ($currentUser->role === 'admin_desa' && $participant->user && $participant->user->desa_id === $currentUser->desa_id) {
                        $canVerify = true;
                        $canReject = true;
                        if ($participant->status_verifikasi !== 'verifikasi_berhasil') {
                            $canEdit = true;
                        }
                    }
                    // Peserta dapat mengunggah berkas sendiri jika belum verifikasi atau verifikasi gagal
                    if ($currentUser->role === 'peserta' && $participant->user && $participant->user->id === $currentUser->id) {
                        if (in_array($participant->status_verifikasi, ['belum_verifikasi', 'verifikasi_gagal'])) {
                            $canUpload = true;
                            $canRequestChange = true;
                        }
                    }
                    // Batasi aksi berdasarkan status event: jika bukan Aktif, non-admin tidak dapat melakukan aksi apapun
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
                  {{-- Peserta dapat meminta perubahan data jika belum diverifikasi --}}
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
                  @if(! $canUpload && ! $canRequestChange && ! $canEdit && $participant->status_verifikasi !== 'sedang_diverifikasi')
                    &mdash;
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center">Belum ada peserta untuk event ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection