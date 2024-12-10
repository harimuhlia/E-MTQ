@extends('layouts.app')
@section('title', 'Tahun Event')
    
@section('content')
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tabel Seluruh Tahun Event</h3>
              <div class="card-tools">
                <a href="" class="btn btn-success btn-sm"><i class="fas fa-upload" title="Tambah Data"></i> Import</a>
                <a href="{{ route('tahunevent.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus" title="Tambah Data"></i> Tambah</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Tahun Event</th>
                  <th>Slug</th>
                  <th>Edit</th>
                </tr>
                </thead>
                <tbody> 
                  @foreach ($tahunevents as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tahun_event }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>
                      {{-- <a href="#" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></a>
                      <a href="{{ route('datasiswa.edit',$item->id) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a> --}}
                      <form action="{{ route('tahunevent.destroy', $item->slug) }}" method="post">
                        @csrf
                        @method('delete')
                        <a href="{{ route('tahunevent.show', $item->slug) }}" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('tahunevent.edit', $item->slug) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                        @if (Auth()->user()->role == 'admin_global')
                        <button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete the record {{ $item->slug }} ?')"><i class="fas fa-trash-alt"></i></button>
                        @endif
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Tahun Event</th>
                    <th>Slug</th>
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
  {{-- @include('sweetalert::alert') --}}
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

