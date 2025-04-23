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
        <i class="fa-solid fa-pencil me-2"></i> Domain szerkesztése
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/editDomainPlan" method="post" class="needs-validation" novalidate>
    <input type="hidden" name="id" value="<?= $domain->getId() ?>">
    <div class="modal-body">
        <div class="g-3 row">
            <div class="col-12 col-md-6">
                <label for="tld" class="form-label">Végződés</label>
                <div class="input-group">
                    <span class="input-group-text">.</span>
                    <input type="text" id="tld" name="tld" class="form-control" required value="<?= $domain->getTld() ?>">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="price" class="form-label">Éves díj</label>
                <div class="input-group">
                    <input type="number" id="price" name="price" class="form-control" required min="0" value="<?= $domain->getPrice(false) ?>">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>