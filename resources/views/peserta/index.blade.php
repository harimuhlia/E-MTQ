@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-between align-items-center">
        <h4>Daftar Peserta</h4>
        @php
          // Determine selected event status to conditionally show the create button.
          $selectedEventStatus = null;
          if(session('selected_event_id')) {
              $ev = \App\Models\DetailEvent::find(session('selected_event_id'));
              $selectedEventStatus = $ev ? $ev->status() : null;
          }
        @endphp
        @if($currentUser && $currentUser->role === 'administrator')
          <!-- Administrator always allowed to add participants -->
          <a href="{{ route('peserta.create') }}" class="btn btn-primary">Tambah Peserta</a>
        @elseif($currentUser && $currentUser->role === 'admin_desa' && $selectedEventStatus === 'Aktif')
          <!-- Operator desa only allowed to add participants during an active event -->
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