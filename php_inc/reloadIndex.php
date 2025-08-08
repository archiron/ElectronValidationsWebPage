<?php  
    session_start();
    if (isset($_POST['tablo2']) && isset($_POST['tablo1'])) {
        echo 'tablo2 OK in reloadIndex.php' . "\n";
        //echo 'tablo2 : ' . $_POST['tablo2'] . "\n";
        $site = $_POST['site'];
        echo 'site : ' . $site . "\n";
        $_SESSION[$site . '-tablo2'] = json_decode($_POST['tablo2']);
        echo 'tablo1 : ' . $_POST['tablo1'] . "\n"; 
        $_SESSION[$site . '-tablo1'] = json_decode($_POST['tablo1']);
        $_SESSION[$site . '-tablo0'] = json_decode($_POST['tablo0']);
    }
    else {
        echo 'no $_POST["tablo2"] in reloadIndex.php' . "\n";
    }

?>
