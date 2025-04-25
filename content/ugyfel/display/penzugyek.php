<?php
$pageMeta = [
    'title' => 'Pénzügyek',
    'description' => 'Itt találod a projektedhez tartozó pénzügyi adatokat.'
];

$project = new Project($_SESSION['project']);
$invoices = $project->getInvoices();
$cid = $project->getClient()->getId();

if ($stmt = $con->prepare('SELECT invoices.id FROM (invoices LEFT JOIN wh_subscriptions subs ON wh_id = subs.id) LEFT JOIN wh_domains dom ON dom.id = domain_id WHERE subs.client = ? OR dom.client = ?')) {
    $stmt->bind_param('ii', $cid, $cid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id);
    while ($stmt->fetch()) {
        $invoice = new Invoice($id);
        if ($invoice->getStatus() == 2) {
            continue;
        }
        $invoices[] = $invoice;
    }
    $stmt->close();
}

$sum = 0;
$sumPaid = 0;

foreach ($invoices as $invoice) {
    if ($invoice->getStatus() == 2) {
        continue;
    }
    $sum += $invoice->getAmount();
    $sumPaid += $invoice->getPaymentsSum();
}
?>

<div class="card p-2">
    <div class="card-body">
        <?php
        if ($sum - $sumPaid > 0) {
            echo '<div class="alert alert-danger mb-3" role="alert">
            <strong>Figyelem!</strong> Jelenleg ' . number_format($sum - $sumPaid, 0, ',', ' ') . ' Ft ki nem egyenlített tartozásod van a projektedhez. Kérjük, mihamarabb fizesd be a fennálló tartozást, hogy elkerüld a szolgáltatásaid felfüggesztését!
            </div>';
        }
        ?>
        <div class="d-flex justify-content-center align-items-center h-100 gap-md-5 gap-1 flex-column flex-md-row mb-3">
            <span><b>Összeg:</b> <?= number_format($sum, 0, ',', ' ') ?> Ft</span>
            <span><b>Befizetve:</b> <?= number_format($sumPaid, 0, ',', ' ') ?> Ft</span>
            <span class="text-<?= $sum - $sumPaid > 0 ? 'danger' : 'success' ?>"><b>Fizetendő:</b> <?= number_format($sum - $sumPaid, 0, ',', ' ') ?> Ft</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Számla</th>
                        <th>Kiállítás dátuma</th>
                        <th>Befizetés határideje</th>
                        <th>Összeg</th>
                        <th>Státusz</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($invoices)) {
                        echo '<tr><td colspan="5" class="text-center">Nincsenek számlák a projektedhez.</td></tr>';
                    }

                    foreach ($invoices as $invoice) {
                    ?>
                        <tr>
                            <td><a href="<?= URL ?>storage/<?= $cid ?>/invoices/<?= $invoice->getInvoiceId() ?>.pdf" target="_blank" class="text-decoration-none"><i class="fa fa-file-pdf me-2"></i> <?= $invoice->getInvoiceId() ?></a></td>
                            <td><?= $invoice->getCreateDate(true) ?></td>
                            <td><?= $invoice->getDeadline(true) ?></td>
                            <td><?= number_format($invoice->getAmount(), 0, ',', ' ') ?> Ft</td>
                            <td><?= $invoice->getStatus(2) ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>