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
              <div class="form-group">
                <label for="cabang_id">Cabang Lomba</label>
                <select name="cabang_id" id="cabang_id" class="form-control" required>
                  <option value="">Pilih Cabang</option>
                  @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id }}" {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="golongan_id">Golongan</label>
                <select name="golongan_id" id="golongan_id" class="form-control" required>
                  <option value="">Pilih Golongan</option>
                  <!-- Opsi golongan akan diisi secara dinamis melalui JavaScript -->
                </select>
              </div>
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const cabangSelect = document.getElementById('cabang_id');
    const golonganSelect = document.getElementById('golongan_id');
    function loadGolongan(cabangId) {
      if (!cabangId) {
        golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>';
        return;
      }
      fetch('/get-golongan/' + cabangId)
        .then(response => response.json())
        .then(data => {
          golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>';
          data.forEach(function(item) {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nama;
            if ({{ json_encode(old('golongan_id')) }} == item.id) {
              opt.selected = true;
            }
            golonganSelect.appendChild(opt);
          });
        });
    }
    // Inisialisasi jika cabang telah dipilih saat submit sebelumnya
    loadGolongan(cabangSelect.value);
    cabangSelect.addEventListener('change', function() {
      loadGolongan(this.value);
    });
  });
</script>
@endsection