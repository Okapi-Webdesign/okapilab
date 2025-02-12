<?php
$pageMeta = [
    'title' => 'Ügyfelek',
    'description' => 'Ezen az oldalon az ügyfelek adatait tekintheted meg.',
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
                        <th></th>
                    </tr>
                </thead>
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
                    targets: [0, 3, 4, 5, 6]
                },
                {
                    width: '200px',
                    targets: [3]
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
    });
</script>