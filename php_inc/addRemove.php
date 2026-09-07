<?php  
    session_start();
    include '../php_inc/security.inc.php';
    /*
        if (isset($_POST['pTableData'])) {
            echo 'pTableData OK in addRemove.php' . '<br>' . "\n";
            echo $_POST['pTableData'];
            //echo "file name : " . $_SESSION['fileForHistos_eos'] . '<br>' . "\n";
            $tableData = stripcslashes($_POST['pTableData']);
            $tableData = json_decode($tableData,TRUE);
            foreach ($tableData as $key => $value) {
                echo $value . '<br>' . "\n";
            }
            file_put_contents($_SESSION['fileForHistos_eos'], implode(PHP_EOL, $tableData));
            $corresp = 1;
        }
        else {
            echo 'no $_POST["pTableData"] in addRemove.php' . '<br>' . "\n";
        }

        if (isset($_POST['selLink2'])) {
            echo 'selLink2 OK in addRemove.php' . '<br>' . "\n";
            echo $_POST['selLink2']; 
        }
        else {
            echo 'selLink2 KO in addRemove.php' . '<br>' . "\n"; 
        }
    */
    // --- SÉCURITÉ 1 : Vérification de l'existence et de la validité de la session ---
if (!isset($_SESSION['fileForHistos_eos']) || empty($_SESSION['fileForHistos_eos'])) {
    http_response_code(403);
    die("Erreur : Session invalide ou cible manquante.");
}

$targetFile = $_SESSION['fileForHistos_eos'];

// --- SÉCURITÉ 2 : Interdiction d'écraser ou de modifier un script exécutable ---
$extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$forbiddenExtensions = ['php', 'php5', 'phtml', 'htaccess'];

    // --- SÉCURITÉ 2 : Validation du nom de fichier et de son extension ---
    // On utilise validate_safe_filename pour s'assurer que le fichier cible est autorisé
    // Liste blanche : 'txt', 'json', 'csv', 'log'
    validate_safe_filename($targetFile, ['txt', 'json', 'csv', 'log']);

    if (isset($_POST['pTableData'])) {
        echo 'pTableData OK in addRemove.php' . '<br>' . "\n";
        
        // Sécurisation de l'affichage direct (XSS)
        echo htmlspecialchars($_POST['pTableData'], ENT_QUOTES, 'UTF-8') . '<br>' . "\n";
        
        $tableData = stripcslashes($_POST['pTableData']);
        $tableData = json_decode($tableData, true);
        
        // Vérification que le JSON est bien un tableau valide
        if (!is_array($tableData)) {
            http_response_code(400);
            die("Erreur : Format de données invalide.");
        }

        // --- SÉCURITÉ 3 : Nettoyage et inspection du contenu à écrire ---
        check_php_injection($tableData);
        // Affichage sécurisé de contrôle (XSS)
        foreach ($tableData as $key => $value) {
            echo htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . '<br>' . "\n";
        }
        
        // Écriture sécurisée des données nettoyées
        if (file_put_contents($targetFile, implode(PHP_EOL, $tableData)) !== false) {
            $corresp = 1;
            echo "Fichier mis à jour avec succès.<br>\n";
        } else {
            http_response_code(500);
            echo "Erreur lors de l'écriture du fichier.<br>\n";
        }
    } else {
        echo 'no $_POST["pTableData"] in addRemove.php' . '<br>' . "\n";
    }

    if (isset($_POST['selLink2'])) {
        // Sécurisation contre les failles XSS lors de l'affichage direct
        echo 'selLink2 OK in addRemove.php : ' . htmlspecialchars($_POST['selLink2'], ENT_QUOTES, 'UTF-8') . '<br>' . "\n"; 
    } else {
        echo 'selLink2 KO in addRemove.php' . '<br>' . "\n"; 
    }

?>
