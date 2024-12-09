<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('data_pegawai'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4 class="header-register ml-4">Tambah Pegawai</h4>
        </div>



        <div class="card-body col-md-15">
            <form action="<?= site_url('store_pegawai'); ?>" method="post" autocomplete="off">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label for="nomor_pegawai">Nomor Pegawai</label>
                    <<input type="text" name="nomor_pegawai" class="form-control" required pattern="\d+" title="Hanya angka yang diperbolehkan">
                </div>
                <div class="form-group">
                    <label for="nomor_pegawai">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="role_pegawai">Role Pegawai</label>
                    <select name="role_pegawai" class="form-control" required>
                        <option value="" disabled selected>Select role</option>
                        <option value="pengelola">Pengelola</option>
                        <option value="distribusi">Distribusi</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Password</label>
                    <input type="text" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Simpan</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>

    </div>
</section>

<?= $this->endSection(); ?>