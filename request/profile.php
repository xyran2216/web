<?php
require '../includes/configuration.php';
require '../includes/extra.php';
$errors = [];
$cpassword = $_POST['cpassword'];
$npassword = $_POST['npassword'];
$rpassword = $_POST['rpassword'];
$csrf = ($_POST['__csrf']);
if ($csrf != $_SESSION['token']) {
    $errors = 'error token.';
}
if (empty($cpassword) || empty($npassword) || empty($rpassword)) {
    $errors = 'Please fill all.';
}
if (strlen($npassword) < 5 || strlen($npassword) > 20) {
    $errors = 'Password must be 5-20 characters length.';
}
if ($npassword != $rpassword) {
    $errors = 'Passwords must be same.';
}
$SQLCheckCurrent = $sql->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = :username AND `password` = :password");
$SQLCheckCurrent->execute(array(':username' => $_SESSION['username'], ':password' => SHA1($cpassword)));
$countCurrent = $SQLCheckCurrent->fetchColumn(0);
if ($countCurrent == 1) {
    $errors = 'Failed.';
}
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    header('Location: ../profile');
} else {
    $SQLUpdate = $sql->prepare("UPDATE `users` SET `password` = :password WHERE `username` = :username AND `ID` = :id");
    $SQLUpdate->execute(array(':password' => SHA1($npassword), ':username' => $_SESSION['username'], ':id' => $_SESSION['ID']));
    session_start();
    $_SESSION['actived'] = 'Success';
    header('Location: ../profile');

}
    
