<?php
// A szükséges összetevők betöltése
header('Content-Type: application/json');
define('FILE_IMPORT', true);
require_once '../inc/import.php';

$wp_hash = $_POST['wp_hash'];
$connect_hash = $_POST['connect_hash'];

$project = WordPressConnection::getProjectByHash($wp_hash, $connect_hash);
if ($project === false) {
    echo json_encode(['error' => 'Hibás azonosító']);
    exit;
}

echo json_encode(['expires' => $project->getWarranty(false)]);
