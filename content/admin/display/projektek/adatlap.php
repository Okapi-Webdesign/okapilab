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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="display-6">
                <i class="fa-brands fa-wordpress me-2 <?= $project->isWordpress() == false ? 'd-none' : '' ?>"></i> <?= $project->getName() ?>
            </h2>
            <div class="text-muted spinner-border float-end d-none" role="status" id="loadingSpinner">
                <span class="visually-hidden">Betöltés...</span>
            </div>
        </div>
        <p>
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
                                                    echo '-';
                                                }
                                                ?></span>
            <span><b>Státusz:</b> <span id="statusLabel"><?= $project->getStatus()->getName() ?></span></span>
        </p>
        <hr>

        <div class="row">
            <div class="col-4 d-none d-xl-block">
                <img src="<?php
                            if ($project->getImageUri() === NULL) {
                                echo 'https://placehold.co/300x200?text=' . str_replace(' ', '+', $project->getName());
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
                <h3 class="h6">Adatok</h3>
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
                        <b>Aktív</b> <br>
                        <?= $project->isActive() ? '<span class="badge bg-success">Igen</span>' : '<span class="badge bg-danger">Nem</span>' ?>
                    </div>
                    <div class="col-12">
                        <b>Státusz</b> <br>
                        <select id="statusSelect" class="select2">
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
                        <select name="tagsSelect" id="tagsSelect" class="select2-tags" multiple>
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
                        <select name="servicesSelect" id="servicesSelect" class="select2-tags" multiple>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('#websiteScreenshot.webOpener').click(function() {
            window.open('<?= $project->getUrl() ?>', '_blank');
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

                        $('#statusLabel').text($('#statusSelect option:selected').text());
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
    });
</script>