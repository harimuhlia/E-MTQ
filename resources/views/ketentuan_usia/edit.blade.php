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
    <form action="{{ isset($ketentuanusium) ? route('ketentuanusia.update', $ketentuanusium->id) : route('ketentuanusia.store') }}" method="POST">
    @csrf
        @if(isset($ketentuanusium))
            @method('PUT')
        @endif
    <div class="card-body">

        {{-- Cabang --}}
        <div class="form-group">
            <label for="cabang_id">Cabang Lomba</label>
            <select name="cabang_id" id="cabang_id" class="form-control" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id }}"
                        {{ isset($ketentuanusium) && $ketentuanusium->cabang_id == $cabang->id ? 'selected' : '' }}>
                        {{ $cabang->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Golongan --}}
        <div class="form-group">
            <label for="golongan_id">Golongan</label>
            <select name="golongan_id" id="golongan_id" class="form-control" required>
                <option value="">-- Pilih Golongan --</option>
                @if(isset($golongans))
                    @foreach($golongans as $golongan)
                        <option value="{{ $golongan->id }}"
                            {{ isset($ketentuanusium) && $ketentuanusium->golongan_id == $golongan->id ? 'selected' : '' }}>
                            {{ $golongan->nama }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Usia Minimal --}}
        <div class="form-group">
            <label for="min_usia">Usia Minimal</label>
            <input type="date" class="form-control" name="min_usia" id="min_usia"
                value="{{ old('min_usia', isset($ketentuanusium) ? $ketentuanusium->min_usia->format('Y-m-d') : '') }}" required>
        </div>

        {{-- Usia Maksimal --}}
        <div class="form-group">
            <label for="max_usia">Usia Maksimal</label>
            <input type="date" class="form-control" name="max_usia" id="max_usia"
                value="{{ old('max_usia', isset($ketentuanusium) ? $ketentuanusium->max_usia->format('Y-m-d') : '') }}" required>
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('ketentuanusia.index') }}" class="btn btn-success">Kembali</a>
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