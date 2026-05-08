function validasiForm() {
    let nama = document.getElementById('nama').value;
    let foto = document.getElementById('foto').files[0];

    if (nama == "") {
        alert("Nama produk wajib diisi!");
        return false;
    }
    if (foto) {
        if (foto.size > 2000000) {
            alert("File terlalu besar! Maksimal 2MB.");
            return false;
        }
    }
    return true;
}