<?php 
include 'koneksi.php';
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $id = $_POST['id']; 

    if ($_FILES['foto']['name'] != "") {
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_baru = time() . "." . $ekstensi; 
        move_uploaded_file($_FILES['foto']['tmp_name'], "assets/$foto_baru");
    } else {
        $foto_baru = $_POST['foto_lama']; 
    }

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, foto) VALUES ('$nama', '$harga', '$stok', '$foto_baru')");
    } else {
        mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok', foto='$foto_baru' WHERE id=$id");
    }
    header("Location: index.php");
}

$e_id = $e_nama = $e_harga = $e_stok = $e_foto = "";
if (isset($_GET['edit'])) {
    $res = mysqli_query($conn, "SELECT * FROM produk WHERE id=$_GET[edit]");
    $e = mysqli_fetch_array($res);
    $e_id = $e['id'];
    $e_nama = $e['nama_produk'];
    $e_harga = $e['harga'];
    $e_stok = $e['stok'];
    $e_foto = $e['foto'];
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM produk WHERE id=$_GET[hapus]");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang - Siti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Form <?= ($e_id != "") ? "Edit" : "Tambah" ?> Produk</h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validasiForm()">
            <input type="hidden" name="id" value="<?= $e_id ?>">
            <input type="hidden" name="foto_lama" value="<?= $e_foto ?>">
            
            <input type="text" name="nama" id="nama" placeholder="Nama Produk" value="<?= $e_nama ?>" required>
            <input type="number" name="harga" placeholder="Harga" value="<?= $e_harga ?>" required>
            <input type="number" name="stok" placeholder="Stok" value="<?= $e_stok ?>" required>
            
            <p style="font-size: 12px; margin: 0;">Pilih Foto (Maks 2MB):</p>
            <input type="file" name="foto" id="foto" <?= ($e_id == "") ? "required" : "" ?>>
            
            <button type="submit" name="simpan" class="btn biru">Simpan Data</button>
            <?php if($e_id != ""): ?> <a href="index.php" class="btn">Batal</a> <?php endif; ?>
        </form>

        <hr>

        <h2>Daftar Produk</h2>
        <table>
            <tr>
                <th>No</th> <th>Foto</th> <th>Nama</th> <th>Harga</th> <th>Stok</th> <th>Aksi</th>
            </tr>
            <?php 
            $n = 1;
            $q = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
            while($d = mysqli_fetch_array($q)) { ?>
            <tr>
                <td><?= $n++; ?></td>
                <td><img src="assets/<?= $d['foto']; ?>" width="50" height="50"></td>
                <td><?= $d['nama_produk']; ?></td>
                <td>Rp <?= number_format($d['harga']); ?></td>
                <td><?= $d['stok']; ?></td>
                <td>
                    <a href="?edit=<?= $d['id']; ?>" class="btn biru" style="padding: 5px 10px;">Edit</a>
                    <a href="?hapus=<?= $d['id']; ?>" class="btn merah" style="padding: 5px 10px;" onclick="return confirm('Yakin Hapus?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <script src="script.js"></script>
</body>
</html>