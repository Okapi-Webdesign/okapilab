<?php
if (!$user->role(4)) {
    alert_redirect('error', URL . 'admin', 'Hozzáférés megtagadva!');
}

foreach ($_POST as $key => $value) {
    $key = safeString($key);
    $value = safeString($value);
    updateSetting($key, $value);
}

alert_redirect('success', URL . 'admin/beallitasok');
