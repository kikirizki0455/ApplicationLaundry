<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Bahan</h4>
        </div>
        <div class="card-body">
            <form action="<?= site_url('pengelolaan/pencucian/storeBahan'); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_cuci" value="<?= esc($pencucian['id_cuci']); ?>">

                <label for="id_bahan">Pilih Bahan:</label>
                <select name="id_bahan[]" multiple>
                    <?php foreach ($bahan as $b): ?>
                        <option value="<?= esc($b['id_bahan']); ?>"><?= esc($b['nama_bahan']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="jumlah_bahan">Jumlah Bahan:</label>
                <input type="number" name="jumlah_bahan[]" required>

                <label for="waktu_estimasi">Waktu Estimasi (menit):</label>
                <input type="number" name="waktu_estimasi" value="<?= esc($pencucian['waktu_estimasi']); ?>" required>

                <button type="submit">Simpan</button>
            </form>

        </div>
    </div>
</section>

<script>
    document.getElementById('bahanContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('tambahBahan')) {
            // Buat elemen baru untuk bahan
            const bahanContainer = document.getElementById('bahanContainer');
            const newBahanItem = document.createElement('div');
            newBahanItem.classList.add('mb-3', 'bahan-item');
            newBahanItem.innerHTML = `
                <label for="id_bahan" class="form-label">Pilih Bahan</label>
                <div class="input-group">
                    <select class="form-control" name="id_bahan[]" required>
                        <?php foreach ($bahan as $item): ?>
                            <option value="<?= $item['id_bahan']; ?>"><?= $item['nama_bahan']; ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="jumlah[]" class="form-control me-2" placeholder="Jumlah (ml)" required min="0">
                    <button type="button" class="btn btn-success tambahBahan me-2"><i class="fas fa-plus"></i></button>
                    <button type="button" class="btn btn-danger removeBahan"><i class="fas fa-minus"></i></button>
                </div>
            `;
            bahanContainer.appendChild(newBahanItem);
        } else if (e.target.classList.contains('removeBahan')) {
            // Hapus elemen bahan yang dipilih
            const bahanItem = e.target.closest('.bahan-item');
            if (bahanItem) {
                bahanItem.remove();
            }
        }
    });
</script>

<?= $this->endSection(); ?>