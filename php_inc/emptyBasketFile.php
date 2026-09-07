<?php  
    session_start();
    include '../php_inc/security.inc.php';

    /*if (isset($_POST['basketFile'])) {
        echo 'basketFile OK in emptyBasketFile.php' . '<br>' . "\n";
        echo $_POST['basketFile']; 
        $basketFile = stripcslashes($_POST['basketFile']);
        $basketFile = json_decode($basketFile,TRUE);
        check_php_injection($basketFile);
        foreach ($basketFile as $key => $value) {
            echo $value . "\n";
        }
        file_put_contents($_SESSION['fileForHistos_eos'], implode(PHP_EOL, $basketFile));
    }
    else {
        echo 'no $_POST["basketFile"] in emptyBasketFile.php' . '<br>' . "\n";
    }*/

    // --- SÉCURITÉ 1 : Contrôle strict de la session et du fichier cible ---
    if (!isset($_SESSION['fileForHistos_eos']) || empty($_SESSION['fileForHistos_eos'])) {
        http_response_code(403);
        die("Erreur : Session invalide ou cible manquante.");
    }

    $targetFile = $_SESSION['fileForHistos_eos'];

    // Validation de l'extension du fichier cible (uniquement txt, json, csv, log)
    validate_safe_filename($targetFile, ['txt', 'json', 'csv', 'log']);

    if (isset($_POST['basketFile'])) {
        echo 'basketFile OK in emptyBasketFile.php' . '<br>' . "\n";
        
        // SÉCURITÉ 2 : Protection XSS sur l'affichage brut de debug
        echo htmlspecialchars($_POST['basketFile'], ENT_QUOTES, 'UTF-8') . '<br>' . "\n"; 
        
        $basketFile = stripcslashes($_POST['basketFile']);
        $basketFile = json_decode($basketFile, true);
        
        // Validation structurelle du JSON reçu
        if (!is_array($basketFile)) {
            http_response_code(400);
            die("Erreur : Format de données JSON invalide.");
        }

        // SÉCURITÉ 3 : Blocage de l'injection PHP
        check_php_injection($basketFile);
        
        // Affichage sécurisé de contrôle (XSS)
        foreach ($basketFile as $key => $value) {
            echo htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . "\n";
        }
        
        // Écriture sécurisée
        if (file_put_contents($targetFile, implode(PHP_EOL, $basketFile)) !== false) {
            echo "Panier vidé et mis à jour avec succès.<br>\n";
        } else {
            http_response_code(500);
            echo "Erreur lors de l'écriture du fichier.<br>\n";
        }
    } else {
        echo 'no $_POST["basketFile"] in emptyBasketFile.php' . '<br>' . "\n";
    }
?>
