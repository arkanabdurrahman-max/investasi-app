document.addEventListener('DOMContentLoaded', function () {
    

    const formTambah = document.getElementById('tambahForm');
    const formEdit = document.getElementById('editForm');

    function validasiHarga(event) {
        const hargaBeli = parseFloat(document.getElementById('hargaBeli').value);
        const hargaSekarang = parseFloat(document.getElementById('hargaSekarang').value);

        if (hargaBeli < 0 || hargaSekarang < 0) {
            alert('Peringatan: Harga investasi tidak boleh bernilai minus!');
            event.preventDefault(); 
        }
    }

    if (formTambah) {
        formTambah.addEventListener('submit', validasiHarga);
    }
    if (formEdit) {
        formEdit.addEventListener('submit', validasiHarga);
    }

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('action-hapus')) {
            const konfirmasi = confirm('Apakah Anda yakin ingin menghapus data investasi barang ini?');
            if (!konfirmasi) {
                event.preventDefault();
            }
        }
    });
});