<?php
if (!defined('FILE_IMPORT')) {
    exit('Hozzáférés megtagadva!');
}

if (mail_send_template(ADMIN_EMAIL, 'test', [
    'date' => date('Y-m-d H:i:s')
])) {
    alert_redirect('success', URL . 'admin/beallitasok', 'Sikeres teszt!');
} else {
    alert_redirect('error', URL . 'admin/beallitasok', 'Hiba történt a teszt során!');
}
