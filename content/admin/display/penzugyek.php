<?php
$pageMeta = [
    'title' => 'Pénzügyek',
    'packages' => ['datatables', 'select2']
];
?>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="text-bg-primary rounded w-100 p-3">
                    <span class="fs-1 d-block"><?php
                                                $sum = 'TODO';

                                                echo $sum;
                                                ?> Ft</span>
                    <span>Egyenleg</span>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="text-bg-secondary rounded w-100 p-3">
                    <span class="fs-1 d-block"><?php
                                                $sum = 'TODO';

                                                echo $sum;
                                                ?> Ft</span>
                    <span>Saját bevétel idén</span>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills my-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-invoices-tab" data-bs-toggle="pill" data-bs-target="#pills-invoices" type="button" role="tab" aria-controls="pills-invoices" aria-selected="true">Számlák</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-expenses-tab" data-bs-toggle="pill" data-bs-target="#pills-expenses" type="button" role="tab" aria-controls="pills-expenses" aria-selected="false">Kiadások</button>
            </li>
        </ul>

        <hr>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-invoices" role="tabpanel" aria-labelledby="pills-invoices-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="invoices_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Projekt</th>
                                <th>Sorszám</th>
                                <th>Kiállítás dátuma</th>
                                <th>Befizetés határideje</th>
                                <th>Összeg</th>
                                <th>Státusz</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $invoices = Invoice::getAll();

                            foreach ($invoices as $invoice) {
                                echo '<tr>';
                                echo '<td>' . $invoice->getId() . '</td>';
                                echo '<td><span title="' . $invoice->getProject()->getClient()->getName() . '" data-bs-toggle="tooltip">' . $invoice->getProject()->getName() . '</span></td>';
                                echo '<td>' . $invoice->getInvoiceId() . '</td>';
                                echo '<td>' . $invoice->getCreateDate(true) . '</td>';
                                if (strtotime($invoice->getDeadline()) < time() && $invoice->getStatus() == 0) echo '<td class="text-danger">' . $invoice->getDeadline(true) . '</td>';
                                else echo '<td>' . $invoice->getDeadline(true) . '</td>';
                                echo '<td>' . number_format($invoice->getAmount(), 0, 0, ' ') . ' Ft</td>';
                                echo '<td>' . $invoice->getStatus(2) . '</td>';
                                echo '<td class="text-end"><div class="action-buttons">';
                                echo '<button class="btn btn-sm btn-success" onclick="modal_open(\'penzugyek/szamlaBefizetes\', {id:' . $invoice->getId() . '})"><i class="fa fa-money-bill"></i></button>';
                                echo '<button class="btn btn-sm btn-primary" onclick="modal_open(\'penzugyek/szamlaReszletek\', {id:' . $invoice->getId() . '})"><i class="fa fa-eye"></i></button>';
                                echo '</div></td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#invoices_table').DataTable({
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
                    targets: [7]
                },
            ],
            order: [3, 'desc'],
            layout: {
                topStart: {
                    buttons: [{
                        text: 'Új számla',
                        className: 'btn-primary',
                        action: function() {
                            modal_open('penzugyek/szamlaUj');
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