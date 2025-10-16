@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detail Ketentuan Usia</h3>
    <table class="table table-bordered">
        <tr><th>Cabang</th><td>{{ $ketentuanusium->cabang->nama }}</td></tr>
        <tr><th>Golongan</th><td>{{ $ketentuanusium->golongan->nama }}</td></tr>
        <tr><th>Usia Minimal</th><td>{{ $ketentuanusium->min_usia->format('d-m-Y') }}</td></tr>
        <tr><th>Usia Maksimal</th><td>{{ $ketentuanusium->max_usia->format('d-m-Y') }}</td></tr>
    </table>
    <a href="{{ route('ketentuanusia.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
