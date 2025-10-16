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
      <form action="{{ isset($ketentuanusium) ? route('ketentuanusia.update', $ketentuanusium->id) : route('ketentuanusia.store') }}" method="POST">
        @csrf
        @if(isset($ketentuanusium))
            @method('PUT')
        @endif
      <div class="card-body">
            <div class="form-group">
              <label for="nama_cabang_usia">Cabang Lomba</label>
              <select name="cabang_id" id="cabang_id" class="form-control @error('cabang_id') is-invalid @enderror" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id }}"
                        {{ old('cabang_id', isset($ketentuanusium) ? $ketentuanusium->cabang_id : '') == $cabang->id ? 'selected' : '' }}>
                        {{ $cabang->nama }}
                    </option>
                @endforeach
            </select>
            @error('cabang_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
            <div class="form-group">
              <label for="golongan_id" class="form-label">Golongan</label>
            <select name="golongan_id" id="golongan_id" class="form-control @error('golongan_id') is-invalid @enderror" required>
                <option value="">-- Pilih Golongan --</option>
                @if(isset($golongans))
                    @foreach($golongans as $golongan)
                        <option value="{{ $golongan->id }}"
                            {{ old('golongan_id', isset($ketentuanusium) ? $ketentuanusium->golongan_id : '') == $golongan->id ? 'selected' : '' }}>
                            {{ $golongan->nama }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('golongan_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
      {{-- AJAX untuk menampilkan golongan berdasarkan cabang --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#cabang_id').on('change', function () {
    var cabang_id = $(this).val();
    if (cabang_id) {
        $.ajax({
            url: '/get-golongan/' + cabang_id,
            type: 'GET',
            success: function (data) {
                $('#golongan_id').empty();
                $('#golongan_id').append('<option value="">-- Pilih Golongan --</option>');
                $.each(data, function (key, value) {
                    $('#golongan_id').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }
});
</script>
</section>

@endsection
