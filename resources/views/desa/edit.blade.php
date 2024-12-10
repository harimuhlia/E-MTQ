@extends('layouts.app')
@section('title', 'Edit Data Jurusan')

@section('content')
<section class="content">
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
  <div class="card card-primary">
  <div class="card-header">
  <h3 class="card-title">Formulir Edit Data Desa</h3>
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
<form action="{{ route('desa.update', $desa->id)}}" enctype="multipart/form-data" method="POST">
  @csrf
  @method('put')
<form>
  <div class="card-body">
      <div class="form-group">
        <label for="nama_jurusan">Edit Desa</label>
        <input type="text" class="form-control" name="nama_desa" value="{{ $desa->nama_desa }}">
      </div>
  <div class="card-footer">
  <button type="submit" class="btn btn-primary">Submit</button>
  <a class="btn btn-success" href="{{ route('desa.index')}}">Kembali</a>
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
