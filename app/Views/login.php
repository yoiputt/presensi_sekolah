<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Presensi Siswa</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body p-4">

                        <h3 class="text-center mb-2">
                            Login
                        </h3>

                        <p class="text-center text-muted mb-4">
                            Sistem Presensi Siswa
                        </p>

                        <!-- Pesan error -->
                        <?php if (session()->getFlashdata('error')) : ?>

                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>

                        <?php endif; ?>

                        <!-- Form Login -->
                        <form
                            action="<?= base_url('/proses-login') ?>"
                            method="post"
                        >

                            <!-- Username -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    placeholder="Masukkan username"
                                    required
                                >

                            </div>

                            <!-- Password -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Masukkan password"
                                    required
                                >

                            </div>

                            <!-- Tombol Login -->
                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Login
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>