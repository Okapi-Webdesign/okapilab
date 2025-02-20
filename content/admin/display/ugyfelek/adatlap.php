<?php
$pageMeta = [
    'title' => 'Ügyfél adatlap',
    'breadcrumbs' => [['ugyfelek', 'adatlap'], ['Ügyfelek', 'Adatlap']],
];

$client = new Client($data[0]);
?>

<div class="card">
    <div class="card-body">
        <h2 class="display-4 mb-2 text-center text-xl-start">
            <?= $client->getName() ?>
        </h2>
        <div class="d-flex justify-content-between align-items-center flex-xl-row flex-column">
            <span class="text-center text-xl-start">
                <span class="me-3 d-inline-block"><b>E-mail cím:</b> <a class="text-decoration-none" href="mailto:<?= $client->getEmail() ?>"><?= $client->getEmail() ?></a></span>
                <span class="me-3 d-inline-block"><b>Telefonszám:</b> <a class="text-decoration-none" href="tel:<?= $client->getPhone() ?>"><?= $client->getPhone(true) ?></a></span>
                <span class="d-inline-block"><b>Kapcsolattartó:</b> <?= $client->getContactName() ?></span>
            </span>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 col-md-3">
                <h3 class="h6">Projekt(ek)</h3>
                <?php
                $projects = $client->getAllProjects();
                if (count($projects) > 0) {
                    echo '<div class="list-group">';
                    foreach ($projects as $project) {
                        $archiveicon = !$project->isActive() ? '<i class="fa fa-archive me-1"></i> ' : '';
                        $mutedclass = !$project->isActive() ? 'text-muted' : '';
                        echo '<a href="' . URL . 'admin/projektek/adatlap/d/' . $project->getId() . '" class="' . $mutedclass . ' list-group-item list-group-item-action">' . $archiveicon . $project->getName() . '</a>';
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
                        </span> <?= empty($client->getCompanyType()) ? '' : '(' . $client->getCompanyType() . ')' ?>
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