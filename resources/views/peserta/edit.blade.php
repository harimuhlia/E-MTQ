@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Formulir Edit Peserta</h3>
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
          <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $peserta->name) }}" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $peserta->email) }}" required>
              </div>
              <div class="form-group">
                <label for="password">Password Baru (opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
              </div>
              <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru (opsional)</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
              </div>
              <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $peserta->nik) }}" required>
              </div>
              <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', is_object($peserta->tanggal_lahir) ? $peserta->tanggal_lahir->format('Y-m-d') : $peserta->tanggal_lahir) }}" required>
              </div>
              @if($currentUserRole === 'administrator')
              <div class="form-group">
                <label for="desa_id">Desa</label>
                <select name="desa_id" class="form-control">
                  @foreach($desas as $desa)
                    <option value="{{ $desa->id }}" {{ (old('desa_id', $peserta->desa_id) == $desa->id) ? 'selected' : '' }}>{{ $desa->nama }}</option>
                  @endforeach
                </select>
              </div>
              @endif
              <div class="form-group">
                <label for="cabang_id">Cabang Lomba</label>
                <select name="cabang_id" id="cabang_id" class="form-control" required>
                  <option value="">Pilih Cabang</option>
                  @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id }}" {{ (old('cabang_id', $eventParticipant->cabang_id) == $cabang->id) ? 'selected' : '' }}>{{ $cabang->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="golongan_id">Golongan</label>
                <select name="golongan_id" id="golongan_id" class="form-control" required>
                  <option value="">Pilih Golongan</option>
                  <!-- Opsi golongan akan diisi secara dinamis oleh JavaScript -->
                </select>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Perbarui</button>
              <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
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
            const selectedId = {{ json_encode(old('golongan_id', $eventParticipant->golongan_id)) }};
            if (selectedId == item.id) {
              opt.selected = true;
            }
            golonganSelect.appendChild(opt);
          });
        });
    }
    // Inisialisasi opsi golongan berdasarkan cabang saat ini
    loadGolongan(cabangSelect.value);
    cabangSelect.addEventListener('change', function() {
      loadGolongan(this.value);
    });
  });
</script>
@endsection