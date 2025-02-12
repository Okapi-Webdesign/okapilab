<?php
$pageMeta = [
    'title' => 'Ügyfél adatlap',
    'breadcrumbs' => [['ugyfelek', 'adatlap'], ['Ügyfelek', 'Adatlap']],
];

$client = new Client($data[0]);
?>

<div class="card">
    <div class="card-body">
        <h2 class="display-6">
            <?= $client->getName() ?>
        </h2>
        <p>
            <span class="me-3"><b>E-mail cím:</b> <a href="mailto:<?= $client->getEmail() ?>"><?= $client->getEmail() ?></a></span>
            <span class="me-3"><b>Telefonszám:</b> <a href="tel:<?= $client->getPhone() ?>"><?= $client->getPhone(true) ?></a></span>
            <span><b>Kapcsolattartó:</b> <?= $client->getContactName() ?></span>
        </p>
        <hr>
        <div class="row">
            <div class="col-12 col-md-3">
                <h3 class="h6">Projekt(ek)</h3>
                <?php
                $projects = $client->getAllProjects();
                if (count($projects) > 0) {
                    echo '<div class="list-group">';
                    foreach ($projects as $project) {
                        echo '<a href="' . URL . 'admin/projektek/adatlap/d/' . $project->getId() . '" class="list-group-item list-group-item-action">' . $project->getName() . '</a>';
                    }
                    echo '</div>';
                } else {
                    echo '<p class="text-muted">Nincs megjeleníthető projekt.</p>';
                }
                ?>
            </div>
            <div class="col-12 col-md-9">
                <h3 class="h6">Adatok</h3>
                <div class="row mb-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-3">
                    <div class="col">
                        <b>Ügyféltípus</b> <br>
                        <span class="text-primary text-uppercase">
                            <?= $client->getType() == 1 ? 'Magánszemély' : 'Jogi személy' ?>
                        </span>
                    </div>
                    <div class="col">
                        <b><?= $client->getType() == 1 ? 'Személyiigazolvány-szám' : 'Cégjegyzékszám' ?></b> <br>
                        <?= $client->getRegistrationNumber() ?>
                    </div>
                    <div class="col">
                        <b>Adószám</b> <br>
                        <?= empty($client->getTaxNumber()) ? '<span class="text-muted">Nincs megadva</span>' : $client->getTaxNumber() ?>
                    </div>
                    <div class="col">
                        <b>Számlázási cím</b> <br>
                        <?= $client->getAddress('zip') ?> <?= $client->getAddress('city') ?> <br>
                        <?= $client->getAddress('address') ?> <br> <?= $client->getAddress('address2') ?>
                    </div>
                    <div class="col">
                        <b>Cégforma</b> <br>
                        <?= empty($client->getCompanyType()) ? '<span class="text-muted">Nincs megadva</span>' : $client->getCompanyType() ?>
                    </div>
                    <div class="col">
                        <b>Státusz</b> <br>
                        <?= $client->isActive() ? '<span class="badge bg-success">Aktív</span>' : '<span class="badge bg-secondary">Inaktív</span>' ?>
                    </div>
                    <div class="col">
                        <b>Regisztráció dátuma</b> <br>
                        <?= $client->getCreateDate() ?>
                    </div>
                </div>
                <div class="text-end">
                    <button class="btn btn-secondary" onclick="modal_open('ugyfelek/jelszo', {id: <?= $client->getId() ?>})">Jelszó visszaállítása</button>
                    <button class="btn btn-warning" onclick="modal_open('ugyfelek/szerkeszt', {id: <?= $client->getId() ?>})">Szerkesztés</button>
                    <button class="btn btn-danger" onclick="modal_open('ugyfelek/torles', {id: <?= $client->getId() ?>})">Törlés</button>
                </div>
            </div>
        </div>
    </div>
</div>