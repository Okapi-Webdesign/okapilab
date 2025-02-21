<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$invoice = new Invoice($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-money-bill me-2"></i> Befizetés rögzítése
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form method="post" action="<?= URL ?>admin/process/finances/invoicePay" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="mb-3 d-flex justify-content-between">
            <span class="d-block">
                <b>Fizetendő:</b> <?= number_format($invoice->getRemaining(), 0, 0, ' ') ?> Ft
            </span>

            <span class="d-block">
                <b>Számla:</b> <?= $invoice->getInvoiceId() ?>
            </span>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="amount" class="form-label">Befizetés összege</label>
                <div class="input-group">
                    <input type="number" id="amount" name="amount" class="form-control" required min="0" value="<?= $invoice->getRemaining() ?>">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="pay_date" class="form-label">Befizetés dátuma</label>
                <input type="date" id="pay_date" name="pay_date" class="form-control" required max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="invoice_id" value="<?= $invoice->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>