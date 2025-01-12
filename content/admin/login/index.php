<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'admin') {
    redirect(URL . 'admin/iranyitopult');
    exit;
}
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    redirect(URL . 'admin/belepes/auth');
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés • <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= URL ?>assets/css/admin/login.css">
    <link rel="shortcut icon" href="<?= URL ?>assets/img/favicon.ico" type="image/x-icon">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <?php
    if (isset($_GET['err'])) {
    ?>
        <div class="position-absolute bottom-0 end-0 mb-2 me-2 alert alert-info">
            <?= $_GET['err'] ?>
        </div>
    <?php } ?>
    <main class="form-signin w-100 m-auto">
        <form method="post" action="<?= URL ?>admin/belepes/auth">
            <img class="mb-4" src="../../assets/img/logo.png" alt="" width="72" height="72">
            <h1 class="h3 mb-0 fw-normal">Jelentkezzen be</h1>
            <p>a további információk eléréséhez!</p>

            <div class="form-floating">
                <input name="username" type="text" placeholder=" " class="form-control" id="floatingInput" required>
                <label for="floatingInput">Felhasználónév</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" placeholder=" " class="form-control" id="floatingPassword" required>
                <label for="floatingPassword">Jelszó</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Belépés</button>
            <p class="mt-5 mb-3 text-body-secondary">&copy; <?= APP_NAME ?> 2024-<?= date('Y') ?></p>
        </form>
    </main>
    <script src="../assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>