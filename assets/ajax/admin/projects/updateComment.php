<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$comment = $_POST['comment'];

if ($stmt = $con->prepare('UPDATE projects SET comment = ? WHERE id = ?')) {
    $stmt->bind_param('si', $comment, $_POST['project']);
    if (!$stmt->execute()) {
        exit('Hiba történt a megjegyzés mentése közben!');
    }
    $stmt->close();
} else {
    exit('Hiba történt a megjegyzés mentése közben!');
}

echo 'success';
