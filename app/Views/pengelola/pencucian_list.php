<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('pengelolaan/pengelola'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Pencucian</h4>
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
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>
            <table class="table table-striped ">

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
                    <?php $no = 1; ?>
                    <?php foreach ($pencucian as $p): ?>
                        <tr>
                            <td><?= $no++; ?></td>
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
                            <td><?= $p['tanggal_mulai'] ? date('Y-m-d H:i:s', strtotime($p['tanggal_mulai'])) : '-'; ?></td>
                            <td><?= $p['tanggal_selesai'] ? date('Y-m-d H:i:s', strtotime($p['tanggal_selesai'])) : '-'; ?></td>
                            <td>

                                <span class="badge 
                                <?= $p['pencucian_status'] === 'pending' ? 'badge-danger' : ($p['pencucian_status'] === 'in_progress' ? 'badge-primary' : ($p['pencucian_status'] === 'ready_move' ? 'badge-success' : 'badge-secondary')) ?>">
                                    <?= ucfirst(esc($p['pencucian_status'])); ?>
                                </span>

                            </td>


                            <td>
                                <?php if ($p['pencucian_status'] === 'pending'): ?>
                                    <form action="<?= site_url('pengelolaan/pencucian/start'); ?>" method="post" style="display:inline;">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="id_cuci" value="<?= esc($p['id_cuci']); ?>">
                                        <button type="submit" class="btn btn-success btn-sm me-2">Mulai</button>
                                    </form>
                                <?php elseif ($p['pencucian_status'] === 'in_progress'): ?>
                                    <form action="<?= site_url('pengelolaan/pencucian/StatMove/' . $p['id_cuci']); ?>" method="post" style="display:inline;">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="id_cuci" value="<?= esc($p['id_cuci']); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm me-2">Selesai</button>
                                    </form>
                                <?php elseif ($p['pencucian_status'] === 'ready_move'): ?>
                                    <a href="<?= site_url('pengelolaan/pencucian/move/' . $p['id_cuci']); ?>" class="btn btn-primary btn-sm">pindahkan</a>

                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>