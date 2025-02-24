<?php
$pageMeta = extractPageMeta(ABS_PATH . 'content/admin/display/' . $url . '.php');
$title = $pageMeta['title'] ?? APP_NAME;

if (!isset($pageMeta['packages']) || $pageMeta['packages'] == null || !is_array($pageMeta['packages'])) {
    $pageMeta['packages'] = [];
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> • <?= APP_NAME ?></title>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' integrity='sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="<?= URL ?>assets/css/admin/style.css">
    <script src="<?= URL ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="<?= URL ?>assets/img/favicon.ico" type="image/x-icon">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/51401f08b3.js" crossorigin="anonymous"></script>

    <?php
    if (in_array('datatables', $pageMeta['packages'])) {
    ?>
        <!-- DataTables -->
        <link href="https://cdn.datatables.net/v/bs5/dt-2.1.8/b-3.2.0/r-3.0.3/datatables.min.css" rel="stylesheet">

        <script src="https://cdn.datatables.net/v/bs5/dt-2.1.8/b-3.2.0/r-3.0.3/datatables.min.js"></script>
    <?php
    }

    if (in_array('select2', $pageMeta['packages'])) {
    ?>
        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/i18n/hu.js"></script>
    <?php }
    if (in_array('quill', $pageMeta['packages'])) {
    ?>
        <!-- Rich Text Editor -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <?php } ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script src="<?= URL ?>assets/js/admin/start.js"></script>
    <div id="loader">
        <span id="loader_spinner"></span>
    </div>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark sidebar" id="sidebar">
        <a href="<?= URL ?>admin" class="align-items-center me-md-auto text-white text-decoration-none d-md-flex d-none">
            <img src="<?= URL ?>assets/img/logo.png" alt="Logo" width="32" class="me-3">
            <span class="fs-4"><?= APP_NAME ?></span>
        </a>
        <div class="d-flex align-items-center me-md-auto text-white text-decoration-none d-md-none">
            <button class="sidebarToggler">
                <i class="fa fa-times"></i>
            </button>
            <span class="fs-4"><?= APP_NAME ?></span>
        </div>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <?php
            addMenuItem('Projektek', 'projektek', 'fa fa-project-diagram');
            addMenuItem('Ügyfelek', 'ugyfelek', 'fa fa-users');
            addMenuItem('Dokumentumok', 'dokumentumok', 'fa fa-file-alt');
            addMenuItem('Pénzügyek', 'penzugyek', 'fa fa-money-bill-wave');
            addMenuItem('Trello', 'trello', 'fa-brands fa-trello');
            addMenuItem('Beállítások', 'beallitasok', 'fa fa-cogs');
            ?>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" id="userdropdown" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= $user->getProfilePicture() ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong class="sidebarProfileUsername"><?= $user->getFullname() ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="<?= URL ?>admin/profil">Profil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="<?= URL ?>admin/belepes/kilepes">Kilépés</a></li>
            </ul>
        </div>
    </div>
    <div class="bg-body-tertiary mb-0 pt-3 d-md-none">
        <button class="sidebarToggler">
            <i class="fa fa-bars me-2"></i> Menü
        </button>
    </div>
    <?php
    alert_show();
    ?>
    <div class="main-content bg-body-tertiary">
        <h1 class="h2"><?= $title ?></h1>
        <p>
            <?php
            $breadcrumbs = explode('/', $url);
            $breadcrumbPath = '';
            ?>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL ?>admin">Adminisztráció</a></li>
                <?php
                if (!isset($pageMeta['breadcrumbs']) || $pageMeta['breadcrumbs'] == null) {
                ?>
                    <?php foreach ($breadcrumbs as $breadcrumb): ?>
                        <?php $breadcrumbPath .= $breadcrumb . '/'; ?>
                        <li class="breadcrumb-item <?= end($breadcrumbs) === $breadcrumb ? 'active' : '' ?>">
                            <?php if (end($breadcrumbs) === $breadcrumb): ?>
                                <?= $title ?>
                            <?php else: ?>
                                <a href="<?= URL ?>admin/<?= rtrim($breadcrumbPath, '/') ?>"><?= ucfirst($breadcrumb) ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    <?php } else {
                    $breadcrumbs_urls = $pageMeta['breadcrumbs'][0];
                    $breadcrumbs_display = $pageMeta['breadcrumbs'][1];

                    for ($i = 0; $i < count($breadcrumbs_urls); $i++) {
                        $breadcrumbPath .= $breadcrumbs_urls[$i] . '/';
                        $breadcrumb = $breadcrumbs_display[$i];
                    ?>
                        <li class="breadcrumb-item <?= end($breadcrumbs_urls) === $breadcrumbs_urls[$i] ? 'active' : '' ?>">
                            <?php if (end($breadcrumbs_urls) === $breadcrumbs_urls[$i]): ?>
                                <?= $breadcrumb ?>
                            <?php else: ?>
                                <a href="<?= URL ?>admin/<?= rtrim($breadcrumbPath, '/') ?>"><?= $breadcrumb ?></a>
                            <?php endif; ?>
                        </li>
                <?php
                    }
                }
                ?>
            </ol>
        </nav>
        </p>

        <?php
        if ((isset($pageMeta['role']) && !$user->role($pageMeta['role'])) || !$user->role(2) || !$user->loggedin()) {
            echo '<div class="alert alert-danger">Nincs jogosultsága megtekinteni ezt az oldalt!</div>';
            return;
        }

        if (isset($pageMeta['description'])) {
        ?>

            <p class="text-muted">
                <?= $pageMeta['description'] ?>
            </p>

        <?php
        }

        html_load('admin', $url, $data);
        ?>
    </div>

    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="modalContent"></div>
        </div>
    </div>

    <script src="<?= URL ?>assets/js/admin/end.js"></script>
    <script src="<?= URL ?>assets/js/validate.js"></script>
    <?php if (in_array('select2', $pageMeta['packages'])) { ?>
        <script src="<?= URL ?>assets/js/select2.js"></script>
    <?php } ?>
</body>

</html>