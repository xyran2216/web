<?php
if (!$user->notBanned($sql)) {
    header('Location: logout.php');
}
if (!$user->Question($sql)) {
    header('Location: google.php');
}
?>