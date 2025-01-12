<?php
if (!defined('FILE_IMPORT')) {
    exit('Hozzáférés megtagadva!');
}
alert_redirect('success', URL . 'admin/beallitasok', 'Sikeres teszt!');
