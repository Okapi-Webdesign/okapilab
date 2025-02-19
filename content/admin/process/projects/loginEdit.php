<?php
$id = $_POST['id'];
$login = new ProjectLogin($id);
$project = $login->getProject();

$name = $_POST['name'];
$url = $_POST['url'];
$username = $_POST['username'];
$password = $_POST['password'];
$private = isset($_POST['private']) ? 1 : 0;
$uid = $user->getId();
$pid = $project->getId();

if (empty($name) || empty($url) || empty($username) || empty($password)) {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $pid, 'Minden mező kitöltése kötelező!');
}

if ($stmt = $con->prepare('SELECT `id` FROM `projects_logins` WHERE `project_id` = ? AND `name` = ? AND `id` != ? AND (`private` = 0 OR `author` = ?)')) {
    $stmt->bind_param('isii', $pid, $name, $id, $uid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $pid, 'Ez a név már foglalt!');
    }
    $stmt->close();
}

if ($stmt = $con->prepare('UPDATE `projects_logins` SET `name` = ?, `url` = ?, `username` = ?, `password` = ?, `private` = ? WHERE `id` = ?')) {
    $stmt->bind_param('ssssii', $name, $url, $username, $password, $private, $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $pid);
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $pid);
