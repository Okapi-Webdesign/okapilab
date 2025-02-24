<?php
$pageMeta = [
    'title' => 'Projektválasztó',
    'description' => 'Válaszd ki a projektet, amelyikkel dolgozni szeretnél!'
];
?>

<div class="card">
    <div class="card-body">
        <div class="list-group">
            <?php
            $client = $user->getClient();
            $projects = $client->getAllProjects();

            foreach ($projects as $project) {
                echo '<a href="' . URL . 'ugyfel/process/profile/projectChange/d/' . $project->getId() . '" class="list-group list-group-item list-group-item-action">' . $project->getName() . '</a>';
            }
            ?>
        </div>
    </div>
</div>