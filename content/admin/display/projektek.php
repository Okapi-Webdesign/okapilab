<?php
$pageMeta = [
    'title' => 'Projektek',
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
                        <th>Státusz</th>
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
                targets: [0]
            }, ],
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