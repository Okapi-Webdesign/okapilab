<?php
$client = $_POST['client'] ?? null;
$domain = $_POST['domain'] ?? null;

$tld = array_reverse(explode('.', $domain))[0];
$tld = WHDomainPlan::getByTld($tld);
$tld_id = $tld->getId();

if ($stmt = $con->prepare('INSERT INTO `wh_domains`(`id`, `client`, `domain`, `tld`, `create_date`, `last_renewal`, `status`) VALUES (null, ?, ?, ?, now(), now(), 1)')) {
    $stmt->bind_param('isi', $client, $domain, $tld_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        alert_redirect('success', URL . 'admin/webhoszting');
    } else {
        alert_redirect('error', URL . 'admin/webhoszting');
    }
    $stmt->close();
} else {
    alert_redirect('error', URL . 'admin/webhoszting');
}
