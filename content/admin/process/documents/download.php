<?php
$id = $data[0];
$version = new DocumentVersion($id);
$project = $version->getDocument()->getProject();

redirect(URL . 'storage/' . $project->getId() . '/' . $version->getFilename());
