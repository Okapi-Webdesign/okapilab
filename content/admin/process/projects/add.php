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
if ($stmt = $con->prepare('INSERT INTO `projects`(`id`, `client_id`, `name`, `url`, `status`, `tags`, `services`, `manager_id`, `comment`, `warranty`, `is_wordpress`, `active`, `image_uri`) VALUES (NULL, ?, ?, ?, ?, NULL, NULL, ?, NULL, NULL, 0, 1, NULL)')) {
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

// Email kiküldése
if (isset($_POST['email_send']) && $_POST['email_send'] == 1) {
    $client = new Client($client);
    if (!mail_send_template($client->getEmail(), 'project_created', [
        'name' => $client->getName(),
        'project' => $name
    ])) {
        alert_redirect('warning', URL . 'admin/projektek', 'Hiba történt az e-mail kiküldése közben!');
    }
}

// Átiránytás
alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $pid);
