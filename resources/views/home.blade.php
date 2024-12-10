@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="section">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
        <h1>Dashboard</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Incidunt neque maiores asperiores quae doloribus beatae voluptas soluta harum fugit eius alias laudantium voluptate, reiciendis molestiae aut nemo numquam nesciunt eligendi.</p>
    </div>
    <!-- /.container-fluid -->
  </div>
</section>
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