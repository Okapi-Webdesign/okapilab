<?php
header('Content-Type: application/json');
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['project']);

// Munkalap
$services = $project->getServices();
$isWp = $project->isWordpress();
$storage = $project->getWebhosting();
if ($storage) {
    $storage = round($storage->getPlan()->getSize() / 1000);
}

$domain = $project->getDomain();
$comment = $project->getComment();

// Szerződés
$client = $project->getClient();
$projectname = $project->getName();
if (!empty($domain) && $domain != $projectname && strlen($projectname) + strlen($domain) < 45) {
    $projectname .= ' (' . $domain . ')';
}

$data = [
    'munkalap' => [
        'services' => $services,
        'isWordpress' => $isWp,
        'storage' => $storage,
        'domain' => $domain,
        'comment' => $comment
    ],
    'szerzodes' => [
        'client_name' => $client->getName(),
        'client_address' => $client->getAddress(),
        'client_registration_number' => $client->getRegistrationNumber(),
        'client_tax_number' => $client->getTaxNumber(),
        'contact_name' => $client->getContactName(),
        'contact_email' => $client->getEmail(),
        'contact_phone' => $client->getPhone(),
        'project_name' => $projectname,
        'project_deadline' => $project->getDeadline(),
        'domain' => $domain,
    ]
];

echo json_encode($data);
