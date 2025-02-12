<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-plus me-2"></i> Új ügyfél
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<form action="<?= URL ?>admin/process/clients/add" method="post" class="needs-validation" novalidate>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <label for="type" class="form-label">Típus</label>
                <select id="type" name="type" class="form-select" required>
                    <option value="" selected disabled>Válasszon...</option>
                    <option value="1">Természetes személy</option>
                    <option value="2">Jogi személy</option>
                </select>
            </div>
            <div class="col-12 col-md-8 mb-3 secondary-data">
                <label for="lastname" class="form-label">Név</label>
                <div class="input-group" id="name_1">
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Vezetéknév" required>
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Keresztnév" required>
                </div>
                <div id="name_2">
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-3 secondary-data">
                <label for="zip" class="form-label">Irányítószám</label>
                <input type="text" id="zip" name="zip" class="form-control" required pattern="[0-9]{4}" maxlength="4">
            </div>
            <div class="col-12 col-md-8 mb-3 secondary-data">
                <label for="city" class="form-label">Település</label>
                <input type="text" id="city" name="city" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 mb-3 secondary-data">
                <label for="address" class="form-label">Cím 1. sora</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 mb-3 secondary-data">
                <label for="address2" class="form-label">Cím 2. sora</label>
                <input type="text" id="address2" name="address2" class="form-control">
            </div>
            <div class="col-12 mb-3 secondary-data" id="registration_number_col">
                <label for="registration_number" class="form-label">Személyiigazolvány-szám</label>
                <input type="text" id="registration_number" name="registration_number" class="form-control">
            </div>
            <div class="col-12 col-md-6 mb-3 secondary-data" id="tax_number_col">
                <label for="tax_number" class="form-label">Adószám</label>
                <input type="text" id="tax_number" name="tax_number" class="form-control">
            </div>
            <div class="col-12 col-md-6 mb-3 secondary-data" id="company_form_col">
                <label for="company_form" class="form-label">Cégforma</label>
                <input type="text" id="company_form" name="company_form" class="form-control text-uppercase">
            </div>
            <div class="col-12 mb-3 secondary-data" id="contact_name_col">
                <label for="contact_name" class="form-label">Kapcsolattartó neve</label>
                <div class="input-group">
                    <input type="text" id="contact_lastname" name="contact_lastname" class="form-control" placeholder="Vezetéknév">
                    <input type="text" id="contact_firstname" name="contact_firstname" class="form-control" placeholder="Keresztnév">
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3 secondary-data">
                <label for="email" class="form-label">E-mail cím</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 mb-3 secondary-data">
                <label for="phone" class="form-label">Telefonszám</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
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

        $('#type').change(function() {
            $('.secondary-data').show();
            if ($(this).val() == 1) {
                $('#registration_number_col .form-label').text('Személyiigazolvány-szám');
                $('#tax_number_col').hide();
                $('#company_form_col').hide();
                $('#contact_name_col').hide();
                $('#name_1').show();
                $('#name_2').hide();
                $('#name_1 input').prop('required', true);
                $('#name_2 input').prop('required', false);
            } else if ($(this).val() == 2) {
                $('#registration_number_col .form-label').text('Cégjegyzékszám');
                $('#tax_number_col').show();
                $('#company_form_col').show();
                $('#contact_name_col').show();
                $('#name_1').hide();
                $('#name_2').show();
                $('#name_2 input').prop('required', true);
                $('#name_1 input').prop('required', false);
            }
        });

        $('.secondary-data').hide();
    });
</script>