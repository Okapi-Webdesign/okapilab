<?php
$id = $_POST['id'];

if ($stmt = $con->prepare('DELETE FROM `projects` WHERE `id` = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id);
    }
    $stmt->close();
}

// delete folder
$folder = ABS_PATH . 'storage/' . $id;
if (is_dir($folder)) {
    $files = glob($folder . '/*');
    foreach ($files as $file) {
        unlink($file);
    }
    rmdir($folder);
}

alert_redirect('success', URL . 'admin/projektek');
