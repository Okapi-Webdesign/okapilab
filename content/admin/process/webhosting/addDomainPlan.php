<?php
$tld = $_POST['tld'] ?? null;
$price = $_POST['price'] ?? null;

if ($stmt = $con->prepare('INSERT INTO `wh_domainprices`(`id`, `tld`, `yearly_price`) VALUES (NULL, ?, ?)')) {
    $stmt->bind_param('sd', $tld, $price);
    if ($stmt->execute()) {
        alert_redirect('success', URL . 'admin/webhoszting');
    } else {
        alert_redirect('error', URL . 'admin/webhoszting');
    }
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/webhoszting');
}
