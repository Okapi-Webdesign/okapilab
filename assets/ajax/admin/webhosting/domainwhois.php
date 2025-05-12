<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';
$domain = $_POST['domain'] ?? null;
if (!$domain) {
    echo '<div class="alert alert-danger mb-0">Kérlek, adj meg egy érvényes domain nevet!</div>';
    exit;
}

$domain = strtolower(trim($domain));
if (!preg_match('/^[a-z0-9][a-z0-9\-\.]{1,61}[a-z0-9]\.[a-z]{2,}$/', $domain)) {
    echo '<div class="alert alert-danger mb-0">Kérlek, adj meg egy érvényes domain nevet!</div>';
    exit;
}

$whoisServers = [
    'hu'    => 'whois.nic.hu',
    'com'   => 'whois.verisign-grs.com',
    'net'   => 'whois.verisign-grs.com',
    'org'   => 'whois.pir.org',
    'info'  => 'whois.afilias.net',
    'biz'   => 'whois.neulevel.biz',
    'eu'    => 'whois.eu',
    'co.uk' => 'whois.nic.uk',
    'uk'    => 'whois.nic.uk',
    'de'    => 'whois.denic.de',
    'fr'    => 'whois.nic.fr',
    'it'    => 'whois.nic.it',
    'nl'    => 'whois.domain-registry.nl',
    'cz'    => 'whois.nic.cz',
    'pl'    => 'whois.dns.pl',
    'se'    => 'whois.iis.se',
    'ch'    => 'whois.nic.ch',
    'at'    => 'whois.nic.at',
    'be'    => 'whois.dns.be',
    'ca'    => 'whois.cira.ca',
    'au'    => 'whois.auda.org.au',
    'us'    => 'whois.nic.us',
    'xyz'   => 'whois.nic.xyz',
    'io'    => 'whois.nic.io',
    'app'   => 'whois.nic.google',
    'dev'   => 'whois.nic.google',
    'me'    => 'whois.nic.me',
    'tv'    => 'tvwhois.verisign-grs.com',
];

$toplist = [
    'hu',
    'com',
    'eu',
    'org',
    'info',
    'net'
];

function getTld($domain)
{
    $parts = explode('.', $domain);
    return strtolower(end($parts));
}

function whoisQuery($domain)
{
    global $whoisServers;

    $tld = getTld($domain);
    $server = $whoisServers[$tld] ?? null;

    if (!$server) {
        return false;
    }

    $fp = fsockopen($server, 43, $errno, $errstr, 10);
    if (!$fp) {
        return false;
    }

    fwrite($fp, $domain . "\r\n");
    $response = '';
    while (!feof($fp)) {
        $response .= fgets($fp, 128);
    }
    fclose($fp);
    return $response;
}

function isRegistered($whoisData, $tld)
{
    // Egyszerűbb logika, TLD-nként más lehet a "not found" üzenet
    $notFoundPatterns = [
        'hu'    => 'No match',
        'com'   => 'No match for',
        'net'   => 'No match for',
        'org'   => 'NOT FOUND',
        'info'  => 'NOT FOUND',
        'biz'   => 'Not found',
        'eu'    => 'Status: AVAILABLE',
        'co.uk' => 'No match for',
        'uk'    => 'No match for',
        'de'    => 'Status: free',
        'fr'    => 'No entries found',
        'it'    => 'Status: AVAILABLE',
        'nl'    => 'is free',
        'cz'    => 'no entries found',
        'pl'    => 'No information available',
        'se'    => 'not found',
        'ch'    => 'We do not have an entry',
        'at'    => 'nothing found',
        'be'    => 'Status: AVAILABLE',
        'ca'    => 'Domain status: available',
        'au'    => 'No Data Found',
        'us'    => 'Not found',
        'xyz'   => 'DOMAIN NOT FOUND',
        'io'    => 'is available',
        'app'   => 'Domain not found',
        'dev'   => 'Domain not found',
        'me'    => 'NOT FOUND',
        'tv'    => 'No match for',
    ];

    $pattern = $notFoundPatterns[$tld] ?? 'No match';
    return strpos($whoisData, $pattern) === false;
}

function getNameServers($domain)
{
    return dns_get_record($domain, DNS_NS);
}

function getDnsRecords($domain)
{
    return dns_get_record($domain, DNS_ALL);
}

$tld = getTld($domain);
$whois = whoisQuery($domain);

if ($whois === false) {
    echo "<p><strong>A TLD (.$tld) nem támogatott vagy hiba történt a lekérdezés során.</strong></p>";
} else {
    $registered = isRegistered($whois, $tld);

    $nevelo = 'A';
    $msh = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y'];
    if (!in_array(substr($domain, 0, 1), $msh)) {
        $nevelo = 'Az';
    }

    $maindomain = $domain;
    if (strpos($domain, '.') !== false) {
        $parts = explode('.', $domain);
        $maindomain = implode('.', array_slice($parts, -2));
    }

    if ($registered) {
        echo "<h3 class='text-danger mb-3'><i class='fa fa-times-circle me-2'></i>$nevelo <strong>" . htmlspecialchars($maindomain) . "</strong> domain foglalt!</h3>";

        // Névszerverek
        $nsRecords = getNameServers($domain);
        if (!empty($nsRecords)) {
            echo "<h4>Névszerverek</h4><ol>";
            foreach ($nsRecords as $ns) {
                echo "<li>{$ns['target']}</li>";
            }
            echo "</ol>";
        }

        // DNS rekordok
        $dnsRecords = getDnsRecords($domain);

        $subdomains = ['www', 'mail', 'ftp', 'webmail', 'smtp', 'ns1', 'ns2'];
        foreach ($subdomains as $sub) {
            $full = $sub . '.' . $domain;
            $dns = getDnsRecords($full);
            array_push($dnsRecords, ...$dns);
        }

        echo "<h4>DNS rekordok</h4>";
?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Hoszt</th>
                        <th>Típus</th>
                        <th>TTL</th>
                        <th>Adat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dnsRecords as $record) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($record['host']) . "</td>";
                        echo "<td>" . htmlspecialchars($record['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($record['ttl']) . "</td>";

                        // Összes többi mezőt stringgé konvertálunk, kivéve a már megjelenítetteket
                        $details = '';
                        foreach ($record as $key => $value) {
                            if (!in_array($key, ['host', 'type', 'ttl'])) {
                                $details .= htmlspecialchars($key) . ': ' . htmlspecialchars((is_array($value) ? json_encode($value) : $value)) . "<br>";
                            }
                        }

                        echo "<td>" . $details . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
<?php
    } else {
        echo "<h3 class='text-success mb-3'><i class='fa fa-check-circle me-2'></i>$nevelo <strong>" . htmlspecialchars($maindomain) . "</strong> domain szabad!</h3>";
    }
}
