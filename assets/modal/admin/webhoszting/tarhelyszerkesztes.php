<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$subs = new WHSubscription($_POST['id'] ?? 0);
if (0 == $subs->getId()) {
    echo '<div class="alert alert-danger">A tárhely nem található!</div>';
    exit;
}
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-pencil me-2"></i> Tárhely szerkesztése
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/editSubscription" method="post" class="needs-validation" novalidate>
    <input type="hidden" name="id" value="<?= $subs->getId() ?>">
    <div class="modal-body">
        <label for="plan" class="form-label">Csomag</label>
        <select id="plan" name="plan" class="form-select" required>
            <?php
            $plans = WHPlan::getAll();
            foreach ($plans as $plan) {
                echo '<option data-price="' . ($subs->getBillingPeriod() == 'yearly' ? $plan->getYearlyPrice() : $plan->getMonthlyPrice()) . '" value="' . $plan->getId() . '" ' . ($subs->getPlan()->getId() == $plan->getId() ? 'selected' : '') . '>' . $plan->getName() . '</option>';
            }
            ?>
        </select>

        <label for="price" class="form-label mt-3">Ár (egy számlázási időszakban)</label>
        <div class="input-group">
            <input type="text" id="price" name="price" class="form-control" value="<?= $subs->getPrice() ?>">
            <span class="input-group-text">Ft</span>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>

<script>
    $('document').ready(function() {
        $('#plan').change(function() {
            var price = $(this).find(':selected').data('price');
            $('#price').val(price);
        });
    });
</script>