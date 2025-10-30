@extends('layouts.app')
@section('title', 'Edit Nomor Cabang')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Formulir Edit Nomor Cabang</h3>
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
  <form action="{{ route('cabang.update', $cabang->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="form-group">
            <label for="tahunevent_id">Tahun Event</label>
            <select name="tahunevent_id" class="form-control" required>
                @foreach ($tahunevents as $event)
                <option value="{{ $event->id }}" {{ $cabang->tahunevent_id == $event->id ? 'selected' : '' }}>{{ $event->tahun_event }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="nama">Nama Cabang</label>
            <input type="text" class="form-control" name="nama" id="nama" value="{{ $cabang->nama }}">
        </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a class="btn btn-success" href="{{ route('cabang.index')}}">Kembali</a>
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