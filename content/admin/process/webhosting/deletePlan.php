<?php
$id = $_POST['id'] ?? 0;

if ($stmt = $con->prepare('DELETE FROM `wh_plans` WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/webhoszting');
    }
    alert_redirect('success', URL . 'admin/webhoszting');
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/webhoszting');
}

alert_redirect('error', URL . 'admin/webhoszting');
