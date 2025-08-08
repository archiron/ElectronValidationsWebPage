<?php  
    session_start();
    include '../php_inc/defaults.inc.php';    
    include '../php_inc/fonctions.inc.php';

    if (isset($_POST['sharedFile'])) {
        echo 'sharedFile OK in sharedFile.php' . '<br>' . "\n";
        echo "post = " . $_POST['sharedFile'] . "\n"; 
        $sharedFile = stripcslashes($_POST['sharedFile']);
        $sharedFile = json_decode($sharedFile,TRUE);
        
        $wR = $sharedFile[0];
        $shareFileName = $sharedFile[1];
        $text = $sharedFile[2];

        $fSharedName = $wR . "/" . $shareFileName;
        $fSharedName_eos=str_replace($racine_html, $racine_eos, $fSharedName);
        file_put_contents($fSharedName_eos, implode(PHP_EOL, $text));
    }
    else {
        echo 'no $_POST["sharedFile"] in sharedFile.php' . '<br>' . "\n";
    }

?>
