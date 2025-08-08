<?php  
    session_start();
    if (isset($_POST['basketFile'])) {
        echo 'basketFile OK in emptyBasketFile.php' . '<br>' . "\n";
        echo $_POST['basketFile']; 
        $basketFile = stripcslashes($_POST['basketFile']);
        $basketFile = json_decode($basketFile,TRUE);
        foreach ($basketFile as $key => $value) {
            echo $value . "\n";
        }
        file_put_contents($_SESSION['fileForHistos_eos'], implode(PHP_EOL, $basketFile));
    }
    else {
        echo 'no $_POST["basketFile"] in emptyBasketFile.php' . '<br>' . "\n";
    }

?>
