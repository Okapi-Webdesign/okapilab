<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-plus me-2"></i> Új kiadás
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/finances/expenseAdd" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label for="type" class="form-label">Típus</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="" disabled selected>Válasszon...</option>
                    <option value="expense">Kiadás</option>
                    <option value="payout">Kifizetés</option>
                </select>
            </div>
            <div class="col-12 expenseData">
                <label for="reason" class="form-label">Kiadás oka</label>
                <input type="text" name="reason" id="reason" class="form-control">
            </div>
            <div class="col-12 col-md-6 payoutData">
                <label for="project" class="form-label">Projekt</label>
                <select name="project" id="project" class="select2">
                    <option value="" disabled selected>Válasszon...</option>
                    <?php foreach (Project::getAll(true) as $project) : ?>
                        <option value="<?= $project->getId() ?>"><?= $project->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-6 payoutData">
                <label for="user" class="form-label">Felhasználó</label>
                <select name="user" id="user" class="select2">
                    <?php foreach (User::getAll() as $_user) : ?>
                        <option value="<?= $_user->getId() ?>"><?= $_user->getFullname() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="date" class="form-label">Dátum</label>
                <input type="date" name="date" id="date" class="form-control" required max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-12 col-md-6">
                <label for="amount" class="form-label">Összeg</label>
                <div class="input-group">
                    <input type="number" name="amount" id="amount" class="form-control" required min="0" step="0.01">
                    <span class="input-group-text">Ft</span>
                </div>
            </div>
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
        $('#type').change(function() {
            if ($(this).val() == 'expense') {
                $('.expenseData').show();
                $('.expenseData input').attr('required', true);
                $('.payoutData input').attr('required', false);
                $('.payoutData').hide();
            } else if ($(this).val() == 'payout') {
                $('.expenseData').hide();
                $('.expenseData input').attr('required', false);
                $('.payoutData input').attr('required', true);
                $('.payoutData').show();
            } else {
                $('.expenseData').hide();
                $('.expenseData input').attr('required', false);
                $('.payoutData input').attr('required', false);
                $('.payoutData').hide();
            }
        });

        $('#type').trigger('change');
    });
</script>