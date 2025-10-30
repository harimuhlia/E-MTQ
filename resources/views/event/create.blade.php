@extends('layouts.app')

@section('title', 'Tambah Event Baru')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Form Tambah Event</h3>
          </div>
          <form method="POST" action="{{ route('event.store') }}">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="nama_kegiatan_aktif">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan_aktif" class="form-control @error('nama_kegiatan_aktif') is-invalid @enderror" id="nama_kegiatan_aktif" value="{{ old('nama_kegiatan_aktif') }}" required>
                @error('nama_kegiatan_aktif')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="form-group">
                <label for="tempat_pelaksanaan">Tempat Pelaksanaan</label>
                <input type="text" name="tempat_pelaksanaan" class="form-control @error('tempat_pelaksanaan') is-invalid @enderror" id="tempat_pelaksanaan" value="{{ old('tempat_pelaksanaan') }}">
                @error('tempat_pelaksanaan')
                  <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pendaftaran_mulai">Pendaftaran Mulai</label>
                    <input type="datetime-local" name="pendaftaran_mulai" class="form-control @error('pendaftaran_mulai') is-invalid @enderror" id="pendaftaran_mulai" value="{{ old('pendaftaran_mulai') }}">
                    @error('pendaftaran_mulai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pendaftaran_selesai">Pendaftaran Selesai</label>
                    <input type="datetime-local" name="pendaftaran_selesai" class="form-control @error('pendaftaran_selesai') is-invalid @enderror" id="pendaftaran_selesai" value="{{ old('pendaftaran_selesai') }}">
                    @error('pendaftaran_selesai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="waktu_pelaksanaan_mulai">Pelaksanaan Mulai</label>
                    <input type="date" name="waktu_pelaksanaan_mulai" class="form-control @error('waktu_pelaksanaan_mulai') is-invalid @enderror" id="waktu_pelaksanaan_mulai" value="{{ old('waktu_pelaksanaan_mulai') }}">
                    @error('waktu_pelaksanaan_mulai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="waktu_pelaksanaan_selesai">Pelaksanaan Selesai</label>
                    <input type="date" name="waktu_pelaksanaan_selesai" class="form-control @error('waktu_pelaksanaan_selesai') is-invalid @enderror" id="waktu_pelaksanaan_selesai" value="{{ old('waktu_pelaksanaan_selesai') }}">
                    @error('waktu_pelaksanaan_selesai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="verif1_mulai">Verifikasi Tahap 1 Mulai</label>
                    <input type="datetime-local" name="verif1_mulai" class="form-control @error('verif1_mulai') is-invalid @enderror" id="verif1_mulai" value="{{ old('verif1_mulai') }}">
                    @error('verif1_mulai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="verif1_selesai">Verifikasi Tahap 1 Selesai</label>
                    <input type="datetime-local" name="verif1_selesai" class="form-control @error('verif1_selesai') is-invalid @enderror" id="verif1_selesai" value="{{ old('verif1_selesai') }}">
                    @error('verif1_selesai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="verif2_mulai">Verifikasi Tahap 2 Mulai</label>
                    <input type="datetime-local" name="verif2_mulai" class="form-control @error('verif2_mulai') is-invalid @enderror" id="verif2_mulai" value="{{ old('verif2_mulai') }}">
                    @error('verif2_mulai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="verif2_selesai">Verifikasi Tahap 2 Selesai</label>
                    <input type="datetime-local" name="verif2_selesai" class="form-control @error('verif2_selesai') is-invalid @enderror" id="verif2_selesai" value="{{ old('verif2_selesai') }}">
                    @error('verif2_selesai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sanggah_mulai">Masa Sanggah Mulai</label>
                    <input type="datetime-local" name="sanggah_mulai" class="form-control @error('sanggah_mulai') is-invalid @enderror" id="sanggah_mulai" value="{{ old('sanggah_mulai') }}">
                    @error('sanggah_mulai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sanggah_selesai">Masa Sanggah Selesai</label>
                    <input type="datetime-local" name="sanggah_selesai" class="form-control @error('sanggah_selesai') is-invalid @enderror" id="sanggah_selesai" value="{{ old('sanggah_selesai') }}">
                    @error('sanggah_selesai')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pengumuman_verifikasi">Pengumuman Verifikasi</label>
                    <input type="datetime-local" name="pengumuman_verifikasi" class="form-control @error('pengumuman_verifikasi') is-invalid @enderror" id="pengumuman_verifikasi" value="{{ old('pengumuman_verifikasi') }}">
                    @error('pengumuman_verifikasi')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="technical_meeting">Technical Meeting</label>
                    <input type="datetime-local" name="technical_meeting" class="form-control @error('technical_meeting') is-invalid @enderror" id="technical_meeting" value="{{ old('technical_meeting') }}">
                    @error('technical_meeting')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
              </div>
              <!-- logo_path field could be added later if file upload is implemented -->
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection