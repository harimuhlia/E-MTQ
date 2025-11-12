@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">{{ $announcement->title }}</h3>
          </div>
          <div class="card-body">
            <p>{!! nl2br(e($announcement->content)) !!}</p>
            <p class="text-muted">Diumumkan pada {{ $announcement->created_at->format('d M Y H:i') }} oleh {{ $announcement->user->name ?? '-' }}</p>
          </div>
          <div class="card-footer">
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection