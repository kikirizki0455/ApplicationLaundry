<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4> <a href="<?= site_url('distribusi'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>Timbangan Kotor</h4>

            <div class="section-header-button">
                <a href="<?= site_url('distribusi/tambah_timbangan'); ?>" class="btn btn-success">Tambah Timbangan</a>
            </div>

        </div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success  alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('message'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('rejectMessage')): ?>
            <div class="alert alert-danger  alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('rejectMessage'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('fieldMessage')): ?>
            <div class="alert alert-danger  alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('message'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger  alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Pegawai</th>
                    <th>Ruangan</th>
                    <th>Barang</th>
                    <th>Berat (Kg)</th>
                    <th>Mesin Cuci</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timbangan as $item): ?>
                    <tr>
                        <td><?= esc($item->no_invoice); ?></td>
                        <td><?= esc($item->nama_pegawai); ?></td>
                        <td><?= esc($item->nama_ruangan); ?></td>

                        <td>
                            <?php foreach ($item->barang_details  as $barang): ?>
                                <p><?= esc($barang->nama_barang); ?> <?= esc($barang->jumlah); ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td><?= esc($item->berat_barang); ?></td>
                        <td><?= esc($item->nama_mesin); ?></td>
                        <td>
                            <span class="badge 
                                    <?= $item->status === 'pending' ? 'badge-danger' : ($item->status === 'process' ? 'badge-primary' : ($item->status === 'delivered' ? 'badge-success' : 'badge-secondary')) ?>">
                                <?= ucfirst(esc($item->status)); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($item->status == 'pending'): ?>
                                <a href="<?= site_url('distribusi/timbangan/approve/' . $item->id_timbangan); ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="<?= site_url('distribusi/timbangan/reject/' . esc($item->id_timbangan)) ?>" class="btn btn-danger btn-sm">Reject</a>
                            <?php else: ?>
                                <span class="text-muted">Tidak ada aksi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</section>


<?= $this->endSection(); ?>