<?php
$id = $_POST['id'];
$url = empty($_POST['url']) ? null : $_POST['url'];
$wordpress = $_POST['wordpress'];
$manager = $_POST['manager'];
$deadline = empty($_POST['deadline']) ? null : $_POST['deadline'];

// Projekt adatainak frissítése az adatbázisban
if ($stmt = $con->prepare('UPDATE `projects` SET `url` = ?, `is_wordpress` = ?, `manager_id` = ?, `deadline` = ? WHERE `id` = ?')) {
    $stmt->bind_param('siisi', $url, $wordpress, $manager, $deadline, $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id);
    }
    $stmt->close();
}

// Átirányítás
alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $id);
