<?php  
    session_start();
    include '../php_inc/security.inc.php';
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';

    /*if (isset($_POST['sharedFile'])) {
        echo 'sharedFile OK in sharedFile.php' . '<br>' . "\n";
        echo "post = " . $_POST['sharedFile'] . "\n"; 
        $sharedFile = stripcslashes($_POST['sharedFile']);
        $sharedFile = json_decode($sharedFile,TRUE);
        
        $wR = $sharedFile[0];
        $shareFileName = $sharedFile[1];
        $text = $sharedFile[2];

        $fSharedName = $wR . "/" . basename($shareFileName);
        $fSharedName_eos=str_replace($racine_html, $racine_eos, $fSharedName);
        file_put_contents($fSharedName_eos, implode(PHP_EOL, $text));
    }
    else {
        return 'no $_POST["sharedFile"] in sharedFile.php <br>' . "\n";
    }*/

    if (isset($_POST['sharedFile'])) {
        $sharedFile = stripcslashes($_POST['sharedFile']);
        $sharedFile = json_decode($sharedFile, true);
        
        // Vérification que le format JSON reçu est correct et complet
        /*
            if (!is_array($sharedFile) || count($sharedFile) < 3) {
                http_response_code(400);
                die("Structure de données invalide.");
            }

            $wR = $sharedFile[0];
            // Force l'extraction du nom strict pour éviter les injections de dossiers (../)
            $shareFileName = basename($sharedFile[1]); 
            $text = $sharedFile[2];

            // --- SÉCURITÉ : Validation de l'extension ---
            $allowedExtensions = ['txt', 'json', 'csv', 'log']; // Extensions autorisées uniquement
            $extension = strtolower(pathinfo($shareFileName, PATHINFO_EXTENSION));

            if (empty($extension) || !in_array($extension, $allowedExtensions)) {
                http_response_code(403);
                die("Erreur : Extension de fichier non autorisée.");
            }

            // --- SÉCURITÉ : Validation du contenu (Optionnel mais recommandé) ---
            // Si le fichier ne doit contenir que du texte ou du JSON, on peut bloquer les balises PHP
            if (is_array($text)) {
                $contentToCheck = implode(PHP_EOL, $text);
            } else {
                $contentToCheck = (string)$text;
                $text = [$text];
            }

            if (preg_match('/<\?php|<\?/i', $contentToCheck)) {
                http_response_code(403);
                die("Erreur : Le contenu contient du code non autorisé.");
            }
        */
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
