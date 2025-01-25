<?php
session_start();

unset($_SESSION['username']);
unset($_SESSION['ID']);
unset($_SESSION['token']);
setcookie("identifiant", "");
setcookie("motdepasse", "");
session_destroy();

$target = "";

if (isset($_GET["target"])) {
    $target = htmlspecialchars($_GET["target"]);
    $target = "?target=";
}

header("refresh: 0; url=/login$target");
