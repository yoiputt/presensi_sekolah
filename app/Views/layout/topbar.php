<header class="app-topbar px-3 px-lg-4 py-2 d-flex align-items-center justify-content-between">

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-secondary d-lg-none" id="sidebarToggle" aria-label="Buka menu">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="mb-0"><?= esc($title ?? 'Dashboard') ?></h5>
    </div>

    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2"
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-5"></i>
            <span class="d-none d-sm-inline"><?= esc(session()->get('nama_guru')) ?></span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <span class="dropdown-item-text text-muted small">
                    Login sebagai <strong><?= esc(session()->get('username')) ?></strong>
                </span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="<?= site_url('logout') ?>" method="post" class="px-3">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

</header>