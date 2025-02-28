<?php
$project = new Project($_POST['id']);
$wp = new WordPressConnection($project);

if ($wp->toggleMaintenance()) {
    alert_redirect('success', URL . 'admin/projektek/wordpress/d/' . $project->getId());
} else {
    alert_redirect('error', URL . 'admin/projektek/wordpress/d/' . $project->getId());
}
