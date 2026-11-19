<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$isEdit = $kelas !== null;
$action = $isEdit
    ? site_url('kelas/edit/' . (int) $kelas['id_kelas'])
    : site_url('kelas/tambah');
?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold"><?= esc($title) ?></div>

            <div class="card-body">
                <form action="<?= $action ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" id="nama_kelas" name="nama_kelas" class="form-control" maxlength="30"
                               placeholder="Contoh: XII RPL 1"
                               value="<?= esc(old('nama_kelas', $kelas['nama_kelas'] ?? '')) ?>" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="wali_kelas" class="form-label">Wali Kelas</label>
                        <input type="text" id="wali_kelas" name="wali_kelas" class="form-control" maxlength="120"
                               value="<?= esc(old('wali_kelas', $kelas['wali_kelas'] ?? '')) ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?>
                        </button>
                        <a href="<?= site_url('kelas') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>