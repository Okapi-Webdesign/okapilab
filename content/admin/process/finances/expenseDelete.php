<?php
$id = $data[0];
$type = $data[1];

$sql_type = "fin_$type" . 's';

if ($stmt = $con->prepare("DELETE FROM $sql_type WHERE `id` = ?")) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/penzugyek');
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/penzugyek');
