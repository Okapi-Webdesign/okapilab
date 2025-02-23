<?php
$email = $_POST['email'];
$password = $_POST['password'];
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];

if (empty($email) || empty($password) || empty($lastname) || empty($firstname)) {
    alert_redirect('error', URL . 'admin/beallitasok');
}

// email ellenőrzés
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    alert_redirect('error', URL . 'admin/beallitasok');
}

if ($stmt = $con->prepare("SELECT `id` FROM `accounts` WHERE `email` = ?")) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        alert_redirect('error', URL . 'admin/beallitasok', 'Ez az e-mail cím már regisztrálva van!');
    }
    $stmt->close();
}

// hozzáadás
$hash = password_hash($password, PASSWORD_DEFAULT);
if ($stmt = $con->prepare("INSERT INTO `accounts`(`id`, `lastname`, `firstname`, `email`, `password`, `lastlogin_date`, `role`, `client_id`, `active`) VALUES (NULL, ?, ?, ?, ?, NULL, 2, NULL, 1)")) {
    $stmt->bind_param('ssss', $lastname, $firstname, $email, $hash);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/beallitasok');
    }
    $stmt->close();
}

alert_redirect('success', URL . 'admin/beallitasok');
