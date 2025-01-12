<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'client') {
    redirect(URL . 'ugyfel/faliujsag');
    exit;
}
if (isset($_COOKIE['email']) && isset($_COOKIE['password']) && $_COOKIE['platform'] === 'client') {
    redirect(URL . 'ugyfel/belepes/auth');
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés • <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= URL ?>assets/css/client/login.css">
    <link rel="shortcut icon" href="<?= URL ?>assets/img/favicon.ico" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="d-flex align-items-center justify-content-center bg-light">
    <?php
    if (isset($_GET['err'])) {
    ?>
        <div class="position-absolute bottom-0 end-0 mb-2 me-2 alert alert-info">
            <?= $_GET['err'] ?>
        </div>
    <?php } ?>
    <div class="card shadow-sm p-4">
        <div class="text-center mb-3">
            <img src="<?= URL ?>assets/img/logo.png" alt="EduLink logó" class="mb-4" style="max-width: 100px;">
            <h1 class="h4 mb-0">Jelentkezz be</h1>
            <p class="text-muted">a további információk eléréséhez!</p>
        </div>
        <form class="needs-validation" novalidate action="<?= URL ?>ugyfel/belepes/auth" method="post">
            <div class="form-group">
                <label for="email">Felhasználónév</label>
                <input type="text" class="form-control" id="email" placeholder="Add meg a felhasználóneved" required name="email">
            </div>
            <div class="form-group">
                <label for="password">Jelszó</label>
                <input type="password" class="form-control" id="password" placeholder="Add meg a jelszavad" required name="password">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Emlékezz rám</label>
                </div>
                <a href="<?= URL ?>ugyfel/belepes/forgot-password" class="btn btn-link">Elfelejtett jelszó?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Bejelentkezés</button>
        </form>
        <p class="mt-3 mb-0 text-muted text-center"><span>&copy;</span> EduLink 2024-<?= date('Y') ?></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="<?= URL ?>assets/js/validate.js"></script>
</body>

</html>