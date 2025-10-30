@extends('layouts.app')
@section('title', 'Data Operator Desa')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Operator Desa</h3>
            <div class="card-tools">
              <a href="{{ route('operator.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Operator
              </a>
            </div>
          </div>
          <div class="card-body">
            <table id="operatorTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Desa</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($operators as $operator)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $operator->name }}</td>
                  <td>{{ $operator->email }}</td>
                  <td>{{ optional($operator->desa)->nama }}</td>
                  <td>
                    <form action="{{ route('operator.destroy', $operator->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus operator ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('javascript')
<script>
  $(function () {
    $('#operatorTable').DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
    }).buttons().container().appendTo('#operatorTable_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection