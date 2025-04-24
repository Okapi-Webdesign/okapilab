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

<form action="<?= URL ?>admin/process/webhosting/addDomainReg" method="post" class="needs-validation" novalidate>
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
                <label for="domain" class="form-label">Domain név</label>
                <input type="text" id="domain" name="domain" class="form-control" placeholder="domain.com" required>
            </div>

            <div id="infoBox"></div>
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
        $('#domain').on('keyup', function() {
            var domain = $(this).val();
            if (domain.length > 0) {
                $.ajax({
                    url: '<?= URL ?>assets/ajax/admin/webhosting/getTld.php',
                    type: 'POST',
                    data: {
                        domain: domain
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.error != null) {
                            $('#infoBox').html('');
                        } else {
                            var tld = response.tld;
                            var price = response.price;
                            var infoBox = '<div class="alert alert-info" role="alert">';
                            if (response.exists) infoBox += '<strong>A domain már regisztrálva van!</strong><br>';
                            else infoBox += '<strong>A domain szabad!</strong><br>';
                            infoBox += 'Végződés: .' + tld + '<br>';
                            infoBox += 'Éves díj: ' + price + '<br>';
                            infoBox += '</div>';
                            $('#infoBox').html(infoBox);
                        }
                    }
                });
            } else {
                $('#infoBox').html('');
            }
        });
    });
</script>