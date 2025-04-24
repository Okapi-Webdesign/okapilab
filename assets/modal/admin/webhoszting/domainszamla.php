<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$domain = new WHDomain($_POST['id'] ?? 0);
if (0 == $domain->getId()) {
    echo '<div class="alert alert-danger">A domain nem található!</div>';
    exit;
}
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-refresh fa-solid me-2"></i> Domain megújítása
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/renewDomain" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label for="amount" class="form-label">Összeg</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="amount" id="amount" value="<?= $domain->getTld()->getPrice() ?>" required min="0">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="invoice_file" class="form-label">Számla PDF</label>
                <input type="file" class="form-control" name="invoice_file" id="invoice_file" accept=".pdf" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="invoice_id" class="form-label">Sorszám</label>
                <input type="text" class="form-control" name="invoice_id" id="invoice_id" value="IOOJN-<?= date('Y') ?>-" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="invoice_date" class="form-label">Kiállítás dátuma</label>
                <input type="date" class="form-control datepicker" name="invoice_date" id="invoice_date" value="<?= date('Y-m-d') ?>" required max="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-12 col-md-6">
                <label for="invoice_due_date" class="form-label">Fizetési határidő</label>
                <?php
                $due = $domain->getExpiry();
                if ($due < time()) {
                    $due = strtotime('+8 days');
                }

                $due = date('Y-m-d', $due);
                ?>
                <input type="date" class="form-control datepicker" name="invoice_due_date" id="invoice_due_date" value="<?= $due ?>" required min="<?= date('Y-m-d') ?>">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $domain->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>