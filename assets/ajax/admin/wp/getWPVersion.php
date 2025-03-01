<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = $_POST['project'];
$formatted = $_POST['formatted'];
$wp = new WordPressConnection($project);
$version = $wp->getVersion()['wp'];

if (!$formatted) {
    echo $version;
} else {
    $latest = WordPressConnection::getLatestWpVersion();
    if ($wp->isUpToDate()) echo '<span class="text-muted">' . $version . '</span>';
    else {
        if (explode('.', $version[0] < explode('.', $latest)[0]) || (explode('.', $version)[1] != explode('.', $latest)[1])) {
            echo '<span class="text-danger">' . $version . '</span>';
        } else {
            echo '<span class="text-warning">' . $version . '</span>';
        }
    }
}
