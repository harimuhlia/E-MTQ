@extends('layouts.app')
@section('title', 'Tambah Ketentuan Usia')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Tambah Data Ketentuan Usia</h3>
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
      <form action="{{ route('ketentuanusia.store') }}" method="POST">
      @csrf
      <div class="card-body">
            <div class="form-group">
              <label for="nama_cabang_usia">Cabang Lomba</label>
              <select name="nomorcabang_id" id="nomorcabang_id" class="form-control" required>
                <option value="">Pilih Cabang Lomba</option>
                @foreach ($cabangs as $cabangLomba)
                    <option value="{{ $cabangLomba->id }}">{{ $cabangLomba->nama_cabang }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="nama_golongan">Golongan</label>
              <select id="golongan_id" name="golongan_id" class="form-control">
                <option value="">Pilih Golongan</option>
            </select>
            </div>
            <div class="form-group">
              <label for="usia_minimal">Usia Minimal</label>
              <input type="date" class="form-control" name="usia_minimal" placeholder="Silakan Masukan Usia Minimal">
            </div>
            <div class="form-group">
              <label for="usia_maksimal">Usia Maksimal</label>
              <input type="date" class="form-control" name="usia_maksimal" placeholder="Silakan Masukan Usia Maksimal">
            </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-success" href="{{ route('ketentuanusia.index')}}">Kembali</a>
              </div>
      </form>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>

@endsection
@push('scripts')
<script>
    document.getElementById('cabang_id').addEventListener('change', function () {
        const cabangId = this.value;
        const golonganSelect = document.getElementById('golongan_id');
        
        if (cabangId) {
            golonganSelect.disabled = false;
            fetch(`/golongans-by-cabang/${cabangId}`)
                .then(response => response.json())
                .then(data => {
                    golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>';
                    data.forEach(golongan => {
                        golonganSelect.innerHTML += `<option value="${golongan.id}">${golongan.nama_golongan}</option>`;
                    });
                });
        } else {
            golonganSelect.disabled = true;
            golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>';
        }
    });
</script>
@endpush