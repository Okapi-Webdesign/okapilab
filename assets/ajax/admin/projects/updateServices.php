<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['project']);
$services = $_POST['services'];
if ($services === NULL) {
    $services = [];
}

if (!$project->setServices($services)) {
    exit('Hiba történt a szolgáltatások mentése közben!');
}

echo 'success';
