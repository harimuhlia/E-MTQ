@extends('layouts.app')

@section('title', 'Verifikasi Peserta')

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
        <h4>Verifikasi Peserta</h4>
        <a href="{{ route('event-participant.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Detail Peserta</h3>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">Nama</dt>
          <dd class="col-sm-9">{{ $participant->user->name ?? '-' }}</dd>
          <dt class="col-sm-3">NIK</dt>
          <dd class="col-sm-9">{{ $participant->user->nik ?? '-' }}</dd>
          <dt class="col-sm-3">Email</dt>
          <dd class="col-sm-9">{{ $participant->user->email ?? '-' }}</dd>
          <dt class="col-sm-3">Desa</dt>
          <dd class="col-sm-9">{{ $participant->user->desa->nama ?? '-' }}</dd>
          <dt class="col-sm-3">Cabang</dt>
          <dd class="col-sm-9">{{ $participant->cabang->nama ?? '-' }}</dd>
          <dt class="col-sm-3">Golongan</dt>
          <dd class="col-sm-9">{{ $participant->golongan->nama ?? '-' }}</dd>
          <dt class="col-sm-3">Status Verifikasi</dt>
          <dd class="col-sm-9">
            @switch($participant->status_verifikasi)
              @case('belum_verifikasi')<span class="badge badge-warning">Belum Verifikasi</span>@break
              @case('sedang_diverifikasi')<span class="badge badge-info">Sedang Diverifikasi</span>@break
              @case('verifikasi_gagal')<span class="badge badge-danger">Verifikasi Gagal</span>@break
              @case('verifikasi_berhasil')<span class="badge badge-success">Verifikasi Berhasil</span>@break
              @default <span class="badge badge-secondary">-</span>
            @endswitch
          </dd>
          @if($participant->request_message)
            <dt class="col-sm-3">Permintaan Perubahan</dt>
            <dd class="col-sm-9">{{ $participant->request_message }}</dd>
          @endif
        </dl>
        <hr>
        <h5>Berkas Verifikasi</h5>
        <ul>
          <li>Kartu Keluarga: 
            @if($participant->kk_path)
              <a href="{{ asset('storage/'.$participant->kk_path) }}" target="_blank">{{ basename($participant->kk_path) }}</a>
            @else
              <em>Belum diunggah</em>
            @endif
          </li>
          <li>Akta Kelahiran: 
            @if($participant->akta_path)
              <a href="{{ asset('storage/'.$participant->akta_path) }}" target="_blank">{{ basename($participant->akta_path) }}</a>
            @else
              <em>Belum diunggah</em>
            @endif
          </li>
          <li>KTP/Kartu Pelajar: 
            @if($participant->ktp_path)
              <a href="{{ asset('storage/'.$participant->ktp_path) }}" target="_blank">{{ basename($participant->ktp_path) }}</a>
            @else
              <em>Belum diunggah</em>
            @endif
          </li>
          <li>Foto Peserta: 
            @if($participant->photo_path)
              <a href="{{ asset('storage/'.$participant->photo_path) }}" target="_blank">{{ basename($participant->photo_path) }}</a>
            @else
              <em>Belum diunggah</em>
            @endif
          </li>
        </ul>
      </div>
      <div class="card-footer">
        @php
          // Tentukan apakah tombol verifikasi/penolakan dapat ditampilkan
          // Hanya administrator yang dapat memverifikasi atau menolak.
          $canVerify = false;
          $canReject = false;
          if ($currentUser->role === 'administrator') {
              $canVerify = true;
              $canReject = true;
          }
          // Non administrator tidak boleh menverifikasi/menolak apapun. Event status juga harus aktif.
          if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif' && $currentUser->role !== 'administrator') {
              $canVerify = false;
              $canReject = false;
          }
        @endphp
        @if($participant->status_verifikasi === 'sedang_diverifikasi')
          @if($canVerify)
            <form action="{{ route('event-participant.verify', $participant->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Terima verifikasi berkas peserta ini?')">
              @csrf
              <button type="submit" class="btn btn-success">Verifikasi</button>
            </form>
          @endif
          @if($canReject)
            <form action="{{ route('event-participant.reject', $participant->id) }}" method="POST" class="d-inline-block">
              @csrf
              <div class="form-group mb-0 mr-2 d-inline-block">
                <input type="text" name="reason" class="form-control form-control-sm" placeholder="Catatan penolakan" required>
              </div>
              <button type="submit" class="btn btn-danger">Tolak</button>
            </form>
          @endif
        @else
          <p class="mb-0"><em>Tindakan verifikasi tersedia setelah peserta mengunggah berkas (status sedang diverifikasi).</em></p>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection