<?php
$id = $data[0];

if (!$con->query("DELETE FROM `projects_tags` WHERE `id` = $id")) {
    alert_redirect('error', URL . 'admin/beallitasok');
}

alert_redirect('success', URL . 'admin/beallitasok');
