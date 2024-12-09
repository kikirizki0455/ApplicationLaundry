<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('pengelolaan/pelipatan'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Detail Pelipatan</h4>
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
            <?php if (session()->getFlashdata('messageHapus')): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <?= session()->getFlashdata('messageHapus') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($pelipatan['status'] === 'in_progress'): ?>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Tambah Detail Barang</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?= site_url('pengelolaan/pelipatan/addDetail/' . $pelipatan['id_pelipatan']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label for="id_barang">Pilih Barang</label>
                                        <select name="id_barang" class="form-control" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($barang as $b): ?>
                                                <option value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?> (Stok: <?= $b['stok'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah_barang">Jumlah Barang</label>
                                        <input type="number" name="jumlah_barang" class="form-control" required min="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah Barang</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($pelipatan['status'] === 'ready_move' || $pelipatan['status'] === 'completed'): ?>
                <div class="alert alert-info alert-dismissible show fade">
                    Barang sudah diproses. Anda hanya dapat melihat detail barang.
                    <button class="close" data-dismiss="alert">x</button>
                </div>

            <?php endif; ?>
            <!-- table timbangan -->
            <div class="detail-content">
                <div class="detail-table">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <h3 class="header-judul">Timbangan</h3>
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <?php if ($pelipatan['status'] === 'in_progress'): ?>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($timbangan_barang as $barang): ?>
                                    <tr>
                                        <td><?= esc($barang['nama_barang']) ?></td>
                                        <td><?= esc($barang['jumlah']) ?></td>
                                        <?php if ($pelipatan['status'] === 'in_progress'): ?>

                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="detail-table">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <h3 class="header-judul">Pelipatan</h3>
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <?php if ($pelipatan['status'] === 'in_progress'): ?>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detail_pelipatan as $detail): ?>
                                    <tr>
                                        <td><?= esc($detail['nama_barang']) ?></td>
                                        <td><?= esc($detail['jumlah_barang']) ?></td>
                                        <?php if ($pelipatan['status'] === 'in_progress'): ?>
                                            <td>
                                                <form action="<?= site_url('pengelolaan/pelipatan/delete_detail/' . $detail['id_detail_pelipatan']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($detail_pelipatan)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data detail barang</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= site_url('pengelolaan/pelipatan') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>