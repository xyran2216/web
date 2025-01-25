<?php
const DB_HOST = 'localhost';
const DB_NAME = 'betterst_stresser';
const DB_USERNAME = 'betterst_stresser';
const DB_PASSWORD = 'wqYSG4e~iyuyRuTo48';
global $sql;

$sql = new PDO(sprintf("mysql:host=%s;dbname=%s",
    DB_HOST, DB_NAME), DB_USERNAME, DB_PASSWORD);
$sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$apikey = "gpGIajfFQGDQr6iy1kocdvi8GBn8TtyI0VyGAYmlnPA";
$url = "https://www.blockonomics.co/api/";

$options = array(
    'http' => array(
        'header' => 'Authorization: Bearer ' . $apikey,
        'method' => 'POST',
        'content' => '',
        'ignore_errors' => true
    )
);
