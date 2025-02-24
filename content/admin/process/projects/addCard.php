<?php
$name = $_POST['name'];
$deadline = $_POST['deadline'];
$description = $_POST['description'];
$list = $_POST['list'];
$project = new Project($_POST['project']);

$trello = new TrelloTable();
if (!$trello->createCard($project, $name, $description, 'bottom', $deadline, $list)) {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $project->getId());
}

alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $project->getId());
