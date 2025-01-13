@extends('layouts.app')
@section('title', 'Ketentuan Usia')
    
@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tabel Ketentuan Usia</h3>
              <div class="card-tools">
                <a href="" class="btn btn-success btn-sm"><i class="fas fa-upload" title="Tambah Data"></i> Import</a>
                <a href="{{ route('ketentuanusia.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus" title="Tambah Data"></i> Tambah</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Cabang</th>
                  <th>Nama Golongan</th>
                  <th>Usia Minimal</th>
                  <th>Usia Maksimal</th>
                  <th>Keterangan Usia</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody> 
                  @foreach ($ketentuanUsia as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->cabang->nama_cabang }}</td>
                    <td>{{ $item->golongan->nama_golongan }}</td>
                    <td>{{ $item->usia_minimal }}</td>
                    <td>{{ $item->usia_maksimal }}</td>
                    <td>
                        @php
                            $minimal = \Carbon\Carbon::parse($item->usia_minimal);
                            $maksimal = \Carbon\Carbon::parse($item->usia_maksimal);

                            $years = $minimal->diffInYears($maksimal);
                            $months = $minimal->diffInMonths($maksimal) % 12;
                            $days = $minimal->diffInDays($maksimal->subYears($years)->subMonths($months));
                        @endphp
                        {{ $years }} Tahun, {{ $months }} Bulan, {{ $days }} Hari
                    </td>
                    <td>
                      <form action="{{ route('ketentuanusia.destroy', $item->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <a href="{{ route('ketentuanusia.show', $item->id) }}" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Detail</a>
                        <a href="{{ route('ketentuanusia.edit', $item->id) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i> Edit</a>
                        @if (Auth()->user()->role == 'admin_global')
                        <button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete the record {{ $ketentuanusia->id }} ?')"><i class="fas fa-trash-alt"></i> Hapus</button>
                        @endif
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Nama Cabang</th>
                    <th>Nama Golongan</th>
                    <th>Usia Minimal</th>
                    <th>Usia Maksimal</th>
                    <th>Keterangan Usia</th>
                    <th>Edit</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  @include('sweetalert::alert')
@endsection

@section('javascript')
        <script>
          $(function () {
            $("#example1").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": true,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
          });
        </script>
@endsection

