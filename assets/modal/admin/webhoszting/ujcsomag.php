<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-plug me-2"></i> Új csomag
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/addPlan" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="g-3 row">
            <div class="col-12">
                <label for="name" class="form-label">Név</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="size" class="form-label">Méret</label>
                <div class="input-group">
                    <input type="number" id="size" name="size" class="form-control" required min="0" step="100">
                    <span class="input-group-text">MB</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="cost" class="form-label">Költség</label>
                <div class="input-group">
                    <input type="number" id="cost" name="cost" class="form-control" required min="0">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="monthly_price" class="form-label">Havidíj</label>
                <div class="input-group">
                    <input type="number" id="monthly_price" name="monthly_price" class="form-control" required min="0">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="yearly_price" class="form-label">Éves díj</label>
                <div class="input-group">
                    <input type="number" id="yearly_price" name="yearly_price" class="form-control" required min="0">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="wordpress" class="form-label">WordPress</label>
                <select id="wordpress" name="wordpress" class="form-select" required>
                    <option value="0">Nem</option>
                    <option value="1">Igen</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="managed" class="form-label">Monitorozás</label>
                <select id="managed" name="managed" class="form-select" required>
                    <option value="0">Nem</option>
                    <option value="1">Igen</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>