<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<div class="container mt-5">
    <div class="section-header-button">
        <a href="<?= site_url('pengelolaan'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1 class="text-center">Halaman Timbangan</h1>
    <a href="<?= base_url('/timbangan/create'); ?>" class="btn btn-primary mb-3">Tambah Data Timbangan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Timbangan</th>
                <th>Nama Barang</th>
                <th>Berat Barang (kg)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timbangan as $row): ?>
                <tr>
                    <td><?= $row['id_timbangan']; ?></td>
                    <td><?= $row['nama_barang']; ?></td>
                    <td><?= $row['berat_barang']; ?></td>
                    <td><?= $row['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>