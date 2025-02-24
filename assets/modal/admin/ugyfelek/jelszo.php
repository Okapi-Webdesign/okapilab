<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$c = new Client($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-key me-2"></i> Jelszó módosítása
    </h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/clients/password" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <label for="password" class="form-label">Új jelszó</label>
        <div class="input-group">
            <input type="text" id="password" name="password" class="form-control" required>
            <button type="button" class="btn btn-secondary" onclick="generatePassword()">
                <i class="fa fa-shuffle"></i>
            </button>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $c->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Bezárás
        </button>
        <button type="submit" class="btn btn-primary">
            Mentés
        </button>
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

    generatePassword();
</script>