@extends('layouts.app')

@section('title', 'Permintaan Perubahan Data')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Permintaan Perubahan Data</h3>
          </div>
          @if ($errors->any())
            <div class="alert alert-danger m-3">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('event-participant.request-change', $participant->id) }}" method="POST">
            @csrf
            <div class="card-body">
              @php $current = auth()->user(); @endphp
              @if($current && $current->role === 'peserta')
                <p>Silakan jelaskan perubahan data yang Anda perlukan. Permintaan ini hanya dapat diajukan sebelum verifikasi final.</p>
              @elseif($current && $current->role === 'admin_desa')
                <p>Silakan isi pesan untuk peserta mengenai perubahan data atau berkas yang diperlukan sebelum verifikasi final.</p>
              @endif
              <div class="form-group">
                <label for="message">Pesan Permintaan</label>
                <textarea name="message" class="form-control" rows="4" required>{{ old('message', $participant->request_message) }}</textarea>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
              <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection