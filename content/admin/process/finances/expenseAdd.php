<?php
$type = $_POST['type'];
$project = $_POST['project'];
$user = $_POST['user'];
$reason = $_POST['reason'];
$date = $_POST['date'];
$amount = $_POST['amount'];

if ($type == 'expense') {
    if ($stmt = $con->prepare('INSERT INTO `fin_expenses`(`id`, `reason`, `amount`, `datetime`) VALUES (NULL, ?, ?, ?)')) {
        $stmt->bind_param('sds', $reason, $amount, $date);
        if (!$stmt->execute()) {
            alert_redirect('error', URL . 'admin/penzugyek');
        }
        $stmt->close();
    }
} else {
    if ($stmt = $con->prepare('INSERT INTO `fin_payouts`(`id`, `project_id`, `account_id`, `amount`, `datetime`) VALUES (NULL, ?, ?, ?, ?)')) {
        $stmt->bind_param('iids', $project, $user, $amount, $date);
        if (!$stmt->execute()) {
            alert_redirect('error', URL . 'admin/penzugyek');
        }
        $stmt->close();
    }
}

alert_redirect('success', URL . 'admin/penzugyek');
