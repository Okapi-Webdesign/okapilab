<?php
$invoice_id = $_POST['invoice_id'];
$amount = intval($_POST['amount']);
$pay_date = $_POST['pay_date'];

if ($stmt = $con->prepare('INSERT INTO `fin_incomes`(`id`, `invoice_id`, `amount`, `datetime`) VALUES (NULL, ?, ?, ?)')) {
    $stmt->bind_param('iis', $invoice_id, $amount, $pay_date);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/penzugyek');
    }
    $stmt->close();
}

$invoice = new Invoice($invoice_id);
if ($invoice->getRemaining() <= 0) {
    if ($stmt = $con->prepare('UPDATE `invoices` SET `status` = 1 WHERE `id` = ?')) {
        $stmt->bind_param('i', $invoice_id);
        $stmt->execute();
        $stmt->close();
    }
}

alert_redirect('success', URL . 'admin/penzugyek');
