<?php

ignore_user_abort(true);
set_time_limit(0);
header("Content-Type: application/json");

if (!isset($_GET["host"])) {
    die(json_encode([
        "success" => false,
        "error" => 'Please verify all fields.',
    ]));
}

require_once '../includes/db.php';
require_once '../includes/init.php';

$host = $_GET['host'];

$api_key = null;

if (isset($_GET['key'])) {
    $api_key = $_GET['key'];
}

if (!isset($sql)) {
    die(json_encode([
        "success" => false,
        "error" => 'No SQL Detected.',
    ]));
}

$api_plus = $sql->prepare("SELECT * FROM `apiplus` WHERE `apikey` = :key");
$api_plus->execute([':key' => $api_key]);
$api_plus = $api_plus->fetch(PDO::FETCH_ASSOC);


if (!empty($api_key)) {
    $api_plus = $sql->prepare("SELECT * FROM `apiplus` WHERE `apikey` = :key");
    $api_plus->execute([':key' => $api_key]);
    $api_plus = $api_plus->fetch(PDO::FETCH_ASSOC);

    if (empty($api_plus)) {
        die(json_encode([
            "success" => false,
            "error" => 'Your key or your user id is invalid.',
        ]));
    }

    $us = $api_plus["user"];
    $ustimeapi = $api_plus['timeapi'];
} else {
    include '../includes/configuration.php';
    include '../includes/extra.php';
    $us = $_SESSION["ID"];
    if (!isset($_GET["__csrf"]) || $_GET["__csrf"] != $_SESSION['token']) {
        die(json_encode([
            "success" => false,
            "error" => 'Invalid request.',
        ]));
    }
}

$SQLGetInfo = $sql->prepare("SELECT * FROM `users` WHERE `ID` = :id");
$SQLGetInfo->execute(array(
    ':id' => $us
));
$usInf = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);

$usname = $usInf['username'];
$us_id = $usInf['ID'];
$concu = $usInf['concu'];

if (empty($host)) {
    die(json_encode([
        "success" => false,
        "error" => 'Please verify all fields.',
    ]));
}

if (strtoupper($host) == "ALL") {
    $SQL = $sql->prepare("SELECT `id`, `serveur`, `ip` FROM `logs` WHERE `user` = :user  AND `time` + `date` > UNIX_TIMESTAMP()");
    $SQL->execute(array(':user' => $usname));
} elseif (is_numeric($host)) {
    $SQL = $sql->prepare("SELECT `id`, `serveur`, `ip` FROM `logs` WHERE `id` = :id AND `user` = :user  AND `time` + `date` > UNIX_TIMESTAMP()");
    $SQL->execute(array(
        ':user' => $usname,
        ':id' => $host
    ));
} else {
    $SQL = $sql->prepare("SELECT `id`, `serveur`, `ip` FROM `logs` WHERE `ip` = :id AND `user` = :user  AND `time` + `date` > UNIX_TIMESTAMP()");
    $SQL->execute(array(
        ':user' => $usname,
        ':id' => $host
    ));

}
$stopped = false;

while ($result = $SQL->fetch(PDO::FETCH_ASSOC)) {
    $SQLSelect = $sql->prepare("SELECT * FROM `serveurs` WHERE `ID` = :id");
    $SQLSelect->execute(array(
        ':id' => $result["serveur"]
    ));

    while ($show = $SQLSelect->fetch(PDO::FETCH_ASSOC)) {
        $address = getAddress($show['adresse'], $result["ip"], $usname);
        API_SendRequest($address);
    }

    $SQLUpdate = $sql->prepare("UPDATE `logs` SET `time` = 0 WHERE `user` = :user AND `id` = :id AND `time` + `date` > UNIX_TIMESTAMP()");
    $SQLUpdate->execute(array(
        ':user' => $usname,
        ':id' => $result["id"]
    ));

    $stopped = true;
}

if (!$stopped) {
    die(json_encode([
        "success" => false,
        "error" => strtoupper($host) != "ALL" ? 'This shipment is already complete.' : "No Attack running to stop!",
    ]));
}


die(json_encode([
    "success" => true,
    "error" => '',
]));
