<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$user = new User($_SESSION['id']);

$project = new Project($_POST['project']);
?>

<h3 class="h4 mb-3">Feladatok</h3>
<button class="btn btn-primary" onclick="modal_open('trello/uj', {project: <?= $project->getId() ?>})">Új feladat</button>

<div class="mt-3 row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-5 g-3">
    <?php
    $trello = new TrelloTable();

    $cards = $trello->getProjectCards($project, 20, ['Teendő', 'Folyamatban', 'Visszajelzés / Felülvizsgálat']);

    foreach ($cards as $card) {
        $arrow = '<i class="fa fa-chevron-right text-primary me-2 mt-1"></i>';
        $border = 'primary';
        if ($trello->getList($card['idList'])['name'] == 'Folyamatban') {
            $arrow = '<i class="fa fa-chevron-right text-warning me-2 mt-1"></i>';
            $border = 'warning';
        }
        if ($card['due'] != NULL) {
            $due = strtotime($card['due']);
            if ($due < time()) {
                $arrow = '<i class="fa fa-angles-right text-danger mt-1 me-2"></i>';
                $border = 'danger';
            }
        }

        $members = $card['idMembers'];
        if (!in_array($user->getTrelloId(), $members)) {
            $border = 'secondary';
            if ($card['due'] != NULL && strtotime($card['due']) < time()) $arrow = '<i class="fa fa-angles-right text-secondary me-2 mt-1"></i>';
            else $arrow = '<i class="fa fa-chevron-right text-secondary me-2 mt-1"></i>';
        }
    ?>
        <div class="col d-flex align-items-stretch">
            <div class="projectTaskCard w-100 card border border-<?= $border ?>" onclick="window.open('<?= $card['shortUrl'] ?>', '_blank')">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <?= $arrow ?>
                        <div>
                            <?php
                            $list = $trello->getList($card['idList']);
                            if (!in_array($user->getTrelloId(), $members)) {
                                echo '<span class="badge bg-secondary">' . $list['name'] . '</span>';
                            } else {
                                switch ($list['name']) {
                                    case 'Teendő':
                                        echo '<span class="badge text-bg-primary">Teendő</span>';
                                        break;
                                    case 'Folyamatban':
                                        echo '<span class="badge text-bg-warning">Folyamatban</span>';
                                        break;
                                    case 'Visszajelzés / Felülvizsgálat':
                                        echo '<span class="badge text-bg-info">Visszajelzés / Felülvizsgálat</span>';
                                        break;
                                    default:
                                        echo '<span class="badge text-bg-secondary">' . $list['name'] . '</span>';
                                        break;
                                }
                            }
                            ?>

                            <h5 class="card-title fw-normal">
                                <?= $card['name'] ?>
                            </h5>

                            <?php
                            $memberNames = [];
                            foreach ($members as $member) {
                                $member = $trello->getMember($member);
                                if ($member['id'] != $user->getTrelloId()) {
                                    $_user = $trello->getUserById($member['id']);
                                    $memberNames[] = '<img src="' . $_user->getProfilePicture() . '" height="16" width="16" class="rounded-circle mb-1"> ' . $_user->getFullname();
                                } else $memberNames[] = '<img src="' . $user->getProfilePicture() . '" height="16" width="16" class="rounded-circle mb-1"> Én';
                            }
                            ?>

                            <?php if (!empty($memberNames)) { ?><p class="mb-3 text-muted">
                                    <?= implode('<br>', $memberNames) ?>
                                </p>
                            <?php } ?>

                            <?php if ($card['due'] != NULL) { ?><p class="mb-0 text-muted">
                                    <?php if (strtotime($card['due']) < time()) { ?><span class="badge text-bg-danger"><?= date('Y. m. d.', strtotime($card['due'])) ?></span>
                                    <?php } else if (strtotime($card['due']) < time() + 86400) { ?><span class="badge text-bg-warning"><?= date('Y. m. d.', strtotime($card['due'])) ?></span>
                                    <?php } else { ?><span class="badge text-bg-secondary"><?= date('Y. m. d.', strtotime($card['due'])) ?></span>
                                    <?php } ?>
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>