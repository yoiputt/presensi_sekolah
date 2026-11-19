<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$query = http_build_query(['kelas' => $idKelas, 'dari' => $dari, 'sampai' => $sampai]);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h4 class="mb-0">Laporan Presensi</h4>

    <div class="d-flex gap-2 d-print-none">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
        <a href="<?= site_url('laporan/export') . '?' . $query ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Unduh CSV
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-3 d-print-none">
    <div class="card-body">
        <form method="get" action="<?= site_url('laporan') ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="kelas" class="form-label small text-secondary mb-1">Kelas</label>
                <select id="kelas" name="kelas" class="form-select">
                    <?php foreach ($kelasList as $k) : ?>
                        <option value="<?= (int) $k['id_kelas'] ?>"
                            <?= (int) $k['id_kelas'] === $idKelas ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="dari" class="form-label small text-secondary mb-1">Dari tanggal</label>
                <input type="date" id="dari" name="dari" class="form-control" value="<?= esc($dari) ?>">
            </div>
            <div class="col-md-3">
                <label for="sampai" class="form-label small text-secondary mb-1">Sampai tanggal</label>
                <input type="date" id="sampai" name="sampai" class="form-control" value="<?= esc($sampai) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card border-success shadow-sm">
            <div class="card-body py-2">
                <div class="text-success small">Hadir</div>
                <div class="fs-4 fw-semibold"><?= $jumlah['hadir'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-info shadow-sm">
            <div class="card-body py-2">
                <div class="text-info small">Sakit</div>
                <div class="fs-4 fw-semibold"><?= $jumlah['sakit'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-warning shadow-sm">
            <div class="card-body py-2">
                <div class="text-warning small">Izin</div>
                <div class="fs-4 fw-semibold"><?= $jumlah['izin'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-danger shadow-sm">
            <div class="card-body py-2">
                <div class="text-danger small">Alpa</div>
                <div class="fs-4 fw-semibold"><?= $jumlah['alpa'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel rekap -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <span class="fw-semibold"><?= esc($namaKelas) ?></span>
        <span class="text-secondary ms-2">
            <?= esc(tanggal_indo($dari)) ?> s/d <?= esc(tanggal_indo($sampai)) ?>
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>L/P</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Izin</th>
                    <th class="text-center">Alpa</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekap)) : ?>
                    <tr>
                        <td colspan="10" class="text-center text-secondary py-4">Tidak ada data siswa.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($rekap as $i => $r) : ?>
                    <?php
                    $total  = (int) $r['total'];
                    $persen = $total > 0 ? (int) round($r['hadir'] / $total * 100) : null;
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($r['nis']) ?></td>
                        <td><?= esc($r['nama_siswa']) ?></td>
                        <td><?= esc($r['jenis_kelamin']) ?></td>
                        <td class="text-center"><?= (int) $r['hadir'] ?></td>
                        <td class="text-center"><?= (int) $r['sakit'] ?></td>
                        <td class="text-center"><?= (int) $r['izin'] ?></td>
                        <td class="text-center"><?= (int) $r['alpa'] ?></td>
                        <td class="text-center"><?= $total ?></td>
                        <td class="text-center">
                            <?php if ($persen === null) : ?>
                                <span class="text-secondary">-</span>
                            <?php else : ?>
                                <span class="badge text-bg-<?= $persen >= 90 ? 'success' : ($persen >= 75 ? 'warning' : 'danger') ?>">
                                    <?= $persen ?>%
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white text-secondary small">
        Persentase hadir dihitung dari hari yang sudah tercatat presensinya.
    </div>
</div>

<?= $this->endSection() ?>