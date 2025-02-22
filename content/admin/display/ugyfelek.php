<?php
$pageMeta = [
    'title' => 'Ügyfelek',
    'packages' => ['datatables'],
    'role' => 2
];
?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Típus</th>
                        <th>Név</th>
                        <th>Cégjegyzékszám <i class="text-muted ms-1 fa-solid fa-circle-question" data-bs-toggle="tooltip" title="Cégjegyzékszám, nyilvántartási szám vagy személyiigazolvány-szám"></i></th>
                        <th>E-mail cím</th>
                        <th>Telefonszám</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $clients = Client::getAll();

                    foreach ($clients as $client) {
                        echo '<tr data-id="' . $client->getId() . '" class="cursor-pointer">';
                        echo '<td>' . $client->getId() . '</td>';
                        $class = $client->isActive() ? 'text-primary' : 'text-secondary';
                        if ($client->getType() == 1) echo '<td><span title="Magánszemély" data-bs-toggle="tooltip" class="' . $class . ' fw-bold">M</span></td>';
                        else echo '<td><span title="Jogi személy" data-bs-toggle="tooltip" class="' . $class . ' fw-bold">C</span></td>';
                        echo '<td>' . $client->getName() . '</td>';
                        echo '<td>' . $client->getRegistrationNumber() . '</td>';
                        echo '<td><a href="mailto:' . $client->getEmail() . '">' . $client->getEmail() . '</a></td>';
                        echo '<td><a href="tel:' . $client->getPhone() . '">' . $client->getPhone(true) . '</a></td>';
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
                    targets: [0, 3, 4, 5]
                },
                {
                    width: '200px',
                    targets: [3]
                },
                {
                    className: 'text-center',
                    targets: [1]
                }
            ],
            order: [2, 'asc'],
            layout: {
                topStart: {
                    buttons: [{
                        text: 'Új ügyfél',
                        className: 'btn-primary',
                        action: function() {
                            modal_open('ugyfelek/uj');
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                    }, ]
                },
            }
        });

        $('#table').on('click', 'tr', function() {
            var id = $(this).data('id');
            loader_start();
            window.location.href = '<?= URL ?>admin/ugyfelek/adatlap/d/' + id;
        });
    });
</script>