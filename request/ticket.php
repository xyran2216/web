<?php
require '../includes/configuration.php';
require '../includes/extra.php';
$idzzzdijdsduhusydhudhsudihuisdheiufhefgydiuhejfeuihfyuegfheudheufeghdeidnehfgbebuidhned = $userInf['message'];
$errors = [];
$csrf = ($_POST['__csrf']);
if ($csrf != $_SESSION['token']) {
    $errors = 'error token.';
}
$SQLGetTickets = $sql->query("SELECT * FROM `tickets` WHERE `id` = $idzzzdijdsduhusydhudhsudihuisdheiufhefgydiuhejfeuihfyuegfheudheufeghdeidnehfgbebuidhned");
while ($getInfo = $SQLGetTickets->fetch(PDO::FETCH_ASSOC)) {
    $username = $getInfo['username'];
}
if ($username != $_SESSION['username']) {
    $errors = 'Nice try.';
}
if (strlen($_POST['content']) < 10 || strlen($_POST['content']) > 100) {
    $errors = 'Message must be 10-200 characters length.';
}
$updatecontent = $_POST['content'];
$SQLFind = $sql->prepare("SELECT `status` FROM `tickets` WHERE `id` = :id");
$SQLFind->execute(array(':id' => $idzzzdijdsduhusydhudhsudihuisdheiufhefgydiuhejfeuihfyuegfheudheufeghdeidnehfgbebuidhned));
if ($SQLFind->fetchColumn(0) == 'Closed') {
    $errors = 'Ticket is close.';
}
if (empty($updatecontent)) {
    $errors = 'Nice try.';
}
if ($username != $_SESSION['username']) {
    $errors = 'Nice try.';
}
$i = 0;
$SQLGetMessages = $sql->query("SELECT * FROM `messages` WHERE `ticketid` = '$idzzzdijdsduhusydhudhsudihuisdheiufhefgydiuhejfeuihfyuegfheudheufeghdeidnehfgbebuidhned' ORDER BY `messageid` DESC LIMIT 1");
while ($getInfo = $SQLGetMessages->fetch(PDO::FETCH_ASSOC)) {
    if ($getInfo['sender'] == 'Client') {
        $i++;
    }
    if ($i >= 1) {
        $errors = 'Please wait for an admin to respond until you send a new message.';
    }
    if (empty($errors)) {
        $message = htmlentities($updatecontent);
        $messagecrypt = openssl_encrypt($message, "AES-128-ECB", $keykey);
        $SQLinsert = $sql->prepare("INSERT INTO `messages` VALUES(NULL, :ticketid, :content, :sender, :view, UNIX_TIMESTAMP())");
        $SQLinsert->execute(array(':sender' => 'Client', ':view' => '', ':content' => nl2br($messagecrypt), ':ticketid' => $idzzzdijdsduhusydhudhsudihuisdheiufhefgydiuhejfeuihfyuegfheudheufeghdeidnehfgbebuidhned));
        $SQLUpdate = $sql->prepare("UPDATE `tickets` SET `status` = :status WHERE `id` = :id");
        $SQLUpdate->execute(array(':status' => 'Waiting for admin response', ':id' => $idzzzdijdsduhusydhudhsudihuisdheiufhefgydiuhejfeuihfyuegfheudheufeghdeidnehfgbebuidhned));
        session_start();
        $_SESSION['actived'] = 'Success';

    } else {
        session_start();
        $_SESSION['errors'] = $errors;
    }
}
?> 