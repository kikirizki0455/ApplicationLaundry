<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Update Status Pencucian</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('pencucian/update/' . $pencucian['id_pencucian']); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="mb-3">
                    <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                    <input type="datetime-local" class="form-control" id="waktu_selesai" name="waktu_selesai" value="<?= esc($pencucian['waktu_selesai']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('pencucian'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>