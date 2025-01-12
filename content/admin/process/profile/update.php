<?php
if (!$user->role(2)) {
    alert_redirect('error', URL . 'admin/profil', 'Hozzáférés megtagadva!');
}

$email = $_POST['email'];
$uid = $user->getId();

if ($email == $user->getEmail()) {
    alert_redirect('error', URL . 'admin/profil', 'Az új e-mail cím nem lehet azonos a régi e-mail címmel!');
}

if (empty($email)) {
    alert_redirect('error', URL . 'admin/profil', 'Az e-mail cím megadása kötelező!');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    alert_redirect('error', URL . 'admin/profil', 'Az e-mail cím formátuma nem megfelelő!');
}

if ($stmt = $con->prepare('SELECT id FROM accounts WHERE email = ?')) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        alert_redirect('error', URL . 'admin/profil', 'Az e-mail cím már foglalt!');
    }

    $stmt->close();
}

if ($stmt = $con->prepare('UPDATE accounts SET email = ? WHERE id = ?')) {
    $stmt->bind_param('si', $email, $uid);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/profil');
    }
    $stmt->close();

    alert_redirect('success', URL . 'admin/profil');
}

alert_redirect('error', URL . 'admin/profil');
