<?php
$url = str_replace('ugyfel', '', $url);
$url = ltrim($url, '/');

if ((!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 'client') && strpos($url, 'belepes') === false) {
    session_regenerate_id();
    $_SESSION['loggedin'] = '';
    redirect(URL . 'ugyfel/belepes');
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'client') {
    $user = new User($_SESSION['id']);
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'client' && $url == 'belepes') {
    redirect(URL . 'ugyfel/projektem');
}

// URL beállítása
if (empty($url)) {
    redirect(URL . 'ugyfel/projektem');
}

if (!file_exists(ABS_PATH . 'content/ugyfel/display/' . $url . '.php') && !file_exists(ABS_PATH . 'content/ugyfel/' . $url . '.php') && $url != '404' && strpos($url, 'belepes/') === false && $url != 'belepes' && strpos($url, 'assets/') === false) {
    $url = '404';
}

// Keressük meg a fájlt
if (strpos($url, 'belepes/') === false && $url != 'belepes') {
    if (strpos($url, 'process/') !== false && file_exists(ABS_PATH . 'content/ugyfel/process/' . str_replace('process/', '', $url) . '.php')) {
        require_once ABS_PATH . 'content/ugyfel/' . $url . '.php';
    } elseif (file_exists(ABS_PATH . 'content/ugyfel/display/' . $url . '.php')) {
        require_once ABS_PATH . 'content/ugyfel/template.php';
    } else {
        if (strpos($url, 'assets') !== false) {
            exit('A keresett oldal nem található!');
        }
        $url = '404';
        require_once ABS_PATH . 'content/ugyfel/template.php';
    }
} else {
    $modified_url = str_replace('belepes', '', $url);
    $modified_url = ltrim($modified_url, '/');
    if (empty($modified_url)) {
        $modified_url = 'index';
    }
    if (file_exists(ABS_PATH . 'content/ugyfel/login/' . $modified_url . '.php')) {
        require_once ABS_PATH . 'content/ugyfel/login/' . $modified_url . '.php';
    } else {
        echo 'A fájl nem található!';
    }
}
