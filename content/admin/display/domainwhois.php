<?php
$pageMeta = [
    'title' => 'Domain ellenőrzés',
];
?>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-9">
                <input type="text" class="form-control" id="domain" placeholder="Domain név">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" id="checkDomain"><i class="fa fa-search me-2"></i> Ellenőrzés</button>
            </div>
        </div>

        <div id="domainInfo">
            <div class="alert alert-info mb-0">
                Egy domain lekérdezéséhez adj meg egy domain nevet, majd kattints az Ellenőrzés gombra.
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('#checkDomain').click(function() {
            var domain = $('#domain').val();
            if (domain.length < 3) {
                $('#domainInfo').html('<div class="alert alert-danger mb-0">Kérlek adj meg egy érvényes domain nevet!</div>');
                return;
            }
            $('#domainInfo').html('<div class="alert alert-info mb-0">Betöltés...</div>');
            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/webhosting/domainwhois',
                type: 'POST',
                data: {
                    domain: domain
                },
                success: function(response) {
                    $('#domainInfo').html(response);
                },
                error: function() {
                    $('#domainInfo').html('<div class="alert alert-danger mb-0">Hiba történt a lekérdezés során!</div>');
                }
            });
        });

        $('#domain').on('keypress', function(e) {
            if (e.which == 13) {
                $('#checkDomain').click();
            }
        });
    });
</script>