<?php
$menu = [
    ['url' => 'dashboard', 'label' => 'Dashboard',   'icon' => 'bi-speedometer2'],
    ['url' => 'presensi',  'label' => 'Presensi',    'icon' => 'bi-clipboard-check'],
    ['url' => 'laporan',   'label' => 'Laporan',     'icon' => 'bi-file-earmark-bar-graph'],
];

$master = [
    ['url' => 'siswa', 'label' => 'Data Siswa', 'icon' => 'bi-people'],
    ['url' => 'kelas', 'label' => 'Data Kelas', 'icon' => 'bi-building'],
];
?>
<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <i class="bi bi-journal-check me-2"></i>Presensi Siswa
    </div>

    <ul class="sidebar-menu">

        <li class="sidebar-label">Menu Utama</li>
        <?php foreach ($menu as $item) : ?>
            <li>
                <a href="<?= site_url($item['url']) ?>"
                   class="nav-link <?= url_is($item['url'] . '*') ? 'active' : '' ?>">
                    <i class="bi <?= $item['icon'] ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>

        <li class="sidebar-label">Data Master</li>
        <?php foreach ($master as $item) : ?>
            <li>
                <a href="<?= site_url($item['url']) ?>"
                   class="nav-link <?= url_is($item['url'] . '*') ? 'active' : '' ?>">
                    <i class="bi <?= $item['icon'] ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>

</aside>