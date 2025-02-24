<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-key me-2"></i> Belépési adatok hozzáadása
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>ugyfel/process/project/loginAdd" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Megnevezés</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="url" class="form-label">Belépési URL</label>
                <input type="url" id="url" name="url" class="form-control" required <?= isset($_POST['wp']) && $project->getUrl() !== NULL ? 'value="' . $project->getUrl() . 'wp-admin"' : '' ?>>
            </div>
            <div class="col-12 col-md-6">
                <label for="username" class="form-label">Felhasználónév</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="password" class="form-label">Jelszó</label>
                <input type="text" id="password" name="password" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Hozzáadás</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>