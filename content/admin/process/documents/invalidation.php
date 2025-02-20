<?php
$id = $data[0];
$v = new DocumentVersion($id);

if ($stmt = $con->prepare('UPDATE documents_versions SET active = 0 WHERE id = ?')) {
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/dokumentumok/adatlap/d/' . $v->getDocument()->getId());
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/dokumentumok/adatlap/d/' . $v->getDocument()->getId());
