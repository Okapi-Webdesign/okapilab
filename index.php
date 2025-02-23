<?php
// A szükséges összetevők betöltése
define('FILE_IMPORT', true);
require_once 'inc/import.php';

// Alkalmazás verziója
define('APP_VERSION', '1.0.1');

$default_url = "projektek";

// Az URL-ből olvassuk ki az "url" GET paramétert
$url = isset($_GET['url']) ? explode("/d/", $_GET['url'], 2)[0] : "";
$datastring = isset($_GET['url']) ? str_replace("/d/", "", strstr($_GET['url'], "/d/", false)) : "";
$data = !empty($datastring) ? explode("/", $datastring) : [];

if (strpos($url, "admin/") !== false || $url == 'admin') {
    require_once 'content/admin/handler.php';
    exit;
} elseif (strpos($url, "ugyfel") !== false || $url == 'ugyfel') {
    require_once 'content/ugyfel/handler.php';
    exit;
} elseif (strpos($url, "assets") === false && strpos($url, 'favicon.ico') === false) {
    redirect(URL . 'ugyfel' . $url);
}

if (!file_exists("content/" . $url . '.php') && $url != '404' && $url != $default_url) {
    // Ha nem létezik, akkor a címsorban a 404 oldal jelenik meg
    $url = '404';
}

define('PAGE_ID', $url);

include 'content/template.php';
