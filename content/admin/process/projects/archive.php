<?php
$id = $_POST['id'];
$project = new Project($id);

if ($stmt = $con->prepare('UPDATE `projects` SET `active` = 0 WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id);
    }
    $stmt->close();
}

if ($project->getStatus(1) == 'Befejezett') {
    $wdate = date('Y-m-d', strtotime('+6 months'));
    if ($stmt = $con->prepare('UPDATE `projects` SET `warranty` = ? WHERE `id` = ?')) {
        $stmt->bind_param('si', $wdate, $id);
        if (!$stmt->execute()) {
            alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id);
        }
        $stmt->close();
    }
} 

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $id);
