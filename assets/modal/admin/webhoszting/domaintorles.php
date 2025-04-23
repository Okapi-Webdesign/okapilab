<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$domain = new WHDomainPlan($_POST['id'] ?? 0);
if (0 == $domain->getId()) {
    echo '<div class="alert alert-danger">A domain nem található!</div>';
    exit;
}
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-times me-2"></i> Domain törlése
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/deleteDomain" method="post" class="needs-validation" novalidate>
    <input type="hidden" name="id" value="<?= $domain->getId() ?>">
    <div class="modal-body">
        <p class="mb-0">
            Biztosan törölni szeretnéd a(z) <strong>.<?= $domain->getTld() ?></strong> végződést?
        </p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-danger">Törlés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>