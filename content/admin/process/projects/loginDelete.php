<?php
$id = $data[0];
$login = new ProjectLogin($id);
$project = $login->getProject();
$pid = $project->getId();

if ($stmt = $con->prepare('DELETE FROM `projects_logins` WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $pid);
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $pid);
