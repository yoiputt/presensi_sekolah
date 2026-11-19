<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$bulan = [
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];
$ts           = strtotime($tanggal);
$tanggalIndo  = date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
?>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
    <div>
        <h4 class="mb-1">Selamat datang, <?= esc(session()->get('nama_guru')) ?></h4>
        <p class="text-secondary mb-0">
            <?= $tanggalIndo ?>
            <?php if ($tahunAktif) : ?>
                &middot; Tahun ajaran <?= esc($tahunAktif['tahun_ajaran']) ?> (<?= esc($tahunAktif['semester']) ?>)
            <?php endif; ?>
        </p>
    </div>
    <a href="<?= site_url('presensi') ?>" class="btn btn-primary">
        <i class="bi bi-clipboard-check me-1"></i>Isi Presensi Hari Ini
    </a>
</div>

<!-- Ringkasan umum -->
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Total Siswa</div>
                <div class="fs-2 fw-semibold"><?= $totalSiswa ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Total Kelas</div>
                <div class="fs-2 fw-semibold"><?= $totalKelas ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Sudah Diabsen Hari Ini</div>
                <div class="fs-2 fw-semibold"><?= $sudahAbsen ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Belum Diabsen</div>
                <div class="fs-2 fw-semibold <?= $belumAbsen > 0 ? 'text-warning' : '' ?>"><?= $belumAbsen ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Rekap status hari ini -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body">
                <div class="text-success small">Hadir</div>
                <div class="fs-3 fw-semibold"><?= $rekap['Hadir'] ?></div>
                <div class="text-secondary small"><?= $persenHadir ?>% dari yang sudah diabsen</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body">
                <div class="text-info small">Sakit</div>
                <div class="fs-3 fw-semibold"><?= $rekap['Sakit'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-warning shadow-sm h-100">
            <div class="card-body">
                <div class="text-warning small">Izin</div>
                <div class="fs-3 fw-semibold"><?= $rekap['Izin'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-danger shadow-sm h-100">
            <div class="card-body">
                <div class="text-danger small">Alpa</div>
                <div class="fs-3 fw-semibold"><?= $rekap['Alpa'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan per kelas -->
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Presensi Hari Ini per Kelas</div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th class="text-center">Siswa</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Izin</th>
                    <th class="text-center">Alpa</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekapKelas)) : ?>
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-4">Belum ada data kelas.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($rekapKelas as $k) : ?>
                    <?php
                    $total = (int) $k['total_siswa'];
                    $sudah = (int) $k['sudah_absen'];
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= esc($k['nama_kelas']) ?></td>
                        <td><?= esc($k['wali_kelas'] ?? '-') ?></td>
                        <td class="text-center"><?= $total ?></td>
                        <td class="text-center"><?= (int) $k['hadir'] ?></td>
                        <td class="text-center"><?= (int) $k['sakit'] ?></td>
                        <td class="text-center"><?= (int) $k['izin'] ?></td>
                        <td class="text-center"><?= (int) $k['alpa'] ?></td>
                        <td>
                            <?php if ($total === 0) : ?>
                                <span class="badge text-bg-secondary">Tidak ada siswa</span>
                            <?php elseif ($sudah === 0) : ?>
                                <span class="badge text-bg-warning">Belum diabsen</span>
                            <?php elseif ($sudah < $total) : ?>
                                <span class="badge text-bg-info">Sebagian (<?= $sudah ?>/<?= $total ?>)</span>
                            <?php else : ?>
                                <span class="badge text-bg-success">Lengkap</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= site_url('presensi') . '?kelas=' . (int) $k['id_kelas'] . '&tanggal=' . $tanggal ?>"
                               class="btn btn-sm btn-outline-primary">
                                Isi Presensi
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>