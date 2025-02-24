<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$c = new Client($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-trash me-2"></i> Ügyfél törlése
    </h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<div class="modal-body">
    <p>
        Biztosan törölni szeretnéd a következő ügyfelet? <br>
        <b><?= $c->getName() ?></b>
    </p>
    <p class="text-danger mb-0">
        <b>Figyelem!</b> A művelet nem vonható vissza!
    </p>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
    <a href="<?= URL ?>admin/process/clients/delete/d/<?= $c->getId() ?>" class="btn btn-danger">Törlés</a>
</div>