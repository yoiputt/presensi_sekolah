<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h4 class="mb-0">Data Siswa</h4>
    <a href="<?= site_url('siswa/tambah') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Siswa
    </a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="get" action="<?= site_url('siswa') ?>" class="row g-2">
            <div class="col-md-4">
                <select name="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua kelas</option>
                    <?php foreach ($kelas as $k) : ?>
                        <option value="<?= (int) $k['id_kelas'] ?>"
                            <?= (int) $k['id_kelas'] === $idKelas ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="q" class="form-control"
                       placeholder="Cari nama atau NIS..." value="<?= esc($keyword) ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-grow-1">Cari</button>
                <a href="<?= site_url('siswa') ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>L/P</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($siswa)) : ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Data siswa tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($siswa as $i => $s) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($s['nis']) ?></td>
                        <td><?= esc($s['nama_siswa']) ?></td>
                        <td><?= esc($s['nama_kelas']) ?></td>
                        <td><?= esc($s['jenis_kelamin']) ?></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url('siswa/edit/' . (int) $s['id_siswa']) ?>"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>

                            <form action="<?= site_url('siswa/hapus/' . (int) $s['id_siswa']) ?>"
                                  method="post" class="d-inline"
                                  onsubmit="return confirm('Hapus siswa <?= esc($s['nama_siswa'], 'js') ?>? Seluruh data presensinya juga ikut terhapus.')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white text-secondary small">
        Menampilkan <?= count($siswa) ?> siswa
    </div>
</div>

<?= $this->endSection() ?>