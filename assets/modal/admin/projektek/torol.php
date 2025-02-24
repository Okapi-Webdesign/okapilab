<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-trash me-2"></i> Projekt törlése
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<div class="modal-body">
    <p>Biztosan törölni szeretnéd a következő projektet? <b class="text-danger">A művelet nem vonható vissza.</b></p>
    <p class="mb-0 fw-bold"><?= $project->getName() ?></p>
</div>

<form action="<?= URL ?>admin/process/projects/delete" method="post">
    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $project->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-danger">Törlés</button>
    </div>
</form>