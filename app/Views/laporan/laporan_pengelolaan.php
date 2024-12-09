<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <a href="<?= site_url('laporan/laporan'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            <h4>Laporan Pengelolaan</h4>
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
                        <th>Tanggal Pengiriman</th>
                        <th>Ruangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($pelipatan as $key => $t):
                    ?>
                        <tr>
                            <td><?= esc($key + 1) ?></td>
                            <td><?= esc($t['no_invoice']) ?></td>
                            <td><?= esc($t['berat_barang']) ?> kg</td>
                            <td><?= date('d/m/Y', strtotime($t['tanggal_selesai'])) ?></td>
                            <td><?= esc($t['nama_ruangan']); ?></td>
                            <td>
                                <span class="badge 
                                    <?= $t['status'] === 'pending' ? 'badge-danger' : ($t['status'] === 'in_progress' ? 'badge-primary' : ($t['status'] === 'completed' ? 'badge-success' : 'badge-secondary')) ?>">
                                    <?= ucfirst(esc($t['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm"
                                    onclick="showDetail(<?= $t['id_pelipatan'] ?>)">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination Links -->

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
                <div class="table-report">
                    <div class="table-responsive table-responsive-flex">
                        <div class="detail-table">
                            <h5 class="header-judul">Barang Masuk</h5>
                            <table class="table">
                                <tbody id="detailTableBodyBersih">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                </tbody>
                            </table>
                        </div>

                        <div class="detail-table">

                            <h5 class="header-judul">Barang Keluar</h5>
                            <table class="table">
                                <tbody id="detailTableBodyKotor">
                                    <thead>

                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                </tbody>
                            </table>
                        </div>

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
        fetch(`<?= base_url('laporan/laporan-pengelolaan/detail') ?>/${id}`)
            .then(response => response.json())
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }

                const tbodybersih = document.getElementById('detailTableBodyKotor');
                const tbodykotor = document.getElementById('detailTableBodyBersih')
                tbodybersih.innerHTML = '';
                tbodykotor.innerHTML = '';

                response.data.pelipatan.barang.forEach((item, index) => {
                    tbodykotor.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.jumlah}</td>
                        </tr>
                    `;
                });
                response.data.pelipatan.detail_pelipatan.forEach((item, index) => {
                    tbodybersih.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.jumlah_barang}</td>
                        </tr>
                    `;
                });

                $('#detailModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal mengambil data: ' + error.message
                });
            });
    }
</script>
<?= $this->endSection() ?>