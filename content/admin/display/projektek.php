<?php
$pageMeta = [
    'title' => 'Projektek',
    'packages' => ['datatables', 'select2'],
];
?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="projectTable table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Név</th>
                        <th>Ügyfél</th>
                        <th>Weboldal</th>
                        <th>Státusz</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $projects = Project::getAll();
                    $trello = new TrelloTable();

                    foreach ($projects as $project) {
                        $displayURL = $project->getURL();
                        if ($displayURL == NULL) {
                            $displayURL = 'Nincs megadva';
                        } else {
                            $displayURL = str_replace('https://', '', $displayURL);
                            $displayURL = str_replace('http://', '', $displayURL);
                            if (substr($displayURL, -1) == '/') {
                                $displayURL = substr($displayURL, 0, -1);
                            }
                        }
                        echo '<tr data-id="' . $project->getId() . '">';
                        echo '<td>' . $project->getId() . '</td>';
                        echo '<td>' . $project->getName();
                        if ($project->getTrelloId() != false) {
                            $card = $trello->getProjectCards($project, 1, ['Folyamatban', 'Teendő'], true);
                            if (!empty($card)) {
                                $card = $card[0];
                                $arrow = '<i class="fa fa-chevron-right text-primary me-2"></i>';
                                $border = 'primary';
                                if ($trello->getList($card['idList'])['name'] == 'Folyamatban') {
                                    $arrow = '<i class="fa fa-chevron-right text-warning me-2"></i>';
                                    $border = 'warning';
                                }
                                if ($card['due'] != NULL) {
                                    $due = strtotime($card['due']);
                                    if ($due < time()) {
                                        $arrow = '<i class="fa fa-angles-right text-danger me-2"></i>';
                                        $border = 'danger';
                                    }
                                }
                                echo '<br>';
                                echo '<div data-card-id="' . $card['id'] . '" class="shadow-sm mt-1 projectTaskLabel bg-white border border-' . $border . ' rounded p-2 d-flex align-items-center gap-2"><span>' . $arrow . '</span><div class="lh-1">' . $card['name'];
                                if ($card['due'] != NULL) {
                                    echo '<br><small class="text-muted">' . date('Y. m. d', strtotime($card['due'])) . '</small>';
                                }
                                echo '</div></div>';
                            }
                        }
                        echo '</td>';
                        echo '<td>' . $project->getClient()->getName() . '</td>';
                        if ($project->getUrl() == NULL) echo '<td>' . $displayURL . '</td>';
                        else echo '<td><a href="' . $project->getUrl() . '" target="_blank">' . $displayURL . '</a></td>';
                        echo '<td data-sort="' . $project->getStatus()->getId() . '">' . $project->getStatus()->print() . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
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
            order: [2, 'asc'],
            layout: {
                topStart: {
                    buttons: [{
                            text: 'Új projekt',
                            className: 'btn-primary',
                            action: function() {
                                modal_open('projektek/uj');
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary')
                            },
                        },
                        {
                            text: 'Archív projektek',
                            className: 'btn-secondary',
                            action: function() {
                                window.location.href = 'projektek/archivum';
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-primary')
                            },
                        }
                    ]
                },
            }
        });

        $('.projectTable tbody tr td').click(function() {
            loader_start();
            var id = $(this).closest('tr').data('id');
            window.location.href = '<?= URL ?>admin/projektek/adatlap/d/' + id
        }).children().click(function(e) {
            if ($(this).hasClass('projectTaskLabel')) {
                e.stopPropagation();
                var id = $(this).data('card-id');
                window.open('https://trello.com/c/' + id, '_blank');
            }

            return false;
        });
    });
</script>