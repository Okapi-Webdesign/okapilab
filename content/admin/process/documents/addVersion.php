<?php
$document = new Document($_POST['document_id']);

$output = $document->addVersion($_FILES['file'], nl2br($_POST['changes']));
if (!$output['status']) {
    alert_redirect('error', URL . 'admin/dokumentumok/adatlap/d/' . $document->getId(), $output['message']);
}

alert_redirect('success', URL . 'admin/dokumentumok/adatlap/d/' . $document->getId());
