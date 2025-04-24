<?php
$pageMeta = [
    'title' => 'Webhoszting',
    'packages' => ['datatables', 'select2']
];
?>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-subscriptions" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">Bérlések</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-domains-tab" data-bs-toggle="pill" data-bs-target="#pills-domains" type="button" role="tab" aria-controls="pills-domains" aria-selected="false">Domainek</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-prices-tab" data-bs-toggle="pill" data-bs-target="#pills-prices" type="button" role="tab" aria-controls="pills-prices" aria-selected="true">Árlista</button>
            </li>
        </ul>
        <hr>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-subscriptions" role="tabpanel" aria-labelledby="pills-subscriptions-tab" tabindex="0">
                <div class="table-responsive">
                    <table id="subsTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ügyfél</th>
                                <th>Csomag</th>
                                <th>Fizetési időszak</th>
                                <th>Lejárat</th>
                                <th>Státusz</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subscriptions = WHSubscription::getAll();
                            foreach ($subscriptions as $sub) {
                                $p = $sub->getBillingPeriod() == 'monthly' ? 'Havi' : 'Éves';
                                echo '<tr data-id="' . $sub->getId() . '" class="cursor-pointer" onclick="window.location.href=\'' . URL . 'admin/webhoszting/adatlap/d/' . $sub->getId() . '\'">';
                                echo '<td>' . $sub->getId() . '</td>';
                                echo '<td><a href="' . URL . 'admin/ugyfelek/adatlap/d/' . $sub->getClient()->getId() . '">' . $sub->getClient()->getName() . '</a></td>';
                                echo '<td>' . $sub->getPlan()->getName() . ' (' . $sub->getPlan()->getSize(false) / 1000 . ' GB)</td>';
                                echo '<td>' . $p . '</td>';
                                if ($sub->getExpiry() > time()) echo '<td>' . date('Y. m. d', $sub->getExpiry()) . '</td>';
                                else echo '<td class="text-danger">' . date('Y. m. d.', $sub->getExpiry()) . '</td>';
                                echo '<td>' . ($sub->isActive() ? 'Aktív' : 'Inaktív') . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-domains" role="tabpanel" aria-labelledby="pills-domains-tab" tabindex="0">
                <div class="table-responsive">
                    <table id="domainsTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ügyfél</th>
                                <th>Domain</th>
                                <th>Lejárat</th>
                                <th>Státusz</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $domains = WHDomain::getAll();
                            foreach ($domains as $domain) {
                                echo '<tr>';
                                echo '<td>' . $domain->getId() . '</td>';
                                echo '<td><a href="' . URL . 'admin/ugyfelek/adatlap/d/' . $domain->getClient()->getId() . '">' . $domain->getClient()->getName() . '</a></td>';
                                echo '<td>' . $domain->getDomain() . '</td>';
                                if ($domain->getExpiry() > time()) echo '<td>' . date('Y. m. d', $domain->getExpiry()) . '</td>';
                                else echo '<td class="text-danger">' . date('Y. m. d.', $domain->getExpiry()) . '</td>';
                                echo '<td>' . ($domain->isActive() ? 'Aktív' : 'Inaktív') . '</td>';
                                echo '<td class="text-end action-buttons">';
                                if ($domain->getExpiry() > strtotime('+1 month')) {
                                    echo '<button type="button" class="btn btn-sm btn-secondary" onclick="modal_open(\'webhoszting/domainszamla\', {id: ' . $domain->getId() . '})"><i class="fa fa-money"></i></button>';
                                } else {
                                    echo '<button type="button" class="btn btn-sm btn-primary" onclick="modal_open(\'webhoszting/domainszamla\', {id: ' . $domain->getId() . '})"><i class="fa fa-money"></i></button>';
                                }
                                echo '<button type="button" class="btn btn-sm btn-danger" onclick="modal_open(\'webhoszting/domainregtorles\', {id: ' . $domain->getId() . '})"><i class="fa fa-trash"></i></button>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-prices" role="tabpanel" aria-labelledby="pills-prices-tab" tabindex="0">
                <div class="align-items-center mb-3 d-flex justify-content-between">
                    <h3 class="mb-0">Webtárhely</h3>
                    <button type="button" class="btn btn-primary" onclick="modal_open('webhoszting/ujcsomag')">Új csomag</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col">Név</th>
                                <th scope="col">Méret</th>
                                <th scope="col">Ár</th>
                                <th scope="col">Profit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $plans = WHPlan::getAll();

                            if (0 == count($plans)) {
                                echo '<tr><td colspan="4" class="text-center">Nincs elérhető webtárhely csomag!</td></tr>';
                            } else {
                                foreach ($plans as $plan) {
                                    echo '<tr>';
                                    echo '<td>' . $plan->getName() . '</td>';
                                    echo '<td>' . $plan->getSize(true) . '</td>';
                                    echo '<td>' . $plan->getMonthlyPrice(true) . ' / hó</td>';
                                    echo '<td>' . number_format($plan->getMonthlyPrice(false) - $plan->getCost(false), 0, '', ' ') . ' Ft / hó</td>';
                                    echo '<td class="text-end action-buttons">';
                                    echo '<button type="button" class="btn btn-sm btn-warning" onclick="modal_open(\'webhoszting/csomagszerkesztes\', {id: ' . $plan->getId() . '})"><i class="fa fa-pencil"></i></button>';
                                    echo '<button type="button" class="btn btn-sm btn-danger" onclick="modal_open(\'webhoszting/csomagtorles\', {id: ' . $plan->getId() . '})"><i class="fa fa-trash"></i></button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="align-items-center mt-5 mb-3 d-flex justify-content-between">
                    <h3 class="mb-0">Domain</h3>
                    <button type="button" class="btn btn-primary" onclick="modal_open('webhoszting/ujdomain')">Új végződés</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Végződés</th>
                                <th>Éves díj</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $domains = WHDomainPlan::getAll();

                            if (0 == count($domains)) {
                                echo '<tr><td colspan="3" class="text-center">Nincs elérhető domain végződés!</td></tr>';
                            } else {
                                foreach ($domains as $domain) {
                                    echo '<tr>';
                                    echo '<td>.' . $domain->getTld() . '</td>';
                                    echo '<td>' . $domain->getPrice(true) . ' / év</td>';
                                    echo '<td class="text-end action-buttons">';
                                    echo '<button type="button" class="btn btn-sm btn-warning" onclick="modal_open(\'webhoszting/domainszerkesztes\', {id: ' . $domain->getId() . '})"><i class="fa fa-pencil"></i></button>';
                                    echo '<button type="button" class="btn btn-sm btn-danger" onclick="modal_open(\'webhoszting/domaintorles\', {id: ' . $domain->getId() . '})"><i class="fa fa-trash"></i></button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('#subsTable').DataTable({
            language: {
                "sEmptyTable": "Nincs rendelkezésre álló adat",
                "sInfo": "Megjelenítve: _START_ - _END_ Összesen: _TOTAL_",
                "sInfoEmpty": "Nincs találat",
                "sInfoFiltered": "(_MAX_ összes rekord közül szűrve)",
                "sInfoPostFix": "",
                "sInfoThousands": " ",
                "sLengthMenu": "_MENU_ rekord oldalanként",
                "sLoadingRecords": "Betöltés...",
                "sProcessing": "Feldolgozás...",
                "sSearch": "Keresés:",
                "sZeroRecords": "Nincs a keresésnek megfelelő találat",
            },
            columnDefs: [{
                orderable: false,
                targets: [0]
            }, ],
            order: [1, 'asc'],
            layout: {
                topStart: {
                    buttons: [{
                        text: 'Létrehozás',
                        action: function() {
                            modal_open('webhoszting/ujtarhely');
                        },
                        className: 'btn-primary',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                    }, ]
                },
            }
        });

        $('#domainsTable').DataTable({
            language: {
                "sEmptyTable": "Nincs rendelkezésre álló adat",
                "sInfo": "Megjelenítve: _START_ - _END_ Összesen: _TOTAL_",
                "sInfoEmpty": "Nincs találat",
                "sInfoFiltered": "(_MAX_ összes rekord közül szűrve)",
                "sInfoPostFix": "",
                "sInfoThousands": " ",
                "sLengthMenu": "_MENU_ rekord oldalanként",
                "sLoadingRecords": "Betöltés...",
                "sProcessing": "Feldolgozás...",
                "sSearch": "Keresés:",
                "sZeroRecords": "Nincs a keresésnek megfelelő találat",
            },
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }, ],
            order: [1, 'asc'],
            layout: {
                topStart: {
                    buttons: [{
                        text: 'Létrehozás',
                        action: function() {
                            modal_open('webhoszting/ujdomainreg');
                        },
                        className: 'btn-primary',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                    }, ]
                },
            }
        });
    });
</script>