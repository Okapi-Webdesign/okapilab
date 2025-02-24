<?php
$id = $_POST['id'];

if ($stmt = $con->prepare('UPDATE `projects` SET `active` = 0 WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id);
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $id);
