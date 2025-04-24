<?php
$client = $_POST['client'] ?? null;
$plan = $_POST['plan'] ?? null;
$period = $_POST['period'] ?? null;
$price = $_POST['price'] ?? null;

if ($stmt = $con->prepare('INSERT INTO `wh_subscriptions`(`id`, `client`, `plan`, `billing_period`, `price`, `create_date`, `last_renewal`, `status`) VALUES (null, ?, ?, ?, null, now(), now(), 1)')) {
    $stmt->bind_param('iis', $client, $plan, $period);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        alert_redirect('success', URL . 'admin/webhoszting');
    } else {
        alert_redirect('error', URL . 'admin/webhoszting');
    }
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/webhoszting');
}
