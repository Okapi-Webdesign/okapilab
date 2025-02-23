<?php
$project = new Project($_POST['project']);
$invoice_id = $_POST['invoice_id'];
$amount = intval($_POST['amount']);
$create_date = $_POST['create_date'];
$deadline = $_POST['deadline'];
$file = $_FILES['file'];
$pid = $project->getId();

$dir = ABS_PATH . 'storage/' . $project->getId() . '/invoices/';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = $invoice_id . '.' . $ext;
$target = $dir . $filename;

if ($ext != 'pdf') {
    alert_redirect('error', URL . 'admin/penzugyek', 'Csak PDF fájlok tölthetőek fel!');
}

if (!move_uploaded_file($file['tmp_name'], $target)) {
    alert_redirect('error', URL . 'admin/penzugyek', 'Hiba történt a fájl feltöltése során!');
}

if ($stmt = $con->prepare('INSERT INTO `invoices`(`id`, `invoice_id`, `project_id`, `create_date`, `deadline`, `amount`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, 0)')) {
    $stmt->bind_param('sissi', $invoice_id, $pid, $create_date, $deadline, $amount);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/penzugyek');
    }
    $stmt->close();
}

if (!isset($_POST['email_send']) && $_POST['email_send'] == 1) {
    if (!mail_send_template($project->getClient()->getEmail(), 'invoice_created', [
        'name' => $project->getClient()->getContactName(),
        'invoice_number' => $invoice_id,
        'date' => $create_date,
        'amount' => number_format($amount, 0, 0, ' '),
        'url' => URL . 'storage/' . $project->getId() . '/invoices/' . $filename
    ])) {
        alert_redirect('warning', URL . 'admin/penzugyek');
    }
}

alert_redirect('success', URL . 'admin/penzugyek');
