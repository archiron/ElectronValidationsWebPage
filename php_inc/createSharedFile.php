<?php  
    session_start();
    include '../php_inc/security.inc.php';
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';

    if (isset($_POST['sharedFile'])) {
        /*$sharedFile = stripcslashes($_POST['sharedFile']);
        $sharedFile = json_decode($sharedFile, true);*/
        $sharedFile = securePath($_POST['sharedFile']);
        
        // Vérification que le format JSON reçu est correct et complet
        $wR = $sharedFile[0];
        // Appel des fonctions centralisées en 2 lignes
        $shareFileName = validate_safe_filename($sharedFile[1]); 
        check_php_injection($sharedFile[2]);

        // Construction sécurisée du chemin
        // Assurez-vous que $wR est également nettoyé ou validé par rapport à une liste blanche
        $fSharedName = basename($wR) . "/" . $shareFileName;
        $fSharedName_eos = str_replace($racine_html, $racine_eos, $fSharedName);

        // Écriture sécurisée
        if (file_put_contents($fSharedName_eos, implode(PHP_EOL, $text)) !== false) {
            echo 'Fichier enregistré avec succès.';
        } else {
            http_response_code(500);
            echo 'Erreur lors de l\'écriture du fichier.';
        }
    } else {
        echo 'no $_POST["sharedFile"] in sharedFile.php' . '<br>' . "\n";
    }

?>
