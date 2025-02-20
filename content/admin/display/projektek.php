<?php
$pageMeta = [
    'title' => 'Projektek',
    'packages' => ['datatables', 'select2'],
];
?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Név</th>
                        <th>Ügyfél</th>
                        <th>Weboldal</th>
                        <th>Státusz</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $projects = Project::getAll();

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
                        echo '<tr>';
                        echo '<td>' . $project->getId() . '</td>';
                        echo '<td>' . $project->getName() . '</td>';
                        echo '<td>' . $project->getClient()->getName() . '</td>';
                        if ($project->getUrl() == NULL) echo '<td>' . $displayURL . '</td>';
                        else echo '<td><a href="' . $project->getUrl() . '" target="_blank">' . $displayURL . '</a></td>';
                        echo '<td data-sort="' . $project->getStatus()->getId() . '">' . $project->getStatus()->print() . '</td>';
                        echo '<td><a href="projektek/adatlap/d/' . $project->getId() . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a></td>';
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
                },
                {
                    className: 'text-end',
                    orderable: false,
                    targets: [5]
                },
            ],
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
    });
</script>