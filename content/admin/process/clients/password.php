<?php
$c = new Client($_POST['id']);
$pass = $_POST['password'];
$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
if (empty($pass)) {
    alert_redirect('error', URL . 'admin/ugyfelek/adatlap/d/' . $c->getId(), 'A jelszó nem lehet üres!');
}

$uid = $c->getAccountId();

if ($stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?')) {
    $stmt->bind_param('si', $pass_hash, $uid);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/ugyfelek/adatlap/d/' . $c->getId());
    }
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/ugyfelek/adatlap/d/' . $c->getId());
}

if (!mail_send_template($c->getEmail(), 'password_reset', [
    'name' => $c->getContactName(),
    'password' => $pass
])) {
    alert_redirect('warning', URL . 'admin/ugyfelek/adatlap/d/' . $c->getId(), 'A jelszó módosítva, de a jelszóemlékeztető e-mail nem került elküldésre!');
}

alert_redirect('success', URL . 'admin/ugyfelek/adatlap/d/' . $c->getId());
