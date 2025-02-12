<?php
if (!defined('FILE_IMPORT')) {
    exit('Hozzáférés megtagadva!');
}

// A konfigurációs fájlt csak hozzáértő személyek módosíthatják.

// Az adatbázis elérési útvonala
define('DB_HOST', 'localhost');
// Az adatbázis felhasználóneve
define("DB_USER", "mysql_user");
// Az adatbázis jelszava
define("DB_PASS", "viewonly1!");
// Az adatbázis neve
define("DB_NAME", "okapilab");

// Az e-mail szerver elérési útvonala
define("SMTP_HOST", "");
// Az e-mail szerver portja
define("SMTP_PORT", 465);
// Az e-mail szerver felhasználóneve
define("SMTP_USER", "");
// Az e-mail szerver jelszava
define("SMTP_PASS", "");

// A webapp elérési útvonala
define("URL", "http://local.bozai.eu/");
// A webapp neve
define("APP_NAME", "OkapiLab");

// Fejlesztési üzemmód
define("DEV_MODE", false);
// E-mailek küldése
define("ENABLE_EMAILS", true);

// A webapp adminisztrátorának e-mail címe
define("ADMIN_EMAIL", "bozaiakos@bozaiakos.hu");
