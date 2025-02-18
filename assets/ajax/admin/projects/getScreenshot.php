<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== 'admin') {
    exit('Hozzáférés megtagadva!');
}

$project = new Project($_POST['project']);

if ($project->getImageUri() === NULL) {
    echo 'https://placehold.co/300x200?text=' . str_replace(' ', '+', $project->getName());
} else {
    echo $project->getImageUri();
}
