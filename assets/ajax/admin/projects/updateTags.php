<?php
define('FILE_IMPORT', true);
require_once '../../../../inc/import.php';

$project = new Project($_POST['project']);
$tags = $_POST['tags'];
$new_tags = [];

if ($tags == null) {
    $tags = [];
}

foreach ($tags as $key => $tag) {
    if ($stmt = $con->prepare('SELECT `name` FROM `projects_tags` WHERE `id` = ? OR name = ?')) {
        $stmt->bind_param('is', $tag, $tag);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name);
        if ($stmt->num_rows == 0) {
            unset($tags[$key]);
            $new_tags[] = $name;
            continue;
        }
        $stmt->fetch();
        $stmt->close();
    }
}

foreach ($new_tags as $tag) {
    if ($stmt = $con->prepare('INSERT INTO `projects_tags` (`id`, `name`) VALUES (null, ?)')) {
        $stmt->bind_param('s', $tag);
        $stmt->execute();
        $tag_id = $stmt->insert_id;
        $tags[] = $tag_id;
        $stmt->close();
    }
}

// duplikátumok eltávolítása
$tags = array_unique($tags);

// lista rendezése
sort($tags);

if (!$project->setTags($tags)) {
    exit('Hiba történt a címkék mentése közben!');
}

echo 'success';
