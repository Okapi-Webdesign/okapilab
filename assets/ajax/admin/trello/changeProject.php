<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
$user = new User($_SESSION['id']);

$project_id = $_POST['project_id'];
$trello_id = $_POST['trello_id'];

// ellenőrizzük, hogy van-e már ilyen trello id-jú felhasználó
if ($stmt = $con->prepare('SELECT `id` FROM `trello_projects` WHERE `project_id` = ?')) {
    $stmt->bind_param('i', $project_id);
    if (!$stmt->execute()) {
        exit('Hiba történt a lekérdezés közben!');
    }
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $update = true;
    } else {
        $update = false;
    }
    $stmt->close();
}

if ($update) {
    if ($stmt = $con->prepare('UPDATE `trello_projects` SET `trello_id` = ? WHERE `project_id` = ?')) {
        $stmt->bind_param('si', $trello_id, $project_id);
        if (!$stmt->execute()) {
            exit('Hiba történt a mentés közben!');
        }
        $stmt->close();
    } else {
        exit('Hiba történt a mentés közben!');
    }
} else {
    if ($stmt = $con->prepare('INSERT INTO `trello_projects` (`project_id`, `trello_id`) VALUES (?, ?)')) {
        $stmt->bind_param('is', $project_id, $trello_id);
        if (!$stmt->execute()) {
            exit('Hiba történt a mentés közben!');
        }
        $stmt->close();
    } else {
        exit('Hiba történt a mentés közben!');
    }
}

echo 'success';
