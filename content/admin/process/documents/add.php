<?php

$project = $_POST['project'];
$type = $_POST['type'];

if ($stmt = $con->prepare('INSERT INTO `documents`(`id`, `project_id`, `type`) VALUES (NULL, ?, ?)')) {
    $stmt->bind_param('ii', $project, $type);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/dokumentumok', 'A dokumentum létrehozása sikertelen!');
    }
    $id = $stmt->insert_id;
    $stmt->close();
}

$document = new Document($id);

$output = $document->addVersion($_FILES['file']);
if (!$output['status']) {
    alert_redirect('error', URL . 'admin/dokumentumok', $output['message']);
}

alert_redirect('success', URL . 'admin/dokumentumok');