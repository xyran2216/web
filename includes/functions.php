<?php
ob_start();

class user
{
    function last($odb)
    {
        $update = $odb->prepare("UPDATE users SET lastact = ? WHERE ID = ?");
        $update->execute(array(time(), $_SESSION['ID']));
    }

    function _ago($tm, $rcs = 0)
    {
        $cur_tm = time();
        $dif = $cur_tm - $tm;
        $pds = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lngh = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
        for ($v = sizeof($lngh) - 1; ($v >= 0) && (($no = $dif / $lngh[$v]) <= 1); $v--) ;
        if ($v < 0) $v = 0;
        $_tm = $cur_tm - ($dif % $lngh[$v]);
        $no = floor($no);
        if ($no <> 1)
            $pds[$v] .= 's';
        $x = sprintf("%d %s ", $no, $pds[$v]);
        if (($rcs == 1) && ($v >= 1) && (($cur_tm - $_tm) > 0))
            $x .= time_ago($_tm);
        return $x;
    }

    function safeString($string)
    {
        $upper_string = strtoupper($string);
        $parameters = array("<SCRIPT", "UPDATE `", "ALERT(", "<IFRAMW", "<", ">", "</", "/>", "SCRIPT>", "SCRIPT", "DIV", ".CCS", ".JS", "<META", "<FRAME", "<EMBED", "<XML", "<IFRAME", "<IMG", "HREF", "document.cookie");
        foreach ($parameters as $parameter) {
            if (strpos($upper_string, $parameter) !== false) {
                return true;
            }
        }
    }

    function safeStrings($string)
    {
        $upper_string = strtoupper($string);
        $parameters = array("<SCRIPT", "SELECT * FROM `", "ALERT(", "<IFRAMW", "<", ">", "</", "/>", "SCRIPT>", "SCRIPT", "DIV", ".CCS", ".JS", "<META", "<FRAME", "<EMBED", "<XML", "<IFRAME", "<IMG", "HREF", "document.cookie");
        foreach ($parameters as $parameter) {
            if (strpos($upper_string, $parameter) !== false) {
                return true;
            }
        }
    }

    function get_time_ago($time)
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }
        $condition = array(12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return 'about ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }

    function isAdmin($odb)
    {
        $SQL = $odb->prepare("SELECT `rank` FROM `users` WHERE `ID` = :id");
        $SQL->execute(array(':id' => $_SESSION['ID']));
        $rank = $SQL->fetchColumn(0);
        if ($rank == 1) {
            return true;
        } else {
            return false;
        }
    }

    function isModo($odb)
    {
        $SQL = $odb->prepare("SELECT `rank` FROM `users` WHERE `ID` = :id");
        $SQL->execute(array(':id' => $_SESSION['ID']));
        $rank = $SQL->fetchColumn(0);
        if ($rank == 2) {
            return true;
        } else {
            return false;
        }
    }

    function Question($odb)
    {
        $SQL = $odb->prepare("SELECT `ah` FROM `users` WHERE `ID` = :id");
        $SQL->execute(array(':id' => $_SESSION['ID']));
        $rank = $SQL->fetchColumn(0);
        if ($rank == 1) {
            return false;
        } else {
            return true;
        }
    }

    function Questions($odb)
    {
        $SQL = $odb->prepare("SELECT `ah` FROM `users` WHERE `ID` = :id");
        $SQL->execute(array(':id' => $_SESSION['ID']));
        $rank = $SQL->fetchColumn(0);
        if ($rank == 2) {
            return false;
        } else {
            return true;
        }
    }

    function LoggedIn()
    {
        if (isset($_SESSION['username'], $_SESSION['ID'])) {
            return true;
        } else {
            return false;
        }
    }

    function hasMembership($odb)
    {
        $SQL = $odb->prepare("SELECT `expire` FROM `users` WHERE `ID` = :id");
        $SQL->execute(array(':id' => $_SESSION['ID']));
        $expire = $SQL->fetchColumn(0);
        if (time() < $expire) {
            return true;
        } else {
            $SQLupdate = $odb->prepare("UPDATE `users` SET `membership` = 0 WHERE `ID` = :id");
            $SQLupdate->execute(array(':id' => $_SESSION['ID']));
            return false;
        }
    }

    function notBanned($odb)
    {
        $SQL = $odb->prepare("SELECT `status` FROM `users` WHERE `ID` = :id");
        $SQL->execute(array(':id' => $_SESSION['ID']));
        $result = $SQL->fetchColumn(0);
        if ($result == 0) {
            return true;
        } else {
            return false;
        }
    }
}

class stats
{
    function ServeursClients($odb)
    {
        $SQLGetSrv = $odb->query('SELECT `ID` FROM `serveurs`');
        while ($InfoSrv = $SQLGetSrv->fetch(PDO::FETCH_OBJ)) {
            $SQL = $odb->prepare("SELECT COUNT(*) FROM `logs` WHERE `serveur` = :id AND `time` + `date` > UNIX_TIMESTAMP()");
            $SQL->execute(array(':id' => $InfoSrv->ID));
            $serveur_nombre = $SQL->fetchColumn(0);

            $updateServeur = $odb->prepare("UPDATE `serveurs` SET `client` = :client WHERE `ID` = :id");
            $updateServeur->execute(array(':id' => $InfoSrv->ID, ':client' => $serveur_nombre));

        }

    }

    function apisst($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `apiplus` WHERE `user` = :user");
        $SQL->execute(array(":user" => $_SESSION['ID']));
        return $SQL->fetchColumn(0);
    }

    function totalUsers($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `users`");
        return $SQL->fetchColumn(0);
    }

    function totalUsersAbo($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `users` WHERE NOT `membership` = 0");
        return $SQL->fetchColumn(0);
    }

    function totalNews($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `news`");
        return $SQL->fetchColumn(0);
    }

    function totalMethod($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `methodes`");
        return $SQL->fetchColumn(0);
    }

    function totalBoots($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `logs`");
        return $SQL->fetchColumn(0);
    }

    function serveurs($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `serveurs`");
        return $SQL->fetchColumn(0);
    }

    function runningBoots($odb)
    {
        $SQL = $odb->query("SELECT COUNT(*) FROM `logs` WHERE `time` + `date` > UNIX_TIMESTAMP()");
        return $SQL->fetchColumn(0);
    }

    function runningBootUser($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `logs` WHERE `user` = :user AND `time` + `date` > UNIX_TIMESTAMP()");
        $SQL->execute(array(":user" => $_SESSION['username']));
        return $SQL->fetchColumn(0);
    }

    function totalBootsForUser($odb, $user)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `logs` WHERE `user` = :user");
        $SQL->execute(array(":user" => $_SESSION['username']));
        return $SQL->fetchColumn(0);
    }

    function totaliplogs($odb, $user)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `iplogs` WHERE `userID` = :id");
        $SQL->execute(array(":id" => $_SESSION['ID']));
        return $SQL->fetchColumn(0);
    }

    function MsgUser($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `courrier` WHERE `username` = :user AND `vue` = 0");
        $SQL->execute(array(":user" => $_SESSION['username']));
        return $SQL->fetchColumn(0);
    }

    function ticketOuvert($odb)
    {
        $SQL = $odb->prepare('SELECT COUNT(*) FROM `tickets` WHERE `status` = :status AND `username` = :user OR `status` = :status2 AND `username` = :user');
        $SQL->execute(array(':user' => $_SESSION['username'], ':status' => '<span class="label label-warning">En attente d\'une réponse</span>', ':status2' => '<span class="label label-info">Nouveau message</span>'));
        return $SQL->fetchColumn(0);
    }

    function tickets($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `tickets` WHERE `username` = :user");
        $SQL->execute(array(":user" => $_SESSION['username']));
        return $SQL->fetchColumn(0);
    }

    function totaltickets($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `tickets` WHERE `status` = :status");
        $SQL->execute(array(":status" => 'Waiting for admin response'));
        return $SQL->fetchColumn(0);
    }

    function notification($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `courrier` WHERE `username` = :user");
        $SQL->execute(array(":user" => $_SESSION['username']));
        return $SQL->fetchColumn(0);
    }

    function iplogger($odb)
    {
        $SQL = $odb->prepare("SELECT COUNT(*) FROM `iplogger` WHERE `iduser` = :id");
        $SQL->execute(array(":id" => $_SESSION['ID']));
        return $SQL->fetchColumn(0);
    }

    function EnCours($odb)
    {
        $SQL = $odb->prepare('SELECT COUNT(*) FROM `tickets` WHERE `status` = :status AND `username` = :user');
        $SQL->execute(array(':user' => $_SESSION['username'], ':status' => '<span class="label label-warning">En attente d\'une réponse</span>'));
        return $SQL->fetchColumn(0);
    }

    function Repondu($odb)
    {
        $SQL = $odb->prepare('SELECT COUNT(*) FROM `tickets` WHERE `status` = :status AND `username` = :user');
        $SQL->execute(array(':user' => $_SESSION['username'], ':status' => '<span class="label label-info">Nouveau message</span>'));
        return $SQL->fetchColumn(0);
    }

    function Resolu($odb)
    {
        $SQL = $odb->prepare('SELECT COUNT(*) FROM `tickets` WHERE `status` = :status AND `username` = :user');
        $SQL->execute(array(':user' => $_SESSION['username'], ':status' => '<span class="label label-danger">Résolu</span>'));
        return $SQL->fetchColumn(0);
    }
}

?>
