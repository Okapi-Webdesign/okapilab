<?php
$id = $_SESSION['project'];
$project = new Project($id);

$name = $_POST['name'];
$url = $_POST['url'];
$username = $_POST['username'];
$password = $_POST['password'];

if (empty($name) || empty($url) || empty($username) || empty($password)) {
    alert_redirect('error', URL, 'Minden mező kitöltése kötelező!');
}

if (!$project->addLogin($name, $url, $username, $password, false)) {
    alert_redirect('error', URL);
}

alert_redirect('success', URL);
