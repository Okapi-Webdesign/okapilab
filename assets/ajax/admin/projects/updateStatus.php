<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
$user = new User($_SESSION['id']);

$project = new Project($_POST['project']);
$status = $_POST['status'];

if (!$project->setStatus($status)) {
    exit('Hiba történt a státusz mentése közben!');
}

if ($project->getTrelloId() !== false && $status == ProjectStatus::getMax()->getId()) {
    $trello = new TrelloTable();
    $trello->createCard($project, 'Teljesítési igazolás és számla kiállítása', '', 'top', date('Y-m-d', strtotime('+1 day')), $trello->getListByName('Teendő')['id'], true);
}

echo 'success';
