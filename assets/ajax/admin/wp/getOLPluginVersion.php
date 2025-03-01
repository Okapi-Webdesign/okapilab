<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = $_POST['project'];
$formatted = $_POST['formatted'];
$wp = new WordPressConnection($project);
$version = $wp->getVersion()['plugin'];

if (!$formatted) {
    echo $wp->getVersion()['plugin'];
} else {
    $latest = getSetting('wp_plugin_version');
    if ($version == $latest) echo '<span class="text-muted">' . $version . '</span>';
    else {
        echo '<span class="text-danger">' . $version . '</span>';
    }
}
