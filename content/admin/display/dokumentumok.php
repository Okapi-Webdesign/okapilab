<?php
$pageMeta = [
    'title' => 'Dokumentumok',
    'packages' => ['datatables', 'select2']
];
?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Projekt</th>
                        <th>Típus</th>
                        <th>Dátum</th>
                        <th>Létrehozó</th>
                        <th>Verziószám</th>
                        <th>Kiterjesztés</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $docs = Document::getAll();

                    foreach ($docs as $doc) {
                        $project = $doc->getProject();
                        $type = $doc->getType();
                        $current = $doc->getCurrent();

                        echo '<tr>';
                        echo '<td>' . $doc->getId() . '</td>';
                        echo '<td><span data-bs-toggle="tooltip" title="' . $project->getClient()->getName() . '">' . $project->getName() . '</span></td>';
                        echo '<td>' . $type->getName() . '</td>';
                        echo '<td>' . $current->getDate(true) . '</td>';
                        echo '<td>' . $current->getUser()->getFullName() . '</td>';
                        echo '<td>' . $current->getVersion() . '. verzió</td>';
                        echo '<td>' . pathinfo($current->getFilename(), PATHINFO_EXTENSION) . '</td>';
                        echo '<td><a href="' . URL . 'admin/dokumentumok/adatlap/d/' . $doc->getId() . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a></td>';
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
                    targets: [0, 5, 6]
                },
                {
                    className: 'text-end',
                    orderable: false,
                    targets: [7]
                },
            ],
            order: [2, 'asc'],
            layout: {
                topStart: {
                    buttons: [{
                        text: 'Létrehozás',
                        action: function() {
                            modal_open('dokumentumok/letrehozas');
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