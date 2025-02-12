<?php
$pageMeta = extractPageMeta(ABS_PATH . 'content/ugyfeldisplay/' . $url . '.php');
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
    <?php } ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>