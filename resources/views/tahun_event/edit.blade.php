@extends('layouts.app')
@section('title', 'Tambah Tahun Event')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Tambah Edit Tahun Event</h3>
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
  <form action="{{ route('tahunevent.update', $tahunevents->slug) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
  <form>
    <div class="card-body">
        <div class="form-group">
            <label for="tahun_event">Tahun Event</label>
            <input type="text" class="form-control" name="tahun_event" id="tahun_event" value="{{ $tahunevents->tahun_event }}">
        </div>
    <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a class="btn btn-success" href="{{ route('tahunevent.index')}}">Kembali</a>
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