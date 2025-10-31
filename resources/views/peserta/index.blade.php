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
            <th>Status Verifikasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pesertas as $peserta)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $peserta->name }}</td>
              <td>{{ $peserta->email }}</td>
              <td>{{ $peserta->desa->nama ?? '-' }}</td>
              <td>
                @if($peserta->status_verifikasi === 'verified')
                  <span class="badge badge-success">Verified</span>
                @elseif($peserta->status_verifikasi === 'rejected')
                  <span class="badge badge-danger">Rejected</span>
                @else
                  <span class="badge badge-warning">Pending</span>
                @endif
              </td>
              <td>
                @php
                  $canVerify = false;
                  if($currentUser->role === 'administrator') {
                      $canVerify = true;
                  } elseif($currentUser->role === 'admin_desa' && $peserta->desa_id === $currentUser->desa_id) {
                      $canVerify = true;
                  } elseif($currentUser->role === 'peserta' && $peserta->id === $currentUser->id) {
                      $canVerify = true;
                  }
                @endphp
                @if($canVerify && $peserta->status_verifikasi !== 'verified')
                  <form action="{{ route('peserta.verify', $peserta->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Verifikasi peserta ini?')">Verifikasi</button>
                  </form>
                @else
                  &mdash;
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection