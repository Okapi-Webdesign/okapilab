<?php
$pageMeta = [
    'title' => 'Adatlap'
];

$subscription = new WHSubscription($data[0]);
?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-xl-row flex-column justify-content-xl-between justify-content-center">
            <h2 class="display-4 mb-2 text-center">
                #<?= $subscription->getId() ?> <?= $subscription->getPlan()->getName() ?> tárhely
            </h2>
            <div class="text-muted spinner-border float-end d-none" role="status" id="loadingSpinner">
                <span class="visually-hidden">Betöltés...</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-xl-row flex-column">
            <span class="mb-3 mb-xl-0 text-center text-xl-start">
                <span class="me-3 d-inline-block"><b>Ügyfél:</b> <a href="<?= URL ?>admin/ugyfelek/adatlap/d/<?= $subscription->getClient()->getId() ?>" class="text-decoration-none"><?= $subscription->getClient()->getName() ?></a></span>
                <span class="me-3 d-inline-block"><b>Méret:</b> <?= $subscription->getPlan()->getSize(true) ?></span>
                <span class="me-3 d-inline-block"><b>Lejárat:</b> <?= date('Y. m. d.', $subscription->getExpiry()) ?> (<?= $subscription->getBillingPeriod() == 'monthly' ? 'havi' : 'éves' ?>)</span>
            </span>
            <div class="action-buttons">
                <?php
                if ($subscription->isActive()) {
                ?>
                    <button title="Deaktiválás" data-bs-toggle="tooltip" class="btn btn-sm btn-secondary" onclick="window.location.href='<?= URL ?>admin/process/webhosting/toggleSubscription/d/<?= $subscription->getId() ?>'"><i class="fa fa-ban"></i></button>
                    <button title="Törlés" data-bs-toggle="tooltip" class="btn btn-sm btn-danger" onclick="modal_open('webhoszting/tarhelytorles', {id: <?= $subscription->getId() ?>})"><i class="fa fa-trash"></i></button>
                <?php } else { ?>
                    <button title="Aktiválás" data-bs-toggle="tooltip" class="btn btn-sm btn-success" onclick="window.location.href='<?= URL ?>admin/process/webhosting/toggleSubscription/d/<?= $subscription->getId() ?>'"><i class="fa fa-refresh"></i></button>
                    <button title="Törlés" data-bs-toggle="tooltip" class="btn btn-sm btn-danger" onclick="modal_open('webhoszting/tarhelytorles', {id: <?= $subscription->getId() ?>})"><i class="fa fa-trash"></i></button>
                <?php } ?>
            </div>
        </div>
        <hr>
        <div class="progress" role="progressbar" aria-valuenow="<?= time() ?>" aria-valuemin="<?= strtotime($subscription->getLastRenewal()) ?>" aria-valuemax="<?= $subscription->getExpiry() ?>">
            <?php
            $p = 0;
            $max = $subscription->getExpiry();
            $min = strtotime($subscription->getLastRenewal());
            $p = round((time() - $min) / ($max - $min) * 100, 2);
            if ($p > 100) {
                $p = 100;
            } elseif ($p < 0) {
                $p = 0;
            }
            ?>
            <div class="progress-bar <?= $p == 100 ? 'bg-danger' : '' ?>" style="width:<?= $p ?>%;"></div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-12 col-md-3">
                <h3 class="h6">Projekt(ek)</h3>
                <?php
                $projects = $subscription->getProjects();
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
                        <b>Tárhelycsomag</b> <br>
                        <span>
                            <?= $subscription->getPlan()->getName() ?> csomag <br>
                            <span class="text-muted"><?= $subscription->getPlan()->getSize(true) ?>
                                <br> <?= $subscription->getBillingPeriod() == 'monthly' ? $subscription->getPlan()->getMonthlyPrice(true) . ' / hó' : $subscription->getPlan()->getYearlyPrice(true) . ' / hó' ?></span>
                        </span>
                        </span>
                    </div>

                    <div class="col">
                        <b>Létrehozás dátuma</b> <br>
                        <span>
                            <?= date('Y. m. d. H:i', strtotime($subscription->getCreateDate())) ?>
                        </span>
                    </div>

                    <div class="col">
                        <b>Utolsó megújítás</b> <br>
                        <span>
                            <?= date('Y. m. d. H:i', strtotime($subscription->getLastRenewal())) ?>
                        </span>
                    </div>
                </div>

                <div class="g-3">
                    <button onclick="modal_open('webhoszting/tarhelyszamla', {id: <?= $subscription->getId() ?>})" class="btn btn-primary"><i class="fa fa-refresh me-2"></i> Megújítás</button>
                    <a href=" <?= URL ?>admin/process/webhosting/toggleSubscriptionBP/d/<?= $subscription->getId() ?>" class="btn btn-secondary"><i class="fa fa-shuffle me-2"></i> Váltás <?= $subscription->getBillingPeriod() == 'monthly' ? 'éves' : 'havi' ?> számlázásra</a>
                </div>
            </div>
        </div>
    </div>
</div>