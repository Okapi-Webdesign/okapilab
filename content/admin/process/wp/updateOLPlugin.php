<?php
$file = $_FILES['file'];
$version = $_POST['version'];

if (empty($file) || empty($version)) {
    alert_redirect('error', URL . 'admin/wordpress');
}

if (array_reverse(explode('.', $file['name']))[0] !== 'zip') {
    alert_redirect('error', URL . 'admin/wordpress', 'A fájl nem ZIP formátumú!');
}

// Fájl feltöltése kicsomagolás nélkül
$target = ABS_PATH . 'assets/okapi-wp_latest.zip';
if (!move_uploaded_file($file['tmp_name'], $target)) {
    alert_redirect('error', URL . 'admin/wordpress', 'A fájl feltöltése sikertelen!');
}

// Plugin verzió frissítése
updateSetting('wp_plugin_version', $version);

alert_redirect('success', URL . 'admin/wordpress');
