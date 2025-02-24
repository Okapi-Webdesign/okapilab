<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$id = $_POST['id'];
$status = $_POST['status'];

if ($stmt = $con->prepare('UPDATE invoices SET status = ? WHERE id = ?')) {
    $stmt->bind_param('ii', $status, $id);
    if (!$stmt->execute()) {
        exit();
    }
    $stmt->close();
    echo 'success';
} else {
    echo '';
}
