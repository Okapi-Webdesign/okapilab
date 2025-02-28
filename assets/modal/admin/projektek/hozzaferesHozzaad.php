<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-key me-2"></i> Belépési adatok hozzáadása
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/projects/loginAdd" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Megnevezés</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="url" class="form-label">Belépési URL</label>
                <input type="url" id="url" name="url" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="username" class="form-label">Felhasználónév</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="password" class="form-label">Jelszó</label>
                <input type="text" id="password" name="password" class="form-control" required>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="private" name="private">
                    <label class="form-check-label" for="private">
                        Privát adatpár - csak én láthatom
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="project" value="<?= $project->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Hozzáadás</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>