<?php
if (!defined('ABS_PATH')) {
    die('Hozzáférés megtagadva!');
}

// Kijelentkezés
if (isset($_SESSION['loggedin'])) {
    unset($_SESSION['loggedin']);
    unset($_SESSION['name']);
    unset($_SESSION['id']);
    setcookie('username', '', time() - 3600, '/');
    setcookie('password', '', time() - 3600, '/');
    setcookie('platform', '', time() - 3600, '/');
}

redirect(URL . 'admin/belepes');
