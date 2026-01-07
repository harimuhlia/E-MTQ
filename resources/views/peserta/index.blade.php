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
            {{-- Kolom Aksi hanya ditampilkan untuk administrator dan admin desa --}}
            @if($currentUser && in_array($currentUser->role, ['administrator','admin_desa']))
              <th>Aksi</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach($pesertas as $peserta)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $peserta->name }}</td>
              <td>{{ $peserta->email }}</td>
              <td>{{ $peserta->desa->nama ?? '-' }}</td>
              @if($currentUser && in_array($currentUser->role, ['administrator','admin_desa']))
                @php
                  // Tentukan apakah tombol "Pilih Lomba" harus ditampilkan
                  $canAssignLomba = false;
                  // hanya ketika event dipilih
                  if(session('selected_event_id')) {
                      $eventId = session('selected_event_id');
                      $event = \App\Models\DetailEvent::find($eventId);
                      if($currentUser->role === 'administrator') {
                          $canAssignLomba = true;
                      } elseif($currentUser->role === 'admin_desa' && $event && $event->status() === 'Aktif') {
                          // operator desa hanya dapat memilih lomba untuk peserta dari desanya
                          $canAssignLomba = ($peserta->desa_id == $currentUser->desa_id);
                      }
                  }
                @endphp
                <td>
                  @if($canAssignLomba)
                    <a href="{{ route('peserta.select-lomba', $peserta->id) }}" class="btn btn-sm btn-primary">Pilih Lomba</a>
                  @else
                    &mdash;
                  @endif
                </td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection