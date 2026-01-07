@extends('layouts.app')

@section('title', 'Pilih Lomba Peserta')

@section('content')
<section class="content">
  <div class="container-fluid">
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Pilih Lomba untuk Peserta</h3>
          </div>
          <form action="{{ route('peserta.assign-lomba', $peserta->id) }}" method="POST">
            @csrf
            <div class="card-body">
              <p><strong>Nama Peserta:</strong> {{ $peserta->name }}<br>
                <strong>NIK:</strong> {{ $peserta->nik }}<br>
                <strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($peserta->tanggal_lahir)->format('d-m-Y') }}
              </p>
              <div class="form-group">
                <label>Daftar Lomba</label>
                <div class="row">
                  @foreach($cabangs as $cabang)
                    <div class="col-md-6 mb-3">
                      <h5>{{ $cabang->nama }}</h5>
                      @if($cabang->golongan && $cabang->golongan->count())
                        @foreach($cabang->golongan as $gol)
                          @php
                            $identifier = $cabang->id . '-' . $gol->id;
                            $checked = isset($existingParticipantIds[$cabang->id]) && $existingParticipantIds[$cabang->id] == $gol->id;
                          @endphp
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lomba[]" id="lomba_{{ $identifier }}" value="{{ $identifier }}" {{ $checked ? 'checked' : '' }}>
                            <label class="form-check-label" for="lomba_{{ $identifier }}">
                              {{ $gol->nama }}
                            </label>
                          </div>
                        @endforeach
                      @else
                        <p><em>Tidak ada golongan</em></p>
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan Pilihan</button>
              <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection