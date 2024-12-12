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
        <label for="nama_cabang">Cabang Lomba</label>
        <select name="nama_cabang" id="nomorcabang_id" class="form-control" required>
            <option value="">Pilih Cabang Lomba</option>
            @foreach ($nomorcabangs as $cabangLomba)
                <option value="{{ $cabangLomba->id }}">{{ $cabangLomba->nama_cabang }}</option>
            @endforeach
        </select>
                </div>
                <div class="form-group">
                    <label for="nama_golongan">Golongan</label>
                    <select name="nama_golongan" id="golongan" class="form-control" required>
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

@section('javascript')
<script>
// Ketika cabang lomba dipilih
$('#nomorcabang_id').change(function () {
  var cabangLombaId = $(this).val();
  if (cabangLombaId) {
      // Mengambil golongan berdasarkan cabang lomba yang dipilih
      $.ajax({
          url: '/golongan-by-cabang',
          method: 'GET',
          data: {
              nomorcabang_id: cabangLombaId
          },
          success: function (response) {
              var golonganSelect = $('#golongan');
              golonganSelect.empty(); // Kosongkan pilihan golongan
              golonganSelect.append('<option value="">Pilih Golongan</option>');
              $.each(response, function (index, golongan) {
                  golonganSelect.append('<option value="' + golongan.id + '">' + golongan.nama_golongan + '</option>');
              });
          }
      });
  } else {
      $('#golongan').empty();
      $('#golongan').append('<option value="">Pilih Golongan</option>');
  }
});
</script>
@endsection