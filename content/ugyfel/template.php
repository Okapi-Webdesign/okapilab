<?php
$pageMeta = extractPageMeta(ABS_PATH . 'content/ugyfel/display/' . $url . '.php');
$title = $pageMeta['title'] ?? APP_NAME;

if (!isset($pageMeta['packages']) || $pageMeta['packages'] == null || !is_array($pageMeta['packages'])) {
    $pageMeta['packages'] = [];
}

if ($_SESSION['project'] == null && $url != 'profil/projektvalaszto' && strpos($GET['url'], 'process/profile/projectChange') === false) {
    redirect(URL . 'ugyfel/profil/projektvalaszto');
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> • <?= APP_NAME ?></title>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' integrity='sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="<?= URL ?>assets/css/client/style.css">
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

<body class="bg-body-tertiary">
    <script src="<?= URL ?>assets/js/client/start.js"></script>
    <div id="loader">
        <span id="loader_spinner"></span>
    </div>
    <?php
    alert_show();
    ?>
    <nav class="navbar navbar-expand-lg bg-body-secondary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= URL ?>" style="margin-bottom:2px;">
                OkapiLab
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'], 'projektem') == true ? 'active' : '' ?>" aria-current="page" href="<?= URL ?>">Projektem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'], 'dokumentumok') == true ? 'active' : '' ?>" href="<?= URL ?>ugyfel/dokumentumok">Dokumentumok</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'], 'penzugyek') == true ? 'active' : '' ?>" href="<?= URL ?>ugyfel/penzugyek">Pénzügyek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'], 'szolgaltatasok') == true ? 'active' : '' ?>" href="<?= URL ?>ugyfel/szolgaltatasok">Szolgáltatásaim</a>
                    </li>
                </ul>
                <div class="float-end dropdown">
                    <a class="dropdown-toggle text-dark text-decoration-none" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $user->getFullname() ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="right: 0; left: auto;">
                        <li><a class="dropdown-item" href="<?= URL ?>ugyfel/profil/projektvalaszto">Projektválasztó</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>ugyfel/profil">Profil</a></li>
                        <li><a class="dropdown-item text-danger" href="<?= URL ?>ugyfel/belepes/kilepes">Kijelentkezés</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="main-content bg-body-tertiary p-4">
        <h1 class="h2"><?= $title ?></h1>
        <p>
            <?php
            $breadcrumbs = explode('/', $url);
            $breadcrumbPath = '';
            ?>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL ?>ugyfel">Ügyfélterület</a></li>
                <?php
                if (!isset($pageMeta['breadcrumbs']) || $pageMeta['breadcrumbs'] == null) {
                ?>
                    <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                        <?php $breadcrumbPath .= $breadcrumb . '/'; ?>
                        <li class="breadcrumb-item <?= end($breadcrumbs) === $breadcrumb ? 'active' : '' ?>">
                            <?php if (end($breadcrumbs) === $breadcrumb) : ?>
                                <?= $title ?>
                            <?php else : ?>
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
                            <?php if (end($breadcrumbs_urls) === $breadcrumbs_urls[$i]) : ?>
                                <?= $breadcrumb ?>
                            <?php else : ?>
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
        if ((isset($pageMeta['role']) && !$user->role($pageMeta['role'])) || !$user->loggedin()) {
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

        html_load('ugyfel', $url, $data);
        ?>
    </div>

    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" id="modalContent"></div>
        </div>
    </div>

    <script src="<?= URL ?>assets/js/client/end.js"></script>
    <script src="<?= URL ?>assets/js/validate.js"></script>
    <?php if (in_array('select2', $pageMeta['packages'])) { ?>
        <script src="<?= URL ?>assets/js/select2.js"></script>
    <?php } ?>
</body>

</html>