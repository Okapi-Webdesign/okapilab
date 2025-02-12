<?php
if (!$user->role(2)) {
    alert_redirect('error', URL . 'admin/profil', 'Hozzáférés megtagadva!');
}

$oldpassword = $_POST['password'];
$newpassword = $_POST['password2'];
$newpassword2 = $_POST['password3'];

$uid = $user->getId();

if (empty($oldpassword) || empty($newpassword) || empty($newpassword2)) {
    alert_redirect('error', URL . 'admin/profil', 'Minden mező kitöltése kötelező!');
}

if ($newpassword !== $newpassword2) {
    alert_redirect('error', URL . 'admin/profil', 'Az új jelszavak nem egyeznek!');
}

if ($stmt = $con->prepare('SELECT password FROM accounts WHERE id = ?')) {
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows <= 0) {
        alert_redirect('error', URL . 'admin/profil', 'Hiba történt a jelszó ellenőrzése közben!');
    }

    $stmt->bind_result($dbpassword);
    $stmt->fetch();
    $stmt->close();
}

if (!password_verify($oldpassword, $dbpassword)) {
    alert_redirect('error', URL . 'admin/profil', 'A jelenlegi jelszó nem megfelelő!');
}

$passwordhash = password_hash($newpassword, PASSWORD_DEFAULT);

if ($stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?')) {
    $stmt->bind_param('si', $passwordhash, $uid);
    $stmt->execute();
    $stmt->close();
}

alert_redirect('success', URL . 'admin/profil');
