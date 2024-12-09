<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('distribusi/timbangan_kotor'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4 class="header-register ml-4">Tambah Timbangan</h4>
        </div>

        <div class="card-body col-md-15">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message'); ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('distribusi/timbangan/store'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label for="id_pegawai">Pilih Pegawai</label>
                    <select name="id_pegawai" class="form-control" id="id_pegawai" required>
                        <option value="" disabled>Pilih Pegawai</option>
                        <?php foreach ($pegawai as $item): ?>
                            <option value="<?= esc($item['id_pegawai']); ?>"><?= esc($item['nama_pegawai']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>



                <div class="form-group">
                    <label for="id_ruangan">Ruangan</label>
                    <select name="id_ruangan" id="id_ruangan" class="form-control" required>
                        <option value="" disabled selected>Pilih Ruangan</option>
                        <?php foreach ($ruangan as $item): ?>
                            <option value="<?= esc($item['id_ruangan']); ?>"><?= esc($item['nama_ruangan']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Barang di Ruangan</label>
                    <table class="table table-bordered" id="barangTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">Silakan pilih ruangan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <div class="form-group">
                    <label for="id_mesin">Pilih Mesin Cuci</label>
                    <select name="id_mesin" class="form-control" required>
                        <option value="" disabled>Pilih Mesin Cuci</option>
                        <?php foreach ($mesin_cuci as $item): ?>
                            <option value="<?= esc($item['id_mesin']); ?>"><?= esc($item['nama_mesin']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="berat_barang">Berat Barang (Kg)</label>
                    <input type="number" name="berat_barang" class="form-control" required min="0" max="50">
                </div>

                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Simpan</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</section>

<script>
    // Fungsi untuk mengisi No Invoice
    document.addEventListener('DOMContentLoaded', function() {
        const barangData = <?= json_encode($ruangan) ?>;
        const barangTable = document.getElementById('barangTable').querySelector('tbody');
        const ruangSelect = document.getElementById('id_ruangan');

        ruangSelect.addEventListener('change', function() {
            const selectRuangId = this.value;
            const selectedRuang = barangData.find(item => item.id_ruangan == selectRuangId);

            // Clear the table first
            barangTable.innerHTML = '';

            // Check if the room has items
            if (selectedRuang && selectedRuang.barang && selectedRuang.barang.length > 0) {
                selectedRuang.barang.forEach((barang, index) => {
                    const row = `
                    <tr>
                        <td>${barang.nama_barang}</td>
                          <input type="hidden" name="barang[${index}][id_barang]" value="${barang.id_barang}">
                          <input type="hidden" name="barang[${index}][nama_barang]" value="${barang.nama_barang}">
                       <td><input type="number" name="barang[${index}][jumlah]" class="form-control" min="1" placeholder="Jumlah"></td>
                      <td><button type="button" class="btn btn-danger" onclick="hapusBarang(this)">Hapus</button></td>
                    </tr>
                `;
                    barangTable.innerHTML += row;
                });
            } else {
                barangTable.innerHTML = `<tr><td colspan='3' class='text-center'>- Tidak Ada Barang Diruangan -</td></tr>`;
            }
        });

        // Fungsi untuk menghapus barang
        window.hapusBarang = function(index) {
            const inputJumlah = document.getElementById(`jumlah_${index}`);
            inputJumlah.value = 0;
        };

        // Fungsi untuk mendapatkan barang yang akan dikirim ke timbangan kotor
        window.getBarangUntukTimbanganKotor = function() {
            const barangUntukTimbanganKotor = [];
            const rows = barangTable.querySelectorAll('tr');
            rows.forEach((row, index) => {
                const jumlahInput = row.querySelector(`#jumlah_${index}`);
                const jumlah = parseInt(jumlahInput.value);
                const stok = parseInt(row.cells[0].dataset.stok); // Ambil stok dari data-atribut

                // Validasi jumlah barang tidak melebihi stok
                if (jumlah > 0 && jumlah <= stok) {
                    const namaBarang = row.cells[0].textContent;
                    barangUntukTimbanganKotor.push({
                        nama_barang: namaBarang,
                        jumlah: jumlah
                    });
                } else if (jumlah > stok) {
                    alert(`Jumlah barang ${row.cells[0].textContent} tidak boleh melebihi stok yang tersedia.`);
                    jumlahInput.value = stok; // Mengatur input kembali ke stok maksimal
                }
            });
            return barangUntukTimbanganKotor;
        };

        // Fungsi untuk mengirim barang ke timbangan kotor
        window.kirimKeTimbanganKotor = function() {
            const barangUntukTimbanganKotor = getBarangUntukTimbanganKotor();

            // Kirim barang untuk timbangan kotor ke server (misalnya menggunakan fetch)
            if (barangUntukTimbanganKotor.length > 0) {
                // Contoh kirim ke server (ganti dengan URL dan metode yang sesuai)
                fetch('/distribusi/timbangan/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            barang: barangUntukTimbanganKotor
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Tindak lanjut setelah pengiriman sukses
                        alert('Barang berhasil dikirim ke Timbangan Kotor untuk approval');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim barang ke Timbangan Kotor');
                    });
            } else {
                alert('Tidak ada barang yang dipilih untuk dikirim ke Timbangan Kotor');
            }
        };
    });




    function generateInvoiceNo() {
        console.log("Fungsi generateInvoiceNo dijalankan");
        const pegawaiSelect = document.getElementById('id_pegawai');
        const idPegawai = pegawaiSelect.options[pegawaiSelect.selectedIndex].value;
        const idBarangSelect = document.getElementById('id_barang');
        const idBarang = idBarangSelect.options[idBarangSelect.selectedIndex].value || "001"; // Default jika belum dipilih

        const today = new Date();
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0'); // January = 0
        const yyyy = today.getFullYear();

        const noInvoice = `${yyyy}-${mm}-${dd}-${idPegawai}-${idBarang}`;
        console.log("No Invoice: " + noInvoice);
        document.getElementById('no_invoice').value = noInvoice;
        setTimeout(() => {
            document.getElementById('no_invoice').value = noInvoice;
            console.log('No Invoice updated (with delay):', document.getElementById('no_invoice').value);
        }, 100);
    }


    function checkInvoiceValue() {
        console.log('Form submission - No Invoice:', document.getElementById('no_invoice').value);
        return false; // Mencegah form submit untuk test
    }


    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi generateInvoiceNo() seperti yang sudah ada

        // Panggil fungsi generateInvoiceNo() secara otomatis saat halaman dimuat
        generateInvoiceNo();

        // Tambahkan event listener untuk mengupdate No Invoice saat Pegawai dan Barang diisi
        document.getElementById('id_pegawai').addEventListener('change', generateInvoiceNo);
        document.getElementById('id_barang').addEventListener('change', generateInvoiceNo);
    });
</script>

<?= $this->endSection(); ?>