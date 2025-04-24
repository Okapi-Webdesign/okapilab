<?php
header('Content-Type: application/json; charset=utf-8');
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
$user = new User($_SESSION['id']);

$domain = $_POST['domain'];
$domain = strtolower($domain);
$domain = trim($domain);
$domain = htmlspecialchars($domain, ENT_QUOTES, 'UTF-8');

$tld = array_reverse(explode('.', $domain))[0];

if (checkdnsrr($domain, 'ANY') === false) {
    $exists = false;
} else {
    $exists = true;
}

// Domain végződés lekérése
$tld = WHDomainPlan::getByTld($tld);
if ($tld) {
    $price = $tld->getPrice(true);
} else {
    $error = 'Nincs ilyen domain-végződés!';
}

echo json_encode([
    'exists' => $exists,
    'error' => $error ?? null,
    'price' => $price ?? null,
    'tld' => $tld->getTld() ?? null,
]);
