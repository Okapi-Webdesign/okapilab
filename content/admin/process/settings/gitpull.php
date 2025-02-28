<?php
// TITLE: Frissítés
$current = get_appver();

$s = shell_exec('cd ' . $_SERVER['DOCUMENT_ROOT'] . ' && git pull');

if (!$s || $s == NULL) {
    alert_redirect("error", URL . 'admin/beallitasok');
}

$latest = get_appver();

if ($current == $latest) {
    alert_redirect("info", URL . 'admin/beallitasok', "Nincs új verzió!");
} else {
    alert_redirect("success", URL . 'admin/beallitasok');
}
