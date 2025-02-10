<?php
$url = str_replace('ugyfel', '', $url);
$url = ltrim($url, '/');

if ((!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 'client') && strpos($url, 'belepes') === false) {
    session_regenerate_id();
    $_SESSION['loggedin'] = '';
    redirect(URL . 'ugyfelbelepes');
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'client') {
    $user = new User($_SESSION['id']);
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'client' && $url == 'belepes') {
    redirect(URL . 'ugyfeliranyitopult');
}

// URL beállítása
if (empty($url)) {
    redirect(URL . 'ugyfeliranyitopult');
}

if (!file_exists(ABS_PATH . 'content/ugyfeldisplay/' . $url . '.php') && !file_exists(ABS_PATH . 'content/admin/' . $url . '.php') && $url != '404' && strpos($url, 'belepes/') === false && $url != 'belepes' && strpos($url, 'assets/') === false) {
    $url = '404';
}

// Keressük meg a fájlt
if (strpos($url, 'belepes/') === false && $url != 'belepes') {
    if (strpos($url, 'process/') !== false && file_exists(ABS_PATH . 'content/ugyfelprocess/' . str_replace('process/', '', $url) . '.php')) {
        require_once ABS_PATH . 'content/ugyfel' . $url . '.php';
    } elseif (file_exists(ABS_PATH . 'content/ugyfeldisplay/' . $url . '.php')) {
        require_once ABS_PATH . 'content/ugyfeltemplate.php';
    } else {
        if (strpos($url, 'assets') !== false) {
            exit('A keresett oldal nem található!');
        }
        $url = '404';
        require_once ABS_PATH . 'content/ugyfeltemplate.php';
    }
} else {
    $modified_url = str_replace('belepes', '', $url);
    $modified_url = ltrim($modified_url, '/');
    if (empty($modified_url)) {
        $modified_url = 'index';
    }
    if (file_exists(ABS_PATH . 'content/ugyfellogin/' . $modified_url . '.php')) {
        require_once ABS_PATH . 'content/ugyfellogin/' . $modified_url . '.php';
    } else {
        echo 'A fájl nem található!';
    }
}
