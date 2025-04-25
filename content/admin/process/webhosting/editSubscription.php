<?php
$id = $_POST['id'] ?? 0;
$subs = new WHSubscription($id);

$plan = new WHPlan($_POST['plan'] ?? 0);
$pid = $plan->getId();
$price = $_POST['price'] ?? 0;

$standardPrice = $subs->getBillingPeriod() == 'yearly' ? $plan->getYearlyPrice() : $plan->getMonthlyPrice();

if ($price == $standardPrice) {
    $price = null;
}

if ($stmt = $con->prepare('UPDATE wh_subscriptions SET plan = ?, price = ? WHERE id = ?')) {
    $stmt->bind_param('isi', $pid, $price, $id);
    if ($stmt->execute()) {
        alert_redirect('success', URL . 'admin/webhoszting/adatlap/d/' . $id);
    } else {
        alert_redirect('error', URL . 'admin/webhoszting/adatlap/d/' . $id);
    }
} else {
    alert_redirect('error', URL . 'admin/webhoszting/adatlap/d/' . $id);
}
