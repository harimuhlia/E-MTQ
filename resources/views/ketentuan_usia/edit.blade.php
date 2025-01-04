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
      <form action="{{ route('ketentuanusia.update', $ketentuanusias->slug) }}" method="POST">
        @csrf
        @method('PUT')
      <div class="card-body">
            <div class="form-group">
              <label for="nama_cabang_usia">Cabang Lomba</label>
          <select name="cabang_id" id="cabang_id" class="form-control" required>
              <option value="">Pilih Cabang</option>
                @foreach($cabangs as $cabang)
                <option value="{{ $cabang->id }}" 
                  {{ old('cabang_id', $cabang->cabang_id) == $cabang->id ? 'selected' : '' }}>
                  {{ $cabang->nama_cabang }}
                </option>
                @endforeach
          </select>
            </div>
            <div class="form-group">
              <label for="nama_golongan">Golongan</label>
              <select name="golongan_id" id="golongan_id" class="form-control" required>
                <option value="">Pilih Golongan</option>
                  @foreach ($golongans as $golongan)
                    <option value="{{ $golongan->id }}" 
                      {{ old('golongan_id', $golongan->golongan_id) == $golongan->id ? 'selected' : '' }}>
                      {{ $golongan->nama_golongan }}</option>
                  @endforeach
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