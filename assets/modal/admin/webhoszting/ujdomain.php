<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-globe me-2"></i> Új domain
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/addDomainPlan" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="g-3 row">
            <div class="col-12 col-md-6">
                <label for="tld" class="form-label">Végződés</label>
                <div class="input-group">
                    <span class="input-group-text">.</span>
                    <input type="text" id="tld" name="tld" class="form-control" required>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="price" class="form-label">Éves díj</label>
                <div class="input-group">
                    <input type="number" id="price" name="price" class="form-control" required min="0">
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