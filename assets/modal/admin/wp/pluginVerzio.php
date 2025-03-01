<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-upload me-2"></i> OkapiLab plugin verzió
    </h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/wp/updateOLPlugin" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row row-cols-1 row-cols-md-2 g-3">
            <div class="col">
                <label for="file" class="form-label">Fájl kiválasztása</label>
                <input type="file" id="file" name="file" class="form-control" required accept=".zip">
            </div>
            <div class="col">
                <label for="version" class="form-label">Verzió</label>
                <input type="text" id="version" name="version" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Bezárás
        </button>
        <button type="submit" class="btn btn-primary">
            Feltöltés
        </button>
    </div>
</form>