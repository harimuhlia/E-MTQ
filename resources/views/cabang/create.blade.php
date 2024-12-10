@extends('layouts.app')
@section('title', 'Tambah Nomor Cabang')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Tambah Data Nomor Cabang</h3>
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
  <form action="{{ route('nomorcabang.store')}}" enctype="multipart/form-data" method="POST">
    @csrf
  <form>
    <div class="card-body">
        <div class="form-group">
            <label for="kode_cabang">Kode Cabang</label>
            <input type="text" class="form-control" name="kode_cabang" placeholder="Silakan Masukan Kode Cabang">
        </div>
        <div class="form-group">
            <label for="nama_cabang">Nama Cabang</label>
            <input type="text" class="form-control" name="nama_cabang" placeholder="Silakan Masukan Nama Cabang">
        </div>
    <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a class="btn btn-success" href="{{ route('nomorcabang.index')}}">Kembali</a>
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