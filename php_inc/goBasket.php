<?php  
    session_start();
    if (isset($_POST['dataS'])) {
        echo 'dataS OK in goBasket.php' . '<br>' . "\n";
        echo $_POST['dataS']; 
        $data = stripcslashes($_POST['dataS']);
        $data = json_decode($data,TRUE);
        $temp0 = $data;
        //simPrint('tmp0', $temp0);
        $temp1 = explode("&", $temp0);
        foreach ($temp1 as $key => $value) {
            $temp2 = explode("=", $value);
            if ($temp2[0] == 'site') {
                if ($temp2[1] == 'Dev2') {
                    $site = 'Dev';
                }
                else {
                    $site = $temp2[1];
                }
            }
        }
        foreach ($temp1 as $key => $value) {
            //echo $key . " : " . $value . '<br>' . "\n";
            $temp2 = explode("=", $value);
            if ($temp2[0] != '') {
                $_SESSION[$site.'-'.$temp2[0]] = $temp2[1];
            }
        }
    
    }
    else {
        echo 'no $_POST["dataS"] in goBasket.php' . '<br>' . "\n";
    }

?>
