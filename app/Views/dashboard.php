<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header" style="text-align: center;">
            <h4>Dashboard Admin</h4>
        </div>
        <div class="card-body">

            <?php if (session()->getFlashdata('welcome_message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Selamat datang, <?= esc(session('nama')) ?>!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Start of the Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pegawai</h4>
                            </div>
                            <div class="card-body">
                                <?= count($pegawai); ?> <!-- Menampilkan total pegawai -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Barang</h4>
                            </div>
                            <div class="card-body">
                                <?= count($barang); ?> <!-- Menampilkan total barang -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Bahan</h4>
                            </div>
                            <div class="card-body">
                                <?= count($bahan); ?> <!-- Menampilkan total bahan -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pencucian</h4>
                            </div>
                            <div class="card-body">
                                <?= count($pencucian); ?> <!-- Menampilkan total pencucian -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="row">
                <!-- Kolom Kiri untuk Chart -->
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistik Penggunaan Bahan </h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-primary" id="day-btn">Hari</a>
                                    <a href="#" class="btn" id="week-btn">Minggu</a>
                                    <a href="#" class="btn" id="month-btn">Bulan</a>
                                    <a href="#" class="btn" id="year-btn">Tahun</a>
                                </div>
                            </div>
                        </div>





                        <div class="card-body">
                            <canvas id="myChart" height="712" width="1174" style="display: block; height: 356px; width: 587px;" class="chartjs-render-monitor"></canvas>
                            <!-- Statistik Penggunaan Bahan Hari Ini -->
                            <div class="statistic-details mt-sm-4">
                                <div class="statistic-details-item">
                                    <span class="text-muted">
                                        <!-- Penggunaan Bahan Hari Ini -->
                                        <?php if (isset($penggunaan_bahan_kemarin) && $penggunaan_bahan_kemarin > 0): ?>
                                            <?php if ($persentase_hari_ini < 0): ?>
                                                <span class="text-danger">
                                                    <i class="fas fa-caret-down"></i>
                                                </span> <?= abs($persentase_hari_ini) ?>%
                                            <?php else: ?>
                                                <span class="text-primary">
                                                    <i class="fas fa-caret-up"></i>
                                                </span> <?= $persentase_hari_ini ?>%
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Persentase belum tersedia</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="detail-value"><?= isset($penggunaan_bahan_hari_ini) ? $penggunaan_bahan_hari_ini : '0' ?> ML</div>
                                    <div class="detail-name">Penggunaan Bahan Hari Ini</div>
                                </div>
                                <!-- Penggunaan Bahan Minggu Ini, Bulan Ini, dan Tahun Ini tetap sama -->
                                <div class="statistic-details-item">
                                    <span class="text-muted">
                                        <?php if (isset($penggunaan_bahan_minggu_kemarin) && $penggunaan_bahan_minggu_kemarin > 0): ?>
                                            <?php if ($persentase_minggu_ini < 0): ?>
                                                <span class="text-danger">
                                                    <i class="fas fa-caret-down"></i>
                                                </span> <?= abs($persentase_minggu_ini) ?>%
                                            <?php else: ?>
                                                <span class="text-primary">
                                                    <i class="fas fa-caret-up"></i>
                                                </span> <?= $persentase_minggu_ini ?>%
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Persentase belum tersedia</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="detail-value"><?= isset($penggunaan_bahan_minggu_ini) ? $penggunaan_bahan_minggu_ini : '0' ?> ML</div>
                                    <div class="detail-name">Penggunaan Bahan Minggu Ini</div>
                                </div>

                                <!-- Penggunaan Bahan Bulan Ini -->
                                <div class="statistic-details-item">
                                    <span class="text-muted">
                                        <?php if (isset($penggunaan_bahan_bulan_kemarin) && $penggunaan_bahan_bulan_kemarin > 0): ?>
                                            <?php if ($persentase_bulan_ini < 0): ?>
                                                <span class="text-danger">
                                                    <i class="fas fa-caret-down"></i>
                                                </span> <?= abs($persentase_bulan_ini) ?>%
                                            <?php else: ?>
                                                <span class="text-primary">
                                                    <i class="fas fa-caret-up"></i>
                                                </span> <?= $persentase_bulan_ini ?>%
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Persentase belum tersedia</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="detail-value"><?= isset($penggunaan_bahan_bulan_ini) ? $penggunaan_bahan_bulan_ini : '0' ?> ML</div>
                                    <div class="detail-name">Penggunaan Bahan Bulan Ini</div>
                                </div>

                                <!-- Penggunaan Bahan Tahun Ini -->
                                <div class="statistic-details-item">
                                    <span class="text-muted">
                                        <?php if (isset($penggunaan_bahan_tahun_kemarin) && $penggunaan_bahan_tahun_kemarin > 0): ?>
                                            <?php if ($persentase_tahun_ini < 0): ?>
                                                <span class="text-danger">
                                                    <i class="fas fa-caret-down"></i>
                                                </span> <?= abs($persentase_tahun_ini) ?>%
                                            <?php else: ?>
                                                <span class="text-primary">
                                                    <i class="fas fa-caret-up"></i>
                                                </span> <?= $persentase_tahun_ini ?>%
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Persentase belum tersedia</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="detail-value"><?= isset($penggunaan_bahan_tahun_ini) ? $penggunaan_bahan_tahun_ini : '0' ?> ML</div>
                                    <div class="detail-name">Penggunaan Bahan Tahun Ini</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan untuk Proses Pencucian dan Barang yang Dicuci -->
                <div class="col-lg-4">
                    <div class="row">
                        <!-- Card Jumlah Proses Pencucian -->
                        <div class="col-lg-12">
                            <div class="card gradient-bottom">
                                <div class="card-header">
                                    <h4>Jumlah Proses Pencucian</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (is_array($pelipatan) || $pelipatan instanceof Traversable) {
                                        $pencucian_proses = array_filter($pelipatan, function ($p) {
                                            return isset($p['status']) && in_array($p['status'], ['pending', 'in_progress', 'ready_move']);
                                        });
                                        $jumlah_proses = count($pencucian_proses);
                                    } else {
                                        $jumlah_proses = 0;
                                    }
                                    ?>
                                    <ul class="list-unstyled">
                                        <li class="media">
                                            <div class="media-body">
                                                <div class="float-right">
                                                    <div class="font-weight-600 text-muted text-small"><?= $jumlah_proses ?> Proses</div>
                                                </div>
                                                <div class="media-title">Jumlah Pencucian</div>
                                                <div class="mt-1">
                                                    <div class="budget-price">
                                                        <div class="budget-price-square bg-warning" style="width: 100%;"></div>
                                                        <div class="budget-price-label">Total: <?= $jumlah_proses ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Card Jumlah Barang yang Dicuci -->
                        <div class="col-lg-12">
                            <div class="card gradient-bottom">
                                <div class="card-header">
                                    <h4>Barang yang Dicuci</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (is_array($barang) || $barang instanceof Traversable) {
                                        $total_barang_dicuci = array_reduce($barang, function ($carry, $item) {
                                            if (isset($item->stok_dicuci)) { // Mengakses properti stok_dicuci dari objek stdClass
                                                $carry += (int)$item->stok_dicuci; // Tambahkan nilai stok_dicuci
                                            }
                                            return $carry;
                                        }, 0);
                                    } else {
                                        $total_barang_dicuci = 0; // Default jika $barang tidak valid
                                    }
                                    ?>
                                    <ul class="list-unstyled">
                                        <li class="media">
                                            <div class="media-body">
                                                <div class="float-right">
                                                    <div class="font-weight-600 text-muted text-small"><?= $total_barang_dicuci ?> Barang</div>
                                                </div>
                                                <div class="media-title">Jumlah Barang yang Dicuci</div>
                                                <div class="mt-1">
                                                    <div class="budget-price">
                                                        <div class="budget-price-square bg-success" style="width: 100%;"></div>
                                                        <div class="budget-price-label">Total: <?= $total_barang_dicuci ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BATAS SUCI -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bahan Kritis</h4>

                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive table-invoice">
                                <table class="table">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Bahan</th>
                                            <th>Stok Bahan Kritis</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bahan as $b): ?>
                                            <tr class="<?= $b->stok_bahan < 12 ? 'critical-stock' : '' ?>">
                                                <td><?= esc($b->id_bahan); ?></td>
                                                <td><?= esc($b->nama_bahan); ?></td>
                                                <td>
                                                    <?php if (isset($bahan_kritis[$b->id_bahan])): ?>
                                                        <?= esc($bahan_kritis[$b->id_bahan]->stok_bahan); ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($b->stok_bahan < 12): ?>
                                                        <a href="<?= base_url('dashboard/show_modal/' . $b->id_bahan); ?>" class="btn btn-warning btn-sm">
                                                            Tambah Bahan
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



</section>

<!-- Modal Tambah Stok -->
<?php if (!empty($modalBahan) && !empty($modalBahanId)): ?>
    <div class="modal fade show d-block" id="tambahStokModal" tabindex="-1" aria-labelledby="tambahStokModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahStokModalLabel">Tambah Stok</h5>
                </div>
                <form action="<?= base_url('dashboard/tambah_stok'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_bahan" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" value="<?= esc($modalBahan); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="stok_tambah" class="form-label">Tambah Stok</label>
                            <input type="number" class="form-control" id="stok_tambah" name="stok_tambah" required min="1">
                        </div>
                        <input type="hidden" name="id_bahan" id="id_bahan" value="<?= esc($modalBahanId); ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let myChart; // Global variable for chart

    function createChart(labels, data, type) {
        const ctx = document.getElementById('myChart').getContext('2d');

        // Remove old chart if it exists
        if (myChart) {
            myChart.destroy();
        }

        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penggunaan Bahan (ML)',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Bahan (ML)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: `Penggunaan Bahan per ${type}`
                    }
                }
            }
        });
    }

    function generateDailyData() {
        const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Use the actual daily usage data from the controller
        const dailyData = [
            <?php echo json_encode($daily['jumlah_penggunaan'] ?? 0); ?>
        ];

        createChart(days, dailyData, 'Hari');
    }

    function generateWeeklyData() {
        const weeks = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'];

        // Use the actual weekly usage data from the controller
        const weeklyData = [
            <?php echo json_encode($weekly['jumlah_penggunaan'] ?? 0); ?>
        ];

        createChart(weeks, weeklyData, 'Minggu');
    }

    function generateMonthlyData() {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Use the actual monthly usage data from the controller
        const monthlyData = [
            <?php echo json_encode($monthly['jumlah_penggunaan'] ?? 0); ?>
        ];

        createChart(months, monthlyData, 'Bulan');
    }

    function generateYearlyData() {
        const currentYear = new Date().getFullYear();
        const years = [
            currentYear - 4,
            currentYear - 3,
            currentYear - 2,
            currentYear - 1,
            currentYear
        ];

        // Use the actual yearly usage data from the controller
        const yearlyData = [
            <?php echo json_encode($yearly['jumlah_penggunaan'] ?? 0); ?>
        ];

        createChart(years, yearlyData, 'Tahun');
    }

    // Initialize default chart
    generateDailyData();

    // Event listeners for buttons
    document.getElementById('day-btn').addEventListener('click', function(event) {
        event.preventDefault();
        setActiveButton(this);
        generateDailyData();
    });

    document.getElementById('week-btn').addEventListener('click', function(event) {
        event.preventDefault();
        setActiveButton(this);
        generateWeeklyData();
    });

    document.getElementById('month-btn').addEventListener('click', function(event) {
        event.preventDefault();
        setActiveButton(this);
        generateMonthlyData();
    });

    document.getElementById('year-btn').addEventListener('click', function(event) {
        event.preventDefault();
        setActiveButton(this);
        generateYearlyData();
    });

    function setActiveButton(activeBtn) {
        // Remove 'btn-primary' class from all buttons
        ['day-btn', 'week-btn', 'month-btn', 'year-btn'].forEach(id => {
            const btn = document.getElementById(id);
            btn.classList.remove('btn-primary');
        });

        // Add 'btn-primary' to the active button
        activeBtn.classList.add('btn-primary');
    }
</script>




<?= $this->endSection(); ?>