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


    const themeToggleBtn = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme');

    // Deteksi awal saat halaman pertama kali dibuka
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        if (themeToggleBtn) themeToggleBtn.innerText = "☀️ Light Mode";
    } else {
        document.body.classList.remove('dark-mode');
        if (themeToggleBtn) themeToggleBtn.innerText = "🌙 Dark Mode";
    }

    // Aksi ketika tombol sakelar tema diklik oleh user/dosen
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');

            let theme = 'light';
            if (document.body.classList.contains('dark-mode')) {
                theme = 'dark';
                themeToggleBtn.innerText = "☀️ Light Mode";
            } else {
                themeToggleBtn.innerText = "🌙 Dark Mode";
            }

            // Simpan preferensi agar tema tidak berubah saat pindah halaman PHP
            localStorage.setItem('theme', theme);
        });
    }
});
