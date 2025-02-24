<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== 'admin') {
    exit('Hozzáférés megtagadva!');
}

$project = new Project($data[0]);

$dir = ABS_PATH . 'storage/' . $project->getId() . '/';
$filename = $dir . 'screenshot.jpeg';

$params = http_build_query(array(
    "access_key" => "82a2812f2d0143f7b0b6d8298a25f965",
    "url" => $project->getUrl(),
    "no_cookie_banners" => "true",
));

$image_data = file_get_contents("https://api.apiflash.com/v1/urltoimage?" . $params);
if (!file_exists($dir)) {
    mkdir($dir);
}


if (!file_put_contents($filename, $image_data)) {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $project->getId(), 'Hiba történt a kép feltöltése közben!');
}

$project->updateImageUrl('storage/' . $project->getId() . '/screenshot.jpeg');

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $project->getId());
