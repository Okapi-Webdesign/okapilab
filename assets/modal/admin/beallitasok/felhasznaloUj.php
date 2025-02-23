<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-plus me-2"></i> Új felhasználó
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/settings/addUser" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">E-mail cím</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="password" class="form-label">Jelszó</label>
                <div class="input-group">
                    <input type="text" id="password" name="password" class="form-control" required>
                    <button type="button" class="btn btn-secondary" onclick="generatePassword()">
                        <i class="fa fa-shuffle"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="lastname" class="form-label">Vezetéknév</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="firstname" class="form-label">Keresztnév</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Hozzáadás</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>

<script>
    function generatePassword() {
        let length = 8,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            password = "";

        for (let i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }

        document.getElementById('password').value = password;
    }
</script>