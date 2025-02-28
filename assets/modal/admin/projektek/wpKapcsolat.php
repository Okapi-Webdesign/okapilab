<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-link me-2"></i> WordPress kapcsolat beállítása
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>
<form action="<?= URL ?>admin/process/projects/wp/connect" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="url">URL-cím</label>
                <input type="text" class="form-control" id="url" name="url" value="<?= $project->getUrl() ?>" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label for="wp_hash">API kulcs</label>
                <input type="text" class="form-control" id="wp_hash" name="wp_hash" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $project->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>