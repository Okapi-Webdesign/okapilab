<?php
$pageMeta = ['title' => 'Trello táblakezelés'];

$trello = new TrelloTable();
?>

<a target="_blank" href="<?= $trello->getBoardData()['url'] ?>">
    <button class="btn btn-primary">
        <i class="fa-solid fa-up-right-from-square me-2"></i> Trello tábla megnyitása
    </button>
</a>

<div class="card mt-3">
    <div class="card-body">
        <h3 class="h4 mb-3">Következő feladatok</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Feladat</th>
                        <th>Projekt</th>
                        <th>Határidő</th>
                        <th>Státusz</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cards = [];
                    $cards[] = $trello->getUserCards($user->getTrelloId(), 5, '1️⃣  Magas prioritás');
                    $cards[] = $trello->getUserCards($user->getTrelloId(), 5, '2️⃣ Teendők');
                    $cards[] = $trello->getUserCards($user->getTrelloId(), 5, '3️⃣ Hosszútávú feladat');
                    foreach ($cards as $card) {
                        $status = $trello->getList($card['idList'])['name'];
                        switch ($status) {
                            case '1️⃣  Magas prioritás':
                                $statusBadge = '<span class="badge text-bg-danger"><i class="fa fa-exclamation-triangle me-2"></i>Fontos</span>';
                                break;
                            case '2️⃣ Teendők':
                                $statusBadge = '<span class="badge text-bg-warning"><i class="fa fa-exclamation-triangle me-2"></i>Teendő</span>';
                                break;
                            case '3️⃣ Hosszútávú feladat':
                                $statusBadge = '<span class="badge text-bg-secondary"><i class="fa fa-clock me-2"></i>Halasztható</span>';
                                break;
                            case '✅ Befejezett':
                                $statusBadge = '<span class="badge text-bg-success"><i class="fa fa-check me-2"></i>Kész</span>';
                                break;
                            case '⚠️ Felülvizsgálat':
                                $statusBadge = '<span class="badge text-bg-primary"><i class="fa fa-eye me-2"></i>Visszajelzés / Felülvizsgálat</span>';
                                break;
                            default:
                                $statusBadge = '<span class="badge text-bg-secondary">' . $status . '</span>';
                                break;
                        }

                        $projects = [];

                        foreach ($card['labels'] as $label) {
                            if ($trello->getProject($label['id'])) {
                                $projects[] = $trello->getProject($label['id']);
                            }
                        }

                        $projectNames = [];
                        foreach ($projects as $project) {
                            $projectNames[] = $project->getName();
                        }

                        echo '<tr style="cursor:pointer;" onclick="window.open(\'https://trello.com/c/' . $card['shortLink'] . '\', \'_blank\')">';
                        echo '<td>' . $card['idShort'] . '</td>';
                        echo '<td>' . $card['name'] . '</td>';
                        echo '<td>' . implode(', ', $projectNames) . '</td>';
                        echo '<td>' . (!empty($card['due']) ? date('Y. m. d. H:i', strtotime($card['due'])) : '') . '</td>';
                        echo '<td>' . $statusBadge . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <hr>
        <h3 class="h4 mb-3">Projektek</h3>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Projekt</th>
                        <th>Címke</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $projects = Project::getAll();
                    $labels = $trello->getLabels();
                    // azon listaelemek törlése, amelyeknek neve üres
                    $labels = array_filter($labels, function ($label) {
                        return !empty($label['name']);
                    });

                    foreach ($projects as $project) {
                        echo '<tr>';
                        echo '<td>' . $project->getName() . '</td>';
                        echo '<td>';
                        echo '<select class="form-select project_trello" data-project-id="' . $project->getId() . '">';
                        echo '<option value="" selected>Válasszon...</option>';
                        foreach ($labels as $label) {
                            echo '<option ' . ($project->getTrelloId() == $label['id'] ? 'selected' : '') . ' value="' . $label['id'] . '">' . $label['name'] . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('document').ready(function() {

        $('.project_trello').change(function() {
            $.ajax({
                url: '<?= URL ?>assets/ajax/admin/trello/changeProject.php',
                type: 'POST',
                data: {
                    trello_id: $(this).val(),
                    project_id: $(this).data('project-id')
                },
                success: function(response) {
                    if (response == 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Sikeres művelet!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hiba történt!',
                            html: response
                        });
                    }
                }
            });
        });
    });
</script>