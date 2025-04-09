@extends('layouts.app')
@section('title', 'Edit Ketentuan Usia')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Edit Data Ketentuan Usia</h3>
    </div>

  @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif
  <form action="{{ route('ketentuanusia.update', $ket_usia->id) }}" method="POST">
        @csrf
        @method('PUT')
      <div class="card-body">
            <div class="form-group">
              <label for="nama_cabang_usia">Cabang Lomba</label>
          <select id="cabang_id" name="cabang_id" class="form-control" required>
            <option value="">-- Pilih Cabang --</option>
            @foreach ($cabangs as $cabang)
                <option value="{{ $cabang->id }}" 
                        {{ $ketentuanUsia->cabang_id == $cabang->id ? 'selected' : '' }}>
                    {{ $cabang->nama }}
                </option>
            @endforeach
        </select>
            </div>
            <div class="form-group">
              <label for="golongan_id" class="form-label">Golongan</label>
              <select id="golongan_id" name="golongan_id" class="form-control" required>
                <option value="">-- Pilih Golongan --</option>
                @foreach ($golongans as $golongan)
                    <option value="{{ $golongan->id }}"{{ $ketentuanUsia->golongan_id == $golongan->id ? 'selected' : '' }}>{{ $golongan->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="usia_minimal">Usia Minimal</label>
              <input type="date" class="form-control" name="min_usia" placeholder="Silakan Masukan Usia Minimal">
            </div>
            <div class="form-group">
              <label for="usia_maksimal">Usia Maksimal</label>
              <input type="date" class="form-control" name="max_usia" placeholder="Silakan Masukan Usia Maksimal">
            </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-success" href="{{ route('ketentuanusia.index')}}">Kembali</a>
              </div>
      </form>
      <script>
        // Mengambil Golongan berdasarkan Cabang yang dipilih
        document.getElementById('cabang_id').addEventListener('change', function() {
            let cabang_id = this.value;
            if (cabang_id) {
                fetch(`/get-golongan/${cabang_id}`)
                    .then(response => response.json())
                    .then(data => {
                        let golonganSelect = document.getElementById('golongan_id');
                        golonganSelect.innerHTML = "<option value=''>Pilih Golongan</option>";
                        data.forEach(golongan => {
                            golonganSelect.innerHTML += `<option value="${golongan.id}">${golongan.nama}</option>`;
                        });
                    });
            }
        });
      </script>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

</section>

@endsection