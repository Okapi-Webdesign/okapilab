<?php
$id = $data[0];

if ($stmt = $con->prepare('UPDATE wh_subscriptions SET billing_period = If(billing_period = "monthly", "yearly", "monthly") WHERE id = ?')) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $stmt->close();
        alert_redirect('success', URL . 'admin/webhoszting/adatlap/d/' . $id);
    }
}

$stmt->close();
alert_redirect('error', URL . 'admin/webhoszting/adatlap/d/' . $id);
