@extends('layouts.app')
@section('title', 'Edit Nomor Cabang')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Tambah Edit Nomor Cabang</h3>
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
  <form action="{{ route('nomorcabang.update', $nomorcabangs->slug)}}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
  <form>
    <div class="card-body">
        <div class="form-group">
            <label for="kode_cabang">Kode Cabang</label>
            <input type="text" class="form-control" name="kode_cabang" id="kode_cabang" value="{{ $nomorcabangs->kode_cabang }}">
        </div>
        <div class="form-group">
            <label for="nama_cabang">Nama Cabang</label>
            <input type="text" class="form-control" name="nama_cabang" id="nama_cabang" value="{{ $nomorcabangs->nama_cabang }}">
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