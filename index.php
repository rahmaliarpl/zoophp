<?php
include 'koneksi.php';

$pesan_sukses = "";
$pesan_error = "";

// Jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama           = mysqli_real_escape_string($conn, $_POST['nama']);
    $email          = mysqli_real_escape_string($conn, $_POST['email']);
    $tanggal        = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $kategori       = mysqli_real_escape_string($conn, $_POST['kategori']);
    $jumlah         = intval($_POST['jumlah']);

    // Daftar Harga Tiket
    $harga_dewasa = 50000;
    $harga_anak   = 35000;
    $harga_lansia = 30000;

    $harga_satuan = 0;
    if ($kategori == "Dewasa") {
        $harga_satuan = $harga_dewasa;
    } elseif ($kategori == "Anak-anak") {
        $harga_satuan = $harga_anak;
    } elseif ($kategori == "Lansia") {
        $harga_satuan = $harga_lansia;
    }

    if ($jumlah > 0 && $harga_satuan > 0) {
        $total_harga = $harga_satuan * $jumlah;

        // Simpan ke database
        $query = "INSERT INTO pemesanan (nama_pemesan, email, tanggal_kunjungan, kategori_tiket, jumlah_tiket, total_harga) 
                  VALUES ('$nama', '$email', '$tanggal', '$kategori', '$jumlah', '$total_harga')";
        
        if (mysqli_query($conn, $query)) {
            $pesan_sukses = "Pemesanan berhasil! Total yang harus dibayar: Rp " . number_format($total_harga, 0, ',', '.');
        } else {
            $pesan_error = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    } else {
        $pesan_error = "Mohon lengkapi formulir dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket Kebun Binatang</title>
    <!-- Menggunakan Bootstrap untuk tampilan yang rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h3>Formulir Pemesanan Tiket Zoo</h3>
                </div>
                <div class="card-body">

                    <?php if (!empty($pesan_sukses)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $pesan_sukses; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($pesan_error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $pesan_error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" min="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Tiket</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="" selected disabled>Pilih Kategori Tiket</option>
                                <option value="Dewasa">Dewasa (Usia 13+ th) - Rp 50.000</option>
                                <option value="Anak-anak">Anak-anak (Usia 3-12 th) - Rp 35.000</option>
                                <option value="Lansia">Lansia (Usia 60+ th) - Rp 30.000</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Tiket</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" value="1" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Pesan Tiket Sekarang</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
