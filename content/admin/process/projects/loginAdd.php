<?php
$id = $_POST['project'];
$project = new Project($id);

$name = $_POST['name'];
$url = $_POST['url'];
$username = $_POST['username'];
$password = $_POST['password'];
$private = isset($_POST['private']) ? 1 : 0;

if (empty($name) || empty($url) || empty($username) || empty($password)) {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id, 'Minden mező kitöltése kötelező!');
}

if (!$project->addLogin($name, $url, $username, $password, $private)) {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $id);
}

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $id);
