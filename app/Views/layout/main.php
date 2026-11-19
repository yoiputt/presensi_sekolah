<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/header') ?>
</head>
<body>

    <?= $this->include('layout/sidebar') ?>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="app-main">

        <?= $this->include('layout/topbar') ?>

        <main class="flex-grow-1 p-3 p-lg-4">

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <?php $errors = session()->getFlashdata('errors'); ?>
            <?php if (! empty($errors)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        <?php foreach ((array) $errors as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </main>

        <?= $this->include('layout/footer') ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const sidebar  = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggle   = document.getElementById('sidebarToggle');

            function setOpen(open) {
                sidebar.classList.toggle('show', open);
                backdrop.classList.toggle('show', open);
            }

            toggle?.addEventListener('click', function () {
                setOpen(! sidebar.classList.contains('show'));
            });

            backdrop?.addEventListener('click', function () {
                setOpen(false);
            });
        })();
    </script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>