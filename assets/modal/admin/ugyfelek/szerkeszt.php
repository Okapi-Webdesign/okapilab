<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$c = new Client($_POST['id']);
$client_is_organization = $c->getType() == 2;
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-pencil me-2"></i> Ügyfél szerkesztése
    </h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>
<form action="<?= URL ?>admin/process/clients/edit" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="type" class="form-label">Típus</label>
                <input type="text" class="form-control" disabled value="<?= $c->getType() == 1 ? 'M - Magánszemély' : 'C - Jogi személy' ?>" id="type">
            </div>
            <div class="col-12 col-md-8">
                <label for="name" class="form-label">Név</label>
                <?php
                if ($client_is_organization) {
                    echo '<input type="text" class="form-control" id="name" name="name" value="' . $c->getName() . '" required>';
                } else {
                    echo '<div class="input-group">
                        <input type="text" class="form-control" id="name" name="lastname" value="' . $c->getContactName('l') . '" required>
                        <input type="text" class="form-control" name="firstname" value="' . $c->getContactName('f') . '" required>
                    </div>';
                }
                ?>
            </div>
            <div class="col-12 col-md-4">
                <label for="zip" class="form-label">Irányítószám</label>
                <input type="text" class="form-control" id="zip" name="zip" value="<?= $c->getAddress('zip') ?>" required pattern="[0-9]{4}" maxlength="4">
            </div>
            <div class="col-12 col-md-8">
                <label for="city" class="form-label">Település</label>
                <input type="text" class="form-control" id="city" name="city" value="<?= $c->getAddress('city') ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="address" class="form-label">Cím 1. sora</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= $c->getAddress('address') ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="address2" class="form-label">Cím 2. sora</label>
                <input type="text" class="form-control" id="address2" name="address2" value="<?= $c->getAddress('address2') ?>">
            </div>
            <div class="col-12">
                <label for="registration_number" class="form-label"><?= $client_is_organization ? 'Cégjegyzékszám' : 'Személyiigazolvány-szám' ?></label>
                <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= $c->getRegistrationNumber() ?>">
            </div>
            <?php
            if ($client_is_organization) {
                echo '<div class="col-12 col-md-6">
                        <label for="tax_number" class="form-label">Adószám</label>
                        <input type="text" class="form-control" id="tax_number" name="tax_number" value="' . $c->getTaxNumber() . '">
                    </div>';

                echo '<div class="col-12 col-md-6">
                        <label for="company_form" class="form-label">Cégforma</label>
                        <input type="text" class="form-control text-uppercase" id="company_form" name="company_form" value="' . $c->getCompanyType() . '">
                    </div>';

                echo '<div class="col-12">
                        <label for="contact_name" class="form-label">Kapcsolattartó neve</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="contact_name" name="lastname" value="' . $c->getContactName('l') . '" required>
                            <input type="text" class="form-control" name="firstname" value="' . $c->getContactName('f') . '" required>
                        </div>
                    </div>';
            }
            ?>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">E-mail cím</label>
                <input type="email" class="form-control" id="email" value="<?= $c->getEmail() ?>" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label for="phone" class="form-label">Telefonszám</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= $c->getPhone() ?>" required maxlength="12">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $c->getId() ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </div>
</form>

<script src="<?= URL ?>assets/js/validate.js"></script>

<script>
    $('document').ready(function() {
        $('#zip').keyup(function() {
            if ($(this).val().length == 4) {
                $.ajax({
                    url: 'https://hur.webmania.cc/zips/' + $(this).val() + '.json',
                    type: 'GET',
                    success: function(data) {
                        $('#city').val(data.zips[0].name);
                    }
                });
            } else if ($(this).val().length < 4) {
                $('#city').val('');
            }
        });
    });
</script>