@extends('layouts.app')

@section('title', 'Tambah Pengumuman')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Tambah Pengumuman Baru</h3>
          </div>
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('announcements.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
              </div>
              <div class="form-group">
                <label for="content">Isi Pengumuman</label>
                <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection