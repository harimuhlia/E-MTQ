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
        <select name="cabang_id" id="cabang_id" class="select2" required>
          <option value="">Pilih Cabang</option>
          @foreach ($cabangs as $cabang)
              <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
          @endforeach
        </select>
                </div>
                <div class="form-group">
                    <label for="nama_golongan">Golongan</label>
                    <select name="golongan_id" id="golongan_id" class="select2" required>
                      <option value="">Pilih Golongan</option>
                      @foreach ($golongans as $golongan)
                          <option value="{{ $golongan->id }}">{{ $golongan->nama_golongan }}</option>
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