@extends('layouts.app')
@section('title', 'Edit Nomor Golongan')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Tambah Edit Nomor Golongan</h3>
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
  <form action="{{ route('golongan.update', $golongan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="form-group">
            <label for="kode_golongan">Cabang</label>
            <select name="cabang_id" class="form-control" required>
              @foreach ($cabangs as $item)
              <option value="{{ $item->id }}" {{ $golongan->cabang_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
              @endforeach
          </select>
        </div>
        <div class="form-group">
            <label for="nama">Golongan</label>
            <input type="text" class="form-control" name="nama" value="{{ $golongan->nama }}">
        </div>
        <div class="form-group">
            <label for="max_usia">Usia Maksimal (tahun)</label>
            <input type="number" class="form-control" name="max_usia" id="max_usia" value="{{ $golongan->max_usia }}" min="0">
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