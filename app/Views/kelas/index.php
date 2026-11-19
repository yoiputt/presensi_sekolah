<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h4 class="mb-0">Data Kelas</h4>
    <a href="<?= site_url('kelas/tambah') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kelas
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th class="text-center">Jumlah Siswa</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelas)) : ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Belum ada data kelas.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($kelas as $i => $k) : ?>
                    <?php $jumlah = (int) $k['jumlah_siswa']; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= esc($k['nama_kelas']) ?></td>
                        <td><?= esc($k['wali_kelas'] ?? '-') ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('siswa') . '?kelas=' . (int) $k['id_kelas'] ?>">
                                <?= $jumlah ?>
                            </a>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url('kelas/edit/' . (int) $k['id_kelas']) ?>"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>

                            <?php if ($jumlah > 0) : ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                        title="Kelas masih memiliki siswa">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            <?php else : ?>
                                <form action="<?= site_url('kelas/hapus/' . (int) $k['id_kelas']) ?>"
                                      method="post" class="d-inline"
                                      onsubmit="return confirm('Hapus kelas <?= esc($k['nama_kelas'], 'js') ?>?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>