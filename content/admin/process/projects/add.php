<?php
$client = $_POST['client'];
$name = $_POST['name'];
$url = empty($_POST['url']) ? null : $_POST['url'];
$status = $_POST['status'];
$manager = $_POST['manager'];

// Projekt nevének ellenőrzése az adatbázisban
if ($stmt = $con->prepare('SELECT `id` FROM `projects` WHERE `name` = ?')) {
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        alert_redirect('error', URL . 'admin/projektek', 'A megadott névvel már létezik projekt!');
    }
    $stmt->close();
}

// Új projekt hozzáadása az adatbázishoz
if ($stmt = $con->prepare('INSERT INTO `projects`(`id`, `client_id`, `name`, `url`, `status`, `labels`, `services`, `manager_id`, `comment`, `warranty`, `is_wordpress`, `active`, `logo_url`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL, ?, NULL, NULL, 0, 1, NULL)')) {
    $stmt->bind_param('issii', $client, $name, $url, $status, $manager);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek', 'Hiba történt a projekt hozzáadása közben!');
    }
    $pid = $stmt->insert_id;
    $stmt->close();
}

// Projekt mappájának létrehozása
$dir = ABS_PATH . 'storage/' . $pid;
if (!file_exists($dir)) {
    mkdir($dir);
}

// Átiránytás
alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $pid);
