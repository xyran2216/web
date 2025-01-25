<?php
require '../includes/configuration.php';
require '../includes/extra.php';
$errors = [];
require "ahc.php";
$Authenticator = new Authenticator();
if (!isset($_SESSION['auth_secret'])) {
    $secret = $Authenticator->generateRandomSecret();
    $_SESSION['auth_secret'] = $secret;
}
$csrf = ($_POST['__csrf']);
$code = $_POST['2fa-code'];
if ($csrf != $_SESSION['token']) {
    $errors = 'error token.';
}
if ($info["Auth"] != 0) {
    $errors = 'this system is under maintenance.';
}
if (empty($code)) {
    $errors = 'Nice try.';
}
$Authenticator = new Authenticator();
$checkResult = $Authenticator->verifyCode($_SESSION['auth_secret'], $code, 2);
if ($checkResult == false) {
    $errors = 'Error.';
}
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    header('Location: ../profile');
} else {
    $SQLUpdate = $sql->prepare("UPDATE `users` SET `google` = :google WHERE `username` = :username AND `ID` = :id");
    $SQLUpdate->execute(array(':google' => $_SESSION['auth_secret'], ':username' => $_SESSION['username'], ':id' => $_SESSION['ID']));
    $SQLUpdate = $sql->prepare("UPDATE `users` SET `ah` = :ah WHERE `username` = :username AND `ID` = :id");
    $SQLUpdate->execute(array(':ah' => 1, ':username' => $_SESSION['username'], ':id' => $_SESSION['ID']));
    session_start();
    $_SESSION['actived'] = 'Success';
    header('Location: ../profile');

}