@extends('layouts.app')
@section('title', 'Tambah Nomor Golongan')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Tambah Data Nomor Golongan</h3>
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
  <form action="{{ route('golongan.store')}}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="kode_golongan">Kode Golongan</label>
            <select name="nomorcabang_id" id="nomorcabang_id" class="form-control" required>
              <option value="">Pilih Cabang Lomba</option>
              @foreach ($nomorcabangs as $cabangLomba)
                  <option value="{{ $cabangLomba->id }}">{{ $cabangLomba->nama_cabang }}</option>
              @endforeach
          </select>
        </div>
        <div class="form-group">
            <label for="nama_golongan">Nama Golongan</label>
            <input type="text" class="form-control" name="nama_golongan" id="nama_golongan" placeholder="Silakan Masukan Nama Golongan">
        </div>
    <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a class="btn btn-success" href="{{ route('golongan.index')}}">Kembali</a>
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