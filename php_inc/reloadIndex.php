<?php  
    session_start();
    /*if (isset($_POST['tablo2']) && isset($_POST['tablo1'])) {
        echo 'tablo2 OK in reloadIndex.php' . "\n";
        $site = $_POST['site'];
        echo 'site : ' . $site . "\n";
        $_SESSION[$site . '-tablo2'] = json_decode($_POST['tablo2']);
        echo 'tablo1 : ' . $_POST['tablo1'] . "\n"; 
        $_SESSION[$site . '-tablo1'] = json_decode($_POST['tablo1']);
        $_SESSION[$site . '-tablo0'] = json_decode($_POST['tablo0']);
    }
    else {
        echo 'no $_POST["tablo2"] in reloadIndex.php' . "\n";
    }*/

    if (isset($_POST['tablo2']) && isset($_POST['tablo1'])) {

        // 1. Valider le nom du site (clé de session) — même logique que validate_safe_filename
        $site = basename($_POST['site'] ?? '');
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $site)) {
            http_response_code(403);
            die("Erreur : Nom de site invalide.");
        }

        // 2. Vérifier l'injection PHP sur toutes les données POST
        check_php_injection($_POST);

        // 3. Vérifier que tablo0 est bien présent (bug d'origine)
        if (!isset($_POST['tablo0'])) {
            http_response_code(400);
            die("Erreur : Champ 'tablo0' manquant.");
        }

        // 4. Décoder et valider le JSON (null = JSON invalide)
        $tablo0 = json_decode($_POST['tablo0'], true);
        $tablo1 = json_decode($_POST['tablo1'], true);
        $tablo2 = json_decode($_POST['tablo2'], true);

        if ($tablo0 === null || $tablo1 === null || $tablo2 === null) {
            http_response_code(400);
            die("Erreur : Données JSON invalides.");
        }

        $_SESSION[$site . '-tablo2'] = $tablo2;
        $_SESSION[$site . '-tablo1'] = $tablo1;
        $_SESSION[$site . '-tablo0'] = $tablo0;

        // 5. Échapper la sortie (anti-XSS)
        echo 'tablo2 OK in reloadIndex.php' . "\n";
        echo 'site : ' . htmlspecialchars($site, ENT_QUOTES, 'UTF-8') . "\n";
        echo 'tablo1 : ' . htmlspecialchars($_POST['tablo1'], ENT_QUOTES, 'UTF-8') . "\n";
    } else {
        echo 'no $_POST["tablo2"] in reloadIndex.php' . "\n";
    }
?>
