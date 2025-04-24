<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-plug me-2"></i> Új tárhely
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/webhosting/addSubscription" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="client" class="form-label">Ügyfél</label>
                <select id="client" name="client" class="select2" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <?php
                    $clients = Client::getAll();
                    foreach ($clients as $client) {
                        echo '<option value="' . $client->getId() . '">' . $client->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="plan" class="form-label">Csomag</label>
                <select id="plan" name="plan" class="select2" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <?php
                    $plans = WHPlan::getAll();
                    foreach ($plans as $plan) {
                        echo '<option value="' . $plan->getId() . '" data-price-monthly="' . $plan->getMonthlyPrice(true) . '" data-price-yearly="' . $plan->getYearlyPrice(true) . '">' . $plan->getName() . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="period" class="form-label">Fizetési időszak</label>
                <select id="period" name="period" class="form-select" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <option value="monthly">Havi</option>
                    <option value="yearly">Éves</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="price" class="form-label">Fizetendő</label>
                <input type="text" id="price" name="price" class="form-control" readonly>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>

<script>
    $('document').ready(function() {
        $('#period, #plan').on('change', function() {
            var plan = $('#plan option:selected');
            var period = $('#period').val();
            var price = 0;

            if (period === 'monthly') {
                price = plan.data('price-monthly');
            } else if (period === 'yearly') {
                price = plan.data('price-yearly');
            }

            $('#price').val(price);
        });
    });
</script>