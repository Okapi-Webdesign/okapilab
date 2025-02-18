<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['project']);
$status = $_POST['status'];

if (!$project->setStatus($status)) {
    exit('Hiba történt a státusz mentése közben!');
}

echo 'success';
