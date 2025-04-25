<?php
$subs = new WHSubscription($_POST['id']);
$invoice_id = $_POST['invoice_id'];
$amount = intval($_POST['amount']);
$create_date = $_POST['invoice_date'];
$deadline = $_POST['invoice_due_date'];
$file = $_FILES['invoice_file'];
$sid = $subs->getId();

$dir = ABS_PATH . 'storage/' . $subs->getClient()->getId() . '/invoices/';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = $invoice_id . '.' . $ext;
$target = $dir . $filename;

if ($ext != 'pdf') {
    alert_redirect('error', URL . 'admin/webhoszting', 'Csak PDF fájlok tölthetőek fel!');
}

if (!move_uploaded_file($file['tmp_name'], $target)) {
    alert_redirect('error', URL . 'admin/webhoszting', 'Hiba történt a fájl feltöltése során!');
}

if ($stmt = $con->prepare('INSERT INTO `invoices`(`id`, `invoice_id`, `wh_id`, `create_date`, `deadline`, `amount`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, 0)')) {
    $stmt->bind_param('sissi', $invoice_id, $sid, $create_date, $deadline, $amount);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/penzugyek');
    }
    $stmt->close();
}

$expiry = date('Y-m-d H:i:s', $subs->getExpiry());
if ($stmt = $con->prepare('UPDATE `wh_subscriptions` SET `last_renewal`=? WHERE `id`=?')) {
    $stmt->bind_param('si', $expiry, $sid);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/webhoszting', 'A számla kiállítása sikeres volt. A tárhelyet nem sikerült megújítani!');
    }
    $stmt->close();
}

// Send email to client

if (!isset($_POST['email_send']) && $_POST['email_send'] == 1) {
    if (!mail_send_template($subs->getClient()->getEmail(), 'invoice_created', [
        'name' => $subs->getClient()->getContactName(),
        'invoice_number' => $invoice_id,
        'date' => $create_date,
        'amount' => number_format($amount, 0, 0, ' '),
        'url' => URL . 'storage/' . $subs->getClient()->getId() . '/invoices/' . $filename
    ])) {
        alert_redirect('warning', URL . 'admin/penzugyek');
    }
}

alert_redirect('success', URL . 'admin/penzugyek');
