<?php
$pageMeta = [
    'title' => 'Webhoszting',
];
?>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-pills my-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-prices-tab" data-bs-toggle="pill" data-bs-target="#pills-prices" type="button" role="tab" aria-controls="pills-prices" aria-selected="true">Árlista</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-subscriptions" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">Bérlések</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-domains-tab" data-bs-toggle="pill" data-bs-target="#pills-domains" type="button" role="tab" aria-controls="pills-domains" aria-selected="false">Domainek</button>
            </li>
        </ul>
        <hr>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-prices" role="tabpanel" aria-labelledby="pills-prices-tab" tabindex="0">
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
                                    echo '<button type="button" class="btn btn-sm btn-warning" onclick="modal_open(\'webhoszting/domainedit\', {id: ' . $domain->getId() . '})"><i class="fa fa-pencil"></i></button>';
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