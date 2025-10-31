@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-between align-items-center">
        <h4>Daftar Peserta</h4>
        @if(in_array($currentUser->role, ['admin_desa', 'administrator']))
          <a href="{{ route('peserta.create') }}" class="btn btn-primary">Tambah Peserta</a>
        @endif
      </div>
    </div>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Desa</th>
            
          </tr>
        </thead>
        <tbody>
          @foreach($pesertas as $peserta)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $peserta->name }}</td>
              <td>{{ $peserta->email }}</td>
              <td>{{ $peserta->desa->nama ?? '-' }}</td>
              
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection