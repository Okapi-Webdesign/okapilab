<?php
session_start();
$abspath = __DIR__;
$abspath = str_replace('inc', '', $abspath);
define('ABS_PATH', $abspath);

if (!defined('FILE_IMPORT')) {
    exit('Hozzáférés megtagadva!');
}

// Konfigurációs fájl betöltése
require_once ABS_PATH . 'config.php';

if (DEV_MODE == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Modulok
require(ABS_PATH . "lib/PHPMailer/src/PHPMailer.php");
require(ABS_PATH . "lib/PHPMailer/src/SMTP.php");
require(ABS_PATH . "lib/PHPMailer/src/Exception.php");

// Függvények betöltése
require_once ABS_PATH . 'inc/functions.php';

// Adatbázis kapcsolat létrehozása
require_once ABS_PATH . 'inc/db.php';

// Felhasználó betöltése
require_once ABS_PATH . 'inc/user.php';
require_once ABS_PATH . 'inc/client.php';
require_once ABS_PATH . 'inc/project.php';
require_once ABS_PATH . 'inc/projectstatus.php';
require_once ABS_PATH . 'inc/projectlogin.php';
require_once ABS_PATH . 'inc/document.php';
require_once ABS_PATH . 'inc/documenttype.php';
require_once ABS_PATH . 'inc/documentversion.php';
require_once ABS_PATH . 'inc/invoice.php';
require_once ABS_PATH . 'inc/finincome.php';
require_once ABS_PATH . 'inc/finexpense.php';
require_once ABS_PATH . 'inc/finpayout.php';
require_once ABS_PATH . 'inc/trello.php';
require_once ABS_PATH . 'inc/wpconnection.php';
require_once ABS_PATH . 'inc/whplan.php';
require_once ABS_PATH . 'inc/whdomainplan.php';
require_once ABS_PATH . 'inc/whsubscription.php';
require_once ABS_PATH . 'inc/whdomain.php';
