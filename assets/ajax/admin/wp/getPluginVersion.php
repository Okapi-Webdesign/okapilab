<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = $_POST['project'];
$formatted = $_POST['formatted'];
$latest = $_POST['latest'];
$slug = $_POST['slug'];
if (!isset($_POST['latest'])) {
    $latest = WordPressConnection::getLatestPluginVersion($slug)->version;
}
$wp = new WordPressConnection($project);
$current = $wp->getPluginVersion($slug);

if (!$formatted) {
    echo $current;
} else {
    if ($current === $latest) {
        echo '<span class="text-muted">' . $current . '</span>';
    } else {
        echo '<span class="text-danger">' . $current . '</span>';
    }
}
