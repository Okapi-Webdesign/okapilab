<?php
$id = $data[0];

if ($stmt = $con->prepare('DELETE FROM `clients` WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/ugyfelek');
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/ugyfelek');
