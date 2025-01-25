<?php

require '../includes/db.php';
require '../includes/init.php';

ignore_user_abort(true);
set_time_limit(0);
header("Content-Type: application/json");

$SQLSelect = $sql->query("SELECT `method`, `classement` FROM `methodes` ORDER BY `id` ASC");

$layers = [];

while ($show = $SQLSelect->fetch()) {
    $class = "Layer" . $show["classement"];

    if (!isset($layers[$class])) {
        $layers[$class] = [];
    }
    array_push($layers[$class], $show["method"]);
}

die(json_encode($layers));