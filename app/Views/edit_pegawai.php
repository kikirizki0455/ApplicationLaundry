<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Edit Pegawai</h4>
        </div>
        <div class="card-body col-md-15">
            <form action="<?= site_url('update_pegawai'); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_pegawai" value="<?= esc($pegawai->id_pegawai); ?>">
                <div class="form-group">
                    <label for="nomor_pegawai">Nomor Pegawai</label>
                    <input type="text" name="nomor_pegawai" class="form-control" value="<?= esc($pegawai->nomor_pegawai); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nama_pegawai">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" class="form-control" value="<?= esc($pegawai->nama_pegawai); ?>" required>
                </div>
                <div class="form-group">
                    <label for="role_pegawai">Role Pegawai</label>
                    <select name="role_pegawai" class="form-control" required>
                        <option value="pengelola" <?= $pegawai->role_pegawai == 'pengelola' ? 'selected' : ''; ?>>Pengelola</option>
                        <option value="distribusi" <?= $pegawai->role_pegawai == 'distribusi' ? 'selected' : ''; ?>>Distribusi</option>
                        <option value="admin" <?= $pegawai->role_pegawai == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Username </label>
                    <input type="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Password</label>
                    <input type="text" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            </form>

        </div>
    </div>
</section>

<?= $this->endSection(); ?>