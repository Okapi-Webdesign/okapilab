<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== 'admin') {
    exit('Hozzáférés megtagadva!');
}

$project = new Project($_POST['project']);

$dir = ABS_PATH . 'storage/' . $project->getId() . '/';
$filename = $dir . 'screenshot.jpeg';

$params = http_build_query(array(
    "access_key" => "82a2812f2d0143f7b0b6d8298a25f965",
    "url" => $project->getUrl(),
));

$image_data = file_get_contents("https://api.apiflash.com/v1/urltoimage?" . $params);
if (!file_exists($dir)) {
    mkdir($dir);
}

if (!file_put_contents($filename, $image_data)) {
    echo 'error';
}

$project->updateImageUrl('storage/' . $project->getId() . '/screenshot.jpeg');

echo URL . 'storage/' . $project->getId() . '/screenshot.jpeg';
