@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
<section class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!session('selected_event_id'))
      <div class="alert alert-info">Silakan pilih event terlebih dahulu untuk melihat pengumuman.</div>
    @endif
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Pengumuman</h3>
            @if($user && $user->role === 'administrator' && session('selected_event_id'))
              <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-light">Tambah Pengumuman</a>
            @endif
          </div>
          <div class="card-body p-0">
            @if($user && $user->role === 'administrator')
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Judul</th>
                      <th>Dibuat Oleh</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($announcements as $index => $announcement)
                      <tr>
                        <td>{{ $announcements->firstItem() + $index }}</td>
                        <td><a href="{{ route('announcements.show', $announcement->id) }}">{{ $announcement->title }}</a></td>
                        <td>{{ $announcement->user->name ?? '-' }}</td>
                        <td>{{ $announcement->created_at->format('d M Y H:i') }}</td>
                        <td>
                          <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-sm btn-warning">Edit</a>
                          <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="5" class="text-center">Belum ada pengumuman untuk event ini.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            @else
              <div class="p-3">
                @forelse($announcements as $announcement)
                  <div class="mb-4">
                    <h5>{{ $announcement->title }}</h5>
                    <p>{!! nl2br(e($announcement->content)) !!}</p>
                    <small class="text-muted">Diumumkan pada {{ $announcement->created_at->format('d M Y H:i') }} oleh {{ $announcement->user->name ?? '-' }}</small>
                    @if(!$loop->last)
                      <hr>
                    @endif
                  </div>
                @empty
                  <p class="text-center">Belum ada pengumuman untuk event ini.</p>
                @endforelse
              </div>
            @endif
          </div>
        </div>
        <div class="d-flex justify-content-center">
          {{ $announcements->links() }}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection