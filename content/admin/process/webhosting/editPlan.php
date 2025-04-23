<?php
$id = $_POST['id'] ?? 0;
$name = $_POST['name'];
$size = $_POST['size'];
$cost = $_POST['cost'];
$monthly_price = $_POST['monthly_price'];
$yearly_price = $_POST['yearly_price'];
$wordpress = $_POST['wordpress'];
$managed = $_POST['managed'];

if ($monthly_price * 12 < $yearly_price) {
    alert_redirect('error', URL . 'admin/webhoszting', 'Az éves díj nem lehet több mint a havi díj 12-szerese!');
}

if ($stmt = $con->prepare('UPDATE `wh_plans` SET `name` = ?, `size` = ?, `cost` = ?, `monthly_price` = ?, `yearly_price` = ?, `wordpress` = ?, `managed` = ? WHERE `id` = ?')) {
    $stmt->bind_param('siiiiiii', $name, $size, $cost, $monthly_price, $yearly_price, $wordpress, $managed, $id);
    if (!$stmt->execute()) {
        alert_redirect('error', URL . 'admin/webhoszting');
    }
    alert_redirect('success', URL . 'admin/webhoszting');
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/webhoszting');
}

alert_redirect('error', URL . 'admin/webhoszting');
