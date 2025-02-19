<?php
$pageMeta = [
    'title' => 'Projekt adatlap',
    'packages' => ['select2']
];

$project = new Project($data[0]);
$client = $project->getClient();
?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="display-4">
                <i class="fa-brands fa-wordpress me-1 <?= $project->isWordpress() == false ? 'd-none' : '' ?>"></i> <?= $project->getName() ?>
            </h2>
            <div class="text-muted spinner-border float-end d-none" role="status" id="loadingSpinner">
                <span class="visually-hidden">Betöltés...</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span>
                <span class="me-3"><b>Ügyfél:</b> <a href="<?= URL ?>admin/ugyfelek/adatlap/d/<?= $project->getClient()->getId() ?>" class="text-decoration-none"><?= $project->getClient()->getName() ?></a></span>
                <span class="me-3"><b>Weboldal:</b> <?php
                                                    $displayUrl = $project->getUrl();
                                                    if ($displayUrl !== NULL) {
                                                        $displayUrl = str_replace('http://', '', $displayUrl);
                                                        $displayUrl = str_replace('https://', '', $displayUrl);
                                                        // URL végéről / eltávolítása
                                                        if (substr($displayUrl, -1) == '/') {
                                                            $displayUrl = substr($displayUrl, 0, -1);
                                                        }
                                                        echo '<a href="' . $displayUrl . '" target="_blank" class="text-decoration-none">' . $displayUrl . '</a>';
                                                    } else {
                                                        echo 'Nincs megadva.';
                                                    }
                                                    ?></span>
                <?php if ($project->isActive()) { ?> <span class="me-3"><b>Határidő:</b> <?= $project->getDeadline() == NULL ? 'Nincs megadva.' : $project->getDeadline() ?></span> <?php } else { ?> <span class="me-3"><b>Garancia határideje:</b> <?= $project->getWarranty() == NULL ? 'Nincs megadva.' : $project->getWarranty() ?></span> <?php } ?>
            </span>
            <div class="action-buttons">
                <?php
                if ($project->isActive()) {
                ?>
                    <button class="btn btn-sm btn-warning" onclick="modal_open('projektek/szerkeszt', {id: <?= $project->getId() ?>})"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-sm btn-secondary" onclick="modal_open('projektek/archival', {id: <?= $project->getId() ?>})"><i class="fa fa-archive"></i></button>
                <?php } else { ?>
                    <button class="btn btn-sm btn-danger" onclick="modal_open('projektek/torol', {id: <?= $project->getId() ?>})"><i class="fa fa-trash"></i></button>
                <?php } ?>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-4 d-none d-xl-block">
                <img src="<?php
                            if ($project->getImageUri() === NULL) {
                                echo 'https://placehold.co/300x200/FF9E00/FEFEFE?font=raleway&text=' . str_replace(' ', '+', $project->getName());
                            } else {
                                echo $project->getImageUri();
                            }
                            ?>" class="w-100 rounded shadow-sm <?= $project->getUrl() == NULL ? '' : 'webOpener" style="cursor:pointer;' ?>" id="websiteScreenshot">
                <?php
                // access key
                $access_key = '82a2812f2d0143f7b0b6d8298a25f965';
                $file_content = file_get_contents('https://api.apiflash.com/v1/urltoimage/quota?access_key=82a2812f2d0143f7b0b6d8298a25f965');

                // convert to array
                $json = json_decode($file_content, true);
                if ($project->getUrl() !== NULL && $json['remaining'] > 5) {
                ?>
                    <p class="text-center mt-2 mb-0">
                        <small>
                            <a href="<?= URL ?>admin/process/projects/regenerateScreenshot/d/<?= $project->getId() ?>" title="<?= $json['remaining'] ?> lehetőség maradt a hónapban." data-bs-toggle="tooltip" class="text-decoration-none text-small" id="websiteScreenshotRegenerate"><i class="fa fa-rotate me-1"></i> Kép újragenerálása</a>
                        </small>
                    </p>
                <?php
                }
                ?>
            </div>
            <div class="col-12 col-xl-8">
                <h3 class="h4">Adatok</h3>
                <div class="row mb-3 g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <b>Ügyfél</b> <br>
                        <?= $client->getName() ?> <br>
                        <a href="mailto:<?= $client->getEmail() ?>" class="text-decoration-none"><?= $client->getEmail() ?></a> <br>
                        <a href="tel:<?= $client->getPhone() ?>" class="text-decoration-none"><?= $client->getPhone(true) ?></a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <b>Weboldal</b> <br>
                        <?php
                        if ($project->getUrl() !== NULL) {
                        ?><a href="<?= $project->getUrl() ?>" target="_blank" class="text-decoration-none"><?= $displayUrl ?></a> <br>
                        <?php } else {
                            echo 'Nincs megadva.';
                        } ?>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <b>Státusz</b> <br>
                        <?= $project->isActive() ? '<span class="badge bg-success"><i class="fa fa-check me-1"></i> Aktív</span>' : '<span class="badge bg-secondary"><i class="fa fa-archive me-1"></i> Archív</span>' ?>
                    </div>
                    <div class="col-12">
                        <b>Státusz</b> <br>
                        <select id="statusSelect" class="select2" <?= $project->isActive() ? '' : 'disabled' ?>>
                            <?php
                            $statuses = Status::getAll();
                            foreach ($statuses as $status) {
                                echo '<option value="' . $status->getId() . '" ' . ($status->getId() == $project->getStatus()->getId() ? 'selected' : '') . '>' . $status->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <b>Címkék</b>
                        <select name="tagsSelect" id="tagsSelect" class="select2-tags" multiple <?= $project->isActive() ? '' : 'disabled' ?>>
                            <?php
                            if ($stmt = $con->prepare('SELECT `id`, `name` FROM `projects_tags` ORDER BY `name` ASC')) {
                                $stmt->execute();
                                $stmt->store_result();
                                $stmt->bind_result($id, $name);
                                $current_tags = $project->getTags();
                                if ($current_tags == NULL) {
                                    $current_tags = [];
                                }
                                while ($stmt->fetch()) {
                                    echo '<option value="' . $id . '" ' . (in_array($id, $current_tags) ? 'selected' : '') . '>' . $name . '</option>';
                                }
                                $stmt->close();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <b>Szolgáltatás</b>
                        <select name="servicesSelect" id="servicesSelect" class="select2-tags" multiple <?= $project->isActive() ? '' : 'disabled' ?>>
                            <?php
                            $services = [
                                'Arculattervezés',
                                'Webdesign',
                                'Webfejlesztés',
                                'Webáruház',
                                'Egyedi fejlesztés'
                            ];

                            $project_services = $project->getServices();
                            if ($project_services == NULL) {
                                $project_services = [];
                            }

                            foreach ($services as $service) {
                                echo '<option value="' . $service . '" ' . (in_array($service, $project_services) ? 'selected' : '') . '>' . $service . '</option>';
                                if (in_array($service, $project_services)) {
                                    $key = array_search($service, $project_services);
                                    unset($project_services[$key]);
                                }
                            }

                            foreach ($project_services as $service) {
                                echo '<option value="' . $service . '" selected>' . $service . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <b>Megjegyzés</b> <br>
                        <textarea id="comment" class="form-control" <?= $project->isActive() ? '' : 'disabled' ?>><?= $project->getComment() ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <h3 class="h4">
            Bejelentkezési adatok
        </h3>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('#websiteScreenshot.webOpener').click(function() {
            window.open('<?= $project->getUrl() ?>', '_blank');
        });

        $("#websiteScreenshotRegenerate").click(function() {
            loader_start();
        });

        $('#statusSelect').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateStatus.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    status: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });

                        $('#statusSelect').val('<?= $project->getStatus()->getId() ?>');
                    }
                }
            });
        });

        $('#tagsSelect').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateTags.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    tags: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });
                    }
                }
            });
        });

        $('#servicesSelect').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateServices.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    services: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });
                    }
                }
            });
        });

        $('#comment').change(function() {
            $('#loadingSpinner').removeClass('d-none');

            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/projects/updateComment.php',
                type: 'POST',
                data: {
                    project: '<?= $project->getId() ?>',
                    comment: $(this).val()
                },
                success: function(response) {
                    $('#loadingSpinner').addClass('d-none');
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            text: response
                        });
                    }
                }
            });
        });
    });
</script>