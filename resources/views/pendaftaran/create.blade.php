<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Lomba</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>Pendaftaran Lomba</h2>

    <form action="{{ route('pendaftaran.store') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="cabang_lomba">Cabang Lomba</label>
            <select name="nomorcabang_id" id="nomorcabang_id" class="form-control" required>
                <option value="">Pilih Cabang Lomba</option>
                @foreach ($nomorcabangs as $cabangLomba)
                    <option value="{{ $cabangLomba->id }}">{{ $cabangLomba->nama_cabang }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="golongan">Golongan</label>
            <select name="golongan" id="golongan" class="form-control" required>
                <option value="">Pilih Golongan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>
</div>

<script>
    // Ketika cabang lomba dipilih
    $('#nomorcabang_id').change(function () {
        var cabangLombaId = $(this).val();
        if (cabangLombaId) {
            // Mengambil golongan berdasarkan cabang lomba yang dipilih
            $.ajax({
                url: '/golongan-by-cabang',
                method: 'GET',
                data: {
                    nomorcabang_id: cabangLombaId
                },
                success: function (response) {
                    var golonganSelect = $('#golongan');
                    golonganSelect.empty(); // Kosongkan pilihan golongan
                    golonganSelect.append('<option value="">Pilih Golongan</option>');
                    $.each(response, function (index, golongan) {
                        golonganSelect.append('<option value="' + golongan.id + '">' + golongan.nama_golongan + '</option>');
                    });
                }
            });
        } else {
            $('#golongan').empty();
            $('#golongan').append('<option value="">Pilih Golongan</option>');
        }
    });
</script>

</body>
</html>