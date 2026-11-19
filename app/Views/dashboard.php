
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Presensi Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand">Presensi Siswa</a>

        <a href="<?= site_url('logout') ?>" class="btn btn-light">
            Logout
        </a>
    </div>
</nav>

<div class="container mt-5">

    <h1>Dashboard</h1>
    <p class="text-secondary">
        Selamat datang di Sistem Presensi Siswa.
    </p>

    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Data Siswa</h3>
                    <p>Kelola data siswa.</p>

                    <a href="<?= site_url('siswa') ?>"
                       class="btn btn-primary">
                        Lihat Data
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Presensi</h3>
                    <p>Kelola data presensi siswa.</p>

                    <a href="<?= site_url('presensi') ?>"
                       class="btn btn-success">
                        Presensi
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Laporan</h3>
                    <p>Lihat laporan presensi.</p>

                    <a href="<?= site_url('laporan') ?>"
                       class="btn btn-dark">
                        Laporan
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>