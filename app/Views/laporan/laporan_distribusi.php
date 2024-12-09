// File: app/Views/laporan/distribusi.php
<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <a href="<?= site_url('laporan/laporan'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            <h4>Laporan Distribusi</h4>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Berat Kotor</th>
                        <th>Berat Bersih</th>
                        <th>Tanggal Pengiriman</th>
                        <th>Ruangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timbangan_bersih as $key => $t): ?>
                        <tr>
                            <td><?= esc($key + 1) ?></td>
                            <td><?= esc($t['no_invoice']) ?></td>
                            <td><?= esc($t['berat_kotor']) ?> kg</td>
                            <td><?= esc($t['berat_bersih'] ?? '-') ?> kg</td>
                            <td><?= esc($t['tanggal_pengiriman'] ?? '-') ?></td>
                            <td><?= esc($t['nama_ruangan'] ?? '-') ?></td>
                            <td>
                                <span class="badge 
                                    <?= $t['status'] === 'pending' ? 'badge-danger' : ($t['status'] === 'process' ? 'badge-primary' : ($t['status'] === 'delivered' ? 'badge-success' : 'badge-secondary')) ?>">
                                    <?= ucfirst(esc($t['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm"
                                    onclick="showDetail(<?= $t['id_timbangan_bersih'] ?>)">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="detail-content">
                    <div class="detail-table">
                        <h4 class='header-judul'>Timbangan Kotor</h4>
                        <table class="table">
                            <tbody id="detailTableBodyKotor">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </tbody>
                        </table>
                    </div>
                    <div class="detail-table">
                        <h4 class='header-judul'>Timbangan Bersih</h4>
                        <table class="table">
                            <tbody id="detailTableBodyBersih">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetail(id) {
        fetch(`<?= base_url('laporan/laporan-distribusi/detail') ?>/${id}`)
            .then(response => response.json())
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }

                const tbodyKotor = document.getElementById('detailTableBodyKotor');
                const tbodyBersih = document.getElementById('detailTableBodyBersih');
                // Mengisi tabel
                tbodyKotor.innerHTML = '';
                tbodyBersih.innerHTML = '';
                response.data.timbangan.barang.forEach(item => {
                    tbodyKotor.innerHTML += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.jumlah}</td>
                </tr>
            `;
                });
                response.data.detailBarang.forEach(item => {
                    tbodyBersih.innerHTML += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.jumlah_barang}</td>
                </tr>
            `;
                });


                $('#detailModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil data: ' + error.message);
            });
    }
</script>
<?= $this->endSection() ?>