@extends('layouts.app')

@section('title', 'Tambah Peserta')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Formulir Tambah Peserta</h3>
          </div>
          @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          <form action="{{ route('peserta.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
              </div>
              <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
              </div>
              @if(isset($desas) && count($desas) > 0)
              <div class="form-group">
                <label for="desa_id">Desa</label>
                <select name="desa_id" class="form-control">
                  <option value="">Pilih Desa</option>
                  @foreach($desas as $desa)
                    <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama }}</option>
                  @endforeach
                </select>
              </div>
              @endif
              <!-- Pemilihan cabang dan golongan telah dipisahkan ke halaman khusus.
                   Setelah peserta disimpan, admin desa atau superadmin dapat menempatkan
                   peserta ke satu atau lebih cabang/golongan melalui halaman pemilihan
                   lomba. Oleh karena itu, form pendaftaran peserta tidak lagi
                   menampilkan dropdown cabang dan golongan di sini. -->
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

@section('javascript')
<!-- Tidak ada JavaScript khusus diperlukan di halaman pendaftaran peserta karena
     pemilihan cabang dan golongan dilakukan di halaman terpisah. -->
@endsection