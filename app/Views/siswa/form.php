<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit = $siswa !== null;
$action = $isEdit
    ? site_url('siswa/edit/' . (int) $siswa['id_siswa'])
    : site_url('siswa/tambah');

$jk = old('jenis_kelamin', $siswa['jenis_kelamin'] ?? '');
$kl = (string) old('id_kelas', $siswa['id_kelas'] ?? '');
?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold"><?= esc($title) ?></div>

            <div class="card-body">
                <form action="<?= $action ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" id="nis" name="nis" class="form-control" maxlength="20"
                               value="<?= esc(old('nis', $siswa['nis'] ?? '')) ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                        <input type="text" id="nama_siswa" name="nama_siswa" class="form-control" maxlength="120"
                               value="<?= esc(old('nama_siswa', $siswa['nama_siswa'] ?? '')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_kelas" class="form-label">Kelas</label>
                        <select id="id_kelas" name="id_kelas" class="form-select" required>
                            <option value="">-- Pilih kelas --</option>
                            <?php foreach ($kelas as $k) : ?>
                                <option value="<?= (int) $k['id_kelas'] ?>"
                                    <?= $kl === (string) $k['id_kelas'] ? 'selected' : '' ?>>
                                    <?= esc($k['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Jenis Kelamin</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                   id="jk_l" value="L" <?= $jk === 'L' ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="jk_l">Laki-laki</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                   id="jk_p" value="P" <?= $jk === 'P' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="jk_p">Perempuan</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?>
                        </button>
                        <a href="<?= site_url('siswa') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>