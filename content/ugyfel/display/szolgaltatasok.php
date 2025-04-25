<?php
$pageMeta = [
    'title' => 'Szolgáltatások',
    'description' => 'Itt találod az általad megvásárolt webtárhelyeket és domaineket. Csak azok a szolgáltatások jelennek meg, melyeket nálunk vásároltál.',
];

$project = new Project($_SESSION['project']);
$client = $project->getClient();
?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Típus</th>
                        <th>Megnevezés</th>
                        <th>Számlázási ciklus</th>
                        <th>Ár</th>
                        <th>Lejárat</th>
                        <th>Státusz</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $domains = WHDomain::getAllByClient($client->getId());
                    $subscriptions = WHSubscription::getAllByClient($client->getId());

                    if (count($domains) + count($subscriptions) == 0) {
                        echo '<tr><td colspan="6" class="text-center">Nincsenek aktív szolgáltatások.</td></tr>';
                    } else {
                        foreach ($domains as $domain) {
                            $c = time() < $domain->getExpiry() ? 'text-dark' : 'text-danger';
                            echo '<tr>';
                            echo '<td><i class="fa-solid fa-globe me-2"></i> Domain</td>';
                            echo '<td><a href="http://' . $domain->getDomain() . '/" target="_blank">' . $domain->getDomain() . '</a></td>';
                            echo '<td>Éves</td>';
                            echo '<td>' . $domain->getTld()->getPrice(true) . '</td>';
                            echo '<td class="' . $c . '">' . date('Y. m. d.', $domain->getExpiry()) . '</td>';
                            echo '<td>' . ($domain->isActive() ? '<span class="badge bg-success">Aktív</span>' : '<span class="badge bg-danger">Inaktív</span>') . '</td>';
                            echo '</tr>';
                        }
                        foreach ($subscriptions as $subscription) {
                            $c = time() < $subscription->getExpiry() ? 'text-dark' : 'text-danger';
                            echo '<tr>';
                            echo '<td><i class="fa-solid fa-server me-2"></i> Webtárhely</td>';
                            echo '<td>' . $subscription->getPlan()->getName() . ' tárhely (' . $subscription->getPlan()->getSize(true) . ')</td>';
                            echo '<td>' . ($subscription->getBillingPeriod() == 'monthly' ? 'Havi' : 'Éves') . '</td>';
                            echo '<td>' . $subscription->getPrice(true) . '</td>';
                            echo '<td class="' . $c . '">' . date('Y. m. d.', $subscription->getExpiry()) . '</td>';
                            echo '<td>' . ($subscription->isActive() ? '<span class="badge bg-success">Aktív</span>' : '<span class="badge bg-danger">Inaktív</span>') . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <p>
            <b>Mi az a domain?</b> <br>
            A domain a webszerver IP-címének egy könnyen megjegyezhető neve. Ez jelenti a weboldal címét, melyen a látogatók elérhetik a honlapot. A domain név egyedi, és a domain regisztráció során hozzárendelésre kerül a webszerverhez.
            <?php
            if ($project->getUrl() != '') {
                $mydomain = $project->getUrl();
                $mydomain = str_replace('http://', '', $mydomain);
                $mydomain = str_replace('https://', '', $mydomain);
                $mydomain = str_replace('www.', '', $mydomain);
                $mydomain = str_replace('/', '', $mydomain);
                echo 'Például a Te projektednek ez a domain címe: <a href="' . $project->getUrl() . '" target="_blank">' . $mydomain . '</a>';
            }
            ?>
        </p>

        <p>
            <b>Mi az a webtárhely?</b> <br>
            A webtárhely a webszerver által biztosított tárhely, ahol a weboldal fájljai és adatbázisai tárolásra kerülnek - ez teszi lehetővé, hogy a látogatók elérjék a weboldalt az interneten keresztül. Az Okapi Webdesignnál vásárolt webtárhelyek gyorsak és megbízhatóak, valamint a csomagban foglalt menedzsment szolgáltatásunkkal segítünk a weboldal karbantartásában és frissítésében.
        </p>
    </div>
</div>