<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$warna = [
    'Hadir' => 'success',
    'Sakit' => 'info',
    'Izin'  => 'warning',
    'Alpa'  => 'danger',
];
?>

<div class="mb-3">
    <h4 class="mb-0">Presensi Siswa</h4>
</div>

<!-- Pilih kelas dan tanggal -->
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="get" action="<?= site_url('presensi') ?>" class="row g-2">
            <div class="col-md-5">
                <label for="kelas" class="form-label small text-secondary mb-1">Kelas</label>
                <select id="kelas" name="kelas" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($kelasList as $k) : ?>
                        <option value="<?= (int) $k['id_kelas'] ?>"
                            <?= (int) $k['id_kelas'] === $idKelas ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tanggal" class="form-label small text-secondary mb-1">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control"
                       value="<?= esc($tanggal) ?>" max="<?= esc($hari) ?>"
                       onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<?php if (empty($kelasList)) : ?>

    <div class="alert alert-warning">
        Belum ada kelas. Tambahkan kelas di menu
        <a href="<?= site_url('kelas') ?>">Data Kelas</a> terlebih dahulu.
    </div>

<?php elseif (empty($siswa)) : ?>

    <div class="alert alert-warning">
        Kelas ini belum memiliki siswa. Tambahkan siswa di menu
        <a href="<?= site_url('siswa') ?>">Data Siswa</a>.
    </div>

<?php else : ?>

    <form action="<?= site_url('presensi/simpan') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_kelas" value="<?= $idKelas ?>">
        <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-semibold"><?= esc(tanggal_indo($tanggal)) ?></span>
                <button type="button" class="btn btn-sm btn-outline-success" id="btnSemuaHadir">
                    <i class="bi bi-check2-all me-1"></i>Tandai semua Hadir
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th style="min-width: 200px">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa as $i => $s) : ?>
                            <?php
                            $id      = (int) $s['id_siswa'];
                            $current = $s['status'] ?? 'Hadir';
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($s['nis']) ?></td>
                                <td>
                                    <?= esc($s['nama_siswa']) ?>
                                    <?php if ($s['status'] === null) : ?>
                                        <span class="badge text-bg-secondary ms-1">Belum diabsen</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-nowrap">
                                    <?php foreach ($statusList as $st) : ?>
                                        <input type="radio" class="btn-check"
                                               name="status[<?= $id ?>]"
                                               id="st-<?= $id ?>-<?= $st ?>"
                                               value="<?= $st ?>"
                                               autocomplete="off"
                                               <?= $current === $st ? 'checked' : '' ?>>
                                        <label class="btn btn-sm btn-outline-<?= $warna[$st] ?>"
                                               for="st-<?= $id ?>-<?= $st ?>">
                                            <?= $st ?>
                                        </label>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                           name="keterangan[<?= $id ?>]" maxlength="255"
                                           placeholder="Opsional"
                                           value="<?= esc($s['keterangan'] ?? '') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan Presensi
                </button>
            </div>
        </div>
    </form>

<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('btnSemuaHadir')?.addEventListener('click', function () {
        document.querySelectorAll('input[type="radio"][value="Hadir"]').forEach(function (radio) {
            radio.checked = true;
        });
    });
</script>
<?= $this->endSection() ?>