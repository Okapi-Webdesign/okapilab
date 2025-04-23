<?php
$id = $_POST['id'] ?? null;
$tld = $_POST['tld'] ?? null;
$price = $_POST['price'] ?? null;

if ($stmt = $con->prepare('UPDATE `wh_domainprices` SET `tld` = ?, `yearly_price` = ? WHERE `id` = ?')) {
    $stmt->bind_param('sdi', $tld, $price, $id);
    if ($stmt->execute()) {
        alert_redirect('success', URL . 'admin/webhoszting');
    } else {
        alert_redirect('error', URL . 'admin/webhoszting');
    }
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/webhoszting');
}
