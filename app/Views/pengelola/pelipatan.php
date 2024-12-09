<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('pengelolaan/pengelola'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Pelipatan</h4>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <?= session()->getFlashdata('message') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error-barang')): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <?= session()->getFlashdata('error-barang') ?>
                    </div>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Ruangan</th>
                        <th>Barang</th>
                        <th>Pegawai</th>
                        <th>Mesin</th>
                        <th>Berat</th>
                        <th>Bahan</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Status Pencucian</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pelipatan as $key => $p): ?>
                        <tr>
                            <td><?= esc($key + 1) ?></td>
                            <td><?= esc($p['no_invoice']); ?></td>
                            <td><?= esc($p['nama_ruangan']); ?></td>
                            <td>
                                <?php if (!empty($p['barang']) && is_array($p['barang'])): ?>
                                    <?php
                                    $barangList = [];
                                    foreach ($p['barang'] as $barang) {
                                        if (is_array($barang) && isset($barang['nama_barang']) && isset($barang['jumlah'])) {
                                            $barangList[] = esc($barang['nama_barang']) . ' : (' . esc($barang['jumlah']) . 'pcs)';
                                        }
                                    }
                                    echo implode(' , ', $barangList);
                                    ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= esc($p['nama_pegawai']); ?></td>
                            <td><?= esc($p['nama_mesin']); ?></td>
                            <td><?= esc($p['berat_barang']); ?></td>
                            <td>

                                <?php if (!empty($p['bahan']) && is_array($p['bahan'])): ?>
                                    <?php
                                    $bahanList = [];
                                    foreach ($p['bahan'] as $bahan) {
                                        if (is_array($bahan) && isset($bahan['nama_bahan']) && isset($bahan['jumlah_bahan'])) {
                                            $bahanList[] = esc($bahan['nama_bahan']) . ' : (' . esc($bahan['jumlah_bahan']) . 'ml)';
                                        }
                                    }
                                    echo implode(' , ', $bahanList);
                                    ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= esc($p['tanggal_mulai'] ?? '-') ?></td>
                            <td><?= esc($p['tanggal_selesai'] ?? '-') ?></td>
                            <td>

                                <span class="badge 
                                    <?= $p['status'] === 'pending' ? 'badge-danger' : ($p['status'] === 'in_progress' ? 'badge-primary' : ($p['status'] === 'completed' ? 'badge-success' : 'badge-secondary')) ?>">
                                    <?= ucfirst(esc($p['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($p['status'] === 'pending'): ?>
                                    <!-- Tombol 'Mulai' saat status 'pending' -->
                                    <form action="<?= site_url('pengelolaan/pelipatan/start/' . $p['id_pelipatan']) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success btn-sm">Mulai</button>
                                    </form>

                                <?php elseif ($p['status'] === 'in_progress'): ?>
                                    <!-- Tombol 'Detail Barang' dan 'Selesai' saat status 'in_progress' -->
                                    <a href="<?= site_url('pengelolaan/pelipatan/detail/' . $p['id_pelipatan']) ?>" class="btn btn-info btn-sm">Detail </a>
                                    <form action="<?= site_url('pengelolaan/pelipatan/statMove/' . $p['id_pelipatan']) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-warning btn-sm">Selesai</button>
                                    </form>

                                <?php elseif ($p['status'] === 'ready_move' || $p['status'] === 'completed'): ?>
                                    <!-- Tombol 'Detail Barang' (hanya melihat) dan 'Pindahkan' saat status 'ready_move' -->
                                    <a href="<?= site_url('pengelolaan/pelipatan/detail/' . $p['id_pelipatan']) ?>" class="btn btn-info btn-sm">Detail </a>

                                <?php endif; ?>

                                <?php if ($p['status'] === 'ready_move'): ?>
                                    <form action="<?= site_url('pengelolaan/pelipatan/move/' . $p['id_pelipatan']) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-warning btn-sm">Pindahkan</button>
                                    </form>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection() ?>