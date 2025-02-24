<?php
$user_id = $user->getId();
$trello_id = $_POST['account_trello'];

// ellenőrizzük, hogy van-e már ilyen trello id-jú felhasználó
if ($stmt = $con->prepare('SELECT `id` FROM `trello_accounts` WHERE `account_id` = ?')) {
    $stmt->bind_param('s', $user_id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/profil');
    }
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $update = true;
    } else {
        $update = false;
    }
    $stmt->close();
}

if ($update) {
    if ($stmt = $con->prepare('UPDATE `trello_accounts` SET `trello_id` = ? WHERE `account_id` = ?')) {
        $stmt->bind_param('ss', $trello_id, $user_id);
        if (!$stmt->execute()) {
            alert_redirect('error', URL . 'admin/profil');
        }
        $stmt->close();
    } else {
        alert_redirect('error', URL . 'admin/profil');
    }
} else {
    if ($stmt = $con->prepare('INSERT INTO `trello_accounts` (`account_id`, `trello_id`) VALUES (?, ?)')) {
        $stmt->bind_param('ss', $user_id, $trello_id);
        if (!$stmt->execute()) {
            alert_redirect('error', URL . 'admin/profil');
        }
        $stmt->close();
    } else {
        alert_redirect('error', URL . 'admin/profil');
    }
}

alert_redirect('success', URL . 'admin/profil');
