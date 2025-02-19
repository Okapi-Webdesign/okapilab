<?php
$id = $_POST['id'];

if ($stmt = $con->prepare('UPDATE `projects` SET `active` = 0, `status` = (SELECT MAX(id) FROM projects_status) WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

alert_redirect('success', URL . 'admin/projektek');
