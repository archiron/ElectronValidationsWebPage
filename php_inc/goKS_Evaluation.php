<?php  
    session_start();
    if (isset($_POST['boldSelection'])) {
        echo 'dataS OK in goKS_Evaluation.php' . '<br>' . "\n";
        echo $_POST['boldSelection']; 
        $data = stripcslashes($_POST['boldSelection']);
        $data = json_decode($data,TRUE);
        $temp0 = $data;
        foreach ($temp0 as $key => $value) {
            //echo $key . " : " . $value . '<br>' . "\n";
            $_SESSION[$key] = $value;
        }
    
    }
    else {
        echo 'no $_POST["boldSelection"] in goKS_Evaluation.php' . '<br>' . "\n";
    }

?>
