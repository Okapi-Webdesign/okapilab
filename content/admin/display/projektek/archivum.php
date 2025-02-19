<?php
$pageMeta = [
    'title' => 'Archív projektek',
    'packages' => ['datatables'],
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
                        <th>Garancia lejárata</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $projects = Project::getArchive();

                    foreach ($projects as $project) {
                        $warranty = $project->getWarranty();
                        $warranty = $warranty == NULL ? '-' : $warranty;
                        echo '<tr>';
                        echo '<td>' . $project->getId() . '</td>';
                        echo '<td>' . $project->getName() . '</td>';
                        echo '<td>' . $project->getClient()->getName() . '</td>';
                        echo '<td>' . $warranty . '</td>';
                        echo '<td><a href="' . URL . 'admin/projektek/adatlap/d/' . $project->getId() . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a></td>';
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
                    targets: [4]
                }
            ],
            order: [2, 'asc'],
            layout: {
                topStart: {
                    buttons: [{
                        text: 'Aktív projektek',
                        className: 'btn-primary',
                        action: function() {
                            window.location.href = '<?= URL ?>admin/projektek';
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                    }]
                },
            }
        });
    });
</script>