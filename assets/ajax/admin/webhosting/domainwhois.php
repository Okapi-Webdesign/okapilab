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

function whoisQuery($domain)
{
    $apiKey = DOMAINWHOIS_API_KEY; // Cseréld ki a saját API kulcsodra
    $url = 'https://www.whoisxmlapi.com/whoisserver/WhoisService?' . http_build_query([
        'apiKey' => $apiKey,
        'domainName' => $domain,
        'outputFormat' => 'JSON'
    ]);

    $response = file_get_contents($url);
    if ($response === false) {
        return false;
    }

    return json_decode($response, true);
}

function isRegistered($whoisData)
{
    return isset($whoisData['WhoisRecord']) && $whoisData['WhoisRecord']['dataError'] !== 'MISSING_WHOIS_DATA';
}

function getDnsRecords($domain)
{
    return dns_get_record($domain, DNS_ALL);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['domain'])) {
    $domain = trim($_POST['domain']);

    $whois = whoisQuery($domain);

    if ($whois === false) {
        echo "<p><strong>Hiba történt a WHOIS lekérdezés során.</strong></p>";
        exit;
    }

    $registered = isRegistered($whois);

    $maindomain = $domain;
    if (strpos($domain, '.') !== false) {
        $parts = explode('.', $domain);
        $maindomain = implode('.', array_slice($parts, -2));
    }

    $nevelo = 'A';
    $msh = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y'];
    if (!in_array(mb_strtolower(substr($maindomain, 0, 1)), $msh)) {
        $nevelo = 'Az';
    }

    if ($registered) {
        echo "<h3 class='text-danger mb-3'><i class='fa fa-times-circle me-2'></i>$nevelo <a target='_blank' class='text-decoration-none text-danger' href='https://" . htmlspecialchars($maindomain) . "'><strong>" . htmlspecialchars($maindomain) . "</strong></a> domain foglalt!</h3>
        <p>Lekérdezés időpontja: <strong>" . $whois['WhoisRecord']['audit']['updatedDate'] . "</strong></p>";

        if (isset($whois['WhoisRecord']['registrarName'])) {
            echo "<h4>Regisztrátor adatok</h4>";
            echo "<p><strong>Regisztrátor:</strong> " . htmlspecialchars($whois['WhoisRecord']['registrarName']);
            if (isset($whois['WhoisRecord']['registryData']['registrant'])) echo "<br><strong>Tulajdonos:</strong> " . $whois['WhoisRecord']['registryData']['registrant']['name'];
            if (isset($whois['WhoisRecord']['registryData']['technicalContact'])) echo "<br><strong>Technikai kapcsolattartó:</strong> " . $whois['WhoisRecord']['registryData']['technicalContact']['organization'] . " (<a href='mailto:" . htmlspecialchars($whois['WhoisRecord']['registryData']['technicalContact']['email']) . "'>" . htmlspecialchars($whois['WhoisRecord']['registryData']['technicalContact']['email']) . "</a>)";
            echo "</p>";
        }

        if (isset($whois['WhoisRecord']['nameServers']['hostNames'])) {
            echo "<h4>Névszerverek</h4><ol>";
            foreach ($whois['WhoisRecord']['nameServers']['hostNames'] as $ns) {
                echo "<li>" . htmlspecialchars($ns) . "</li>";
            }
            echo "</ol>";
        }

        $dnsRecords = getDnsRecords($domain);
        $subdomains = ['www', 'mail', 'ftp', 'webmail', 'smtp', 'ns1', 'ns2'];
        foreach ($subdomains as $sub) {
            $full = $sub . '.' . $domain;
            $dns = getDnsRecords($full);
            array_push($dnsRecords, ...$dns);
        }

        echo "<h4>DNS rekordok</h4>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover table-striped'><thead><tr><th>Hoszt</th><th>Típus</th><th>TTL</th><th>Adat</th></tr></thead><tbody>";
        foreach ($dnsRecords as $record) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($record['host']) . "</td>";
            echo "<td>" . htmlspecialchars($record['type']) . "</td>";
            echo "<td>" . htmlspecialchars($record['ttl']) . "</td>";

            $details = '';
            foreach ($record as $key => $value) {
                if (!in_array($key, ['host', 'type', 'ttl'])) {
                    $details .= htmlspecialchars($key) . ': ' . htmlspecialchars((is_array($value) ? json_encode($value) : $value)) . "<br>";
                }
            }

            echo "<td>" . $details . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table></div>";
    } else {
        echo "<h3 class='text-success mb-0'><i class='fa fa-check-circle me-2'></i>$nevelo <strong>" . htmlspecialchars($maindomain) . "</strong> domain szabad!</h3>";
    }
}
