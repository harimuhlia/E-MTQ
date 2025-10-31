@extends('layouts.app')

@section('title', 'Upload Berkas Verifikasi')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Upload Berkas Verifikasi</h3>
          </div>
          @if ($errors->any())
            <div class="alert alert-danger m-3">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('event-participant.upload', $participant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <p>Silakan unggah dokumen yang dibutuhkan untuk verifikasi. Format yang didukung: PDF, JPG, JPEG, PNG (maks 2 MB per file).</p>
              <div class="form-group">
                <label for="kk">Kartu Keluarga</label>
                <input type="file" name="kk" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                @if($participant->kk_path)
                  <small class="form-text text-muted">Berkas sudah diunggah: {{ basename($participant->kk_path) }}</small>
                @endif
              </div>
              <div class="form-group">
                <label for="akta">Akta Kelahiran</label>
                <input type="file" name="akta" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                @if($participant->akta_path)
                  <small class="form-text text-muted">Berkas sudah diunggah: {{ basename($participant->akta_path) }}</small>
                @endif
              </div>
              <div class="form-group">
                <label for="ktp">KTP/Kartu Pelajar</label>
                <input type="file" name="ktp" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                @if($participant->ktp_path)
                  <small class="form-text text-muted">Berkas sudah diunggah: {{ basename($participant->ktp_path) }}</small>
                @endif
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Kirim Berkas</button>
              <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection