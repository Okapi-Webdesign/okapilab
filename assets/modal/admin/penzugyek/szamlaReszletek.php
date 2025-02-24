<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$invoice = new Invoice($_POST['id']);
?>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa-solid fa-receipt me-2"></i> Számla részletei
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
</div>

<div class="modal-body">
    <div class="row g-3">
        <div class="col-12 col-md-6">
            <b>Számla:</b>
            <span class="d-block mb-3">
                <?php echo $invoice->getInvoiceId(); ?> <br>
                <b>Kiállítva:</b> <?php echo $invoice->getCreateDate(true); ?> <br>
                <b>Határidő:</b> <?php echo $invoice->getDeadline(true); ?>
            </span>

            <span class="d-block">
                <b>Összeg:</b> <?php echo number_format($invoice->getAmount(), 0, ',', ' '); ?> Ft <br>
                <b>Fizetve:</b> <?php echo $invoice->isPaid() ? 'Igen' : number_format($invoice->getPaymentsSum(), 0, 0, ' ') . ' Ft'; ?>
            </span>
        </div>
        <div class="col-12 col-md-6">
            <b>Projekt:</b>
            <span class="d-block mb-3">
                <?php echo $invoice->getProject()->getName(); ?> <br>
                <b>Ügyfél:</b> <?php echo $invoice->getProject()->getClient()->getName(); ?>
            </span>

            <label for="status" class="fw-bold">Státusz:</label>
            <select name="status" id="status" class="form-select">
                <?php if (!$invoice->isPaid()) { ?> <option value="0" <?php echo $invoice->getStatus() == 0 ? 'selected' : ''; ?>>Kiállítva</option> <?php } ?>
                <?php if ($invoice->isPaid()) { ?> <option value="1" <?php echo $invoice->getStatus() == 1 ? 'selected' : ''; ?>>Befizetve</option> <?php } ?>
                <option value="2" <?php echo $invoice->getStatus() == 2 ? 'selected' : ''; ?>>Sztornó</option>
            </select>
        </div>
        <div class="col-12">
            <b>Befizetések:</b>
            <div class="table-responsive mt-2">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Dátum</th>
                            <th>Összeg</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $payments = $invoice->getPayments();

                        if (empty($payments)) {
                            echo '<tr><td colspan="3">Nincs rögzített befizetés!</td></tr>';
                        }

                        foreach ($payments as $payment) {
                            echo '<tr>';
                            echo '<td>' . $payment->getId() . '</td>';
                            echo '<td>' . $payment->getPaymentDate(true) . '</td>';
                            echo '<td>' . number_format($payment->getAmount(), 0, ',', ' ') . ' Ft</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
</div>

<script>
    $('document').ready(function() {
        $('#status').change(function() {
            $.ajax({
                url: '<?= URL ?>/assets/ajax/admin/finances/invoiceStatus.php',
                type: 'POST',
                data: {
                    id: <?php echo $invoice->getId(); ?>,
                    status: $(this).val()
                },
                success: function(response) {
                    console.log(response);
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres módosítás!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt a módosítás során!',
                            html: response
                        });
                    }
                }
            });
        });
    });
</script>