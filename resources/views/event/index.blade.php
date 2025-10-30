@extends('layouts.app')

@section('title', 'Daftar Event')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
      <h4>Daftar Event</h4>
      <a href="{{ route('event.create') }}" class="btn btn-primary">Tambah Event</a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th style="width: 50px">#</th>
            <th>Nama Event</th>
            <th>Periode Pendaftaran</th>
            <th>Status</th>
            <th style="width: 200px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($events as $event)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $event->nama_kegiatan_aktif }}</td>
              <td>
                {{ optional($event->pendaftaran_mulai)->translatedFormat('d M Y H:i') ?? '-' }}
                &ndash;
                {{ optional($event->pendaftaran_selesai)->translatedFormat('d M Y H:i') ?? '-' }}
              </td>
              <td>
                <span class="badge badge-{{ $event->statusClass() }}">{{ $event->status() }}</span>
              </td>
              <td>
                <a href="{{ route('event.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('event.destroy', $event->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus event ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
                <a href="{{ route('home.event', $event->slug) }}" class="btn btn-info btn-sm">Kelola</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">Belum ada data event.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection