<?php
$project = new Project($_POST['id']);
$wp = new WordPressConnection($project);
$hash = $_POST['wp_hash'];

if ($wp->connect($hash)) {
    alert_redirect('success', URL . 'admin/projektek/adatlap/d/' . $project->getId());
} else {
    alert_redirect('error', URL . 'admin/projektek/adatlap/d/' . $project->getId());
}
