@extends('layouts.app')
@section('title', 'Tambah Operator Desa')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Form Tambah Operator Desa</h3>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          <form action="{{ route('operator.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
              </div>
              <div class="form-group">
                <label for="desa_id">Desa</label>
                <select name="desa_id" id="desa_id" class="form-control" required>
                  <option value="">Pilih Desa</option>
                  @foreach($desas as $desa)
                    <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" name="nik" class="form-control" id="nik" value="{{ old('nik') }}" required>
              </div>
              <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
              </div>
              <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('operator.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection