<?php  
    session_start();

    if (isset($_POST['dataS'])) {
        // 1. Vérifier l'injection PHP sur toutes les données POST
        check_php_injection($_POST);

        $raw = $_POST['dataS'];

        // 2. Supprimer stripcslashes (inutile, introduit des caractères dangereux)
        //    Les données sont au format query-string : site=Dev2&key1=val1&key2=val2
        $pairs = explode('&', $raw);

        $site = '';
        $parsed = [];

        foreach ($pairs as $pair) {
            $temp2 = explode('=', $pair, 2); // limite à 2 parties (les valeurs peuvent contenir '=')
            if (count($temp2) < 2) continue;

            [$k, $v] = $temp2;

            if ($k === 'site') {
                // 3. Valider le site (logique de validate_safe_filename : basename + whitelist)
                $site = basename($v);
                if (!preg_match('/^[a-zA-Z0-9_-]+$/', $site)) {
                    http_response_code(403);
                    die("Erreur : Nom de site invalide.");
                }
                if ($site === 'Dev2') {
                    $site = 'Dev';
                }
            } else {
                // 4. Valider chaque clé de session (même logique)
                if (!preg_match('/^[a-zA-Z0-9_-]+$/', $k)) {
                    http_response_code(403);
                    die("Erreur : Clé de session invalide.");
                }
                $parsed[$k] = $v;
            }
        }

        // 5. $site doit exister et être valide
        if (empty($site)) {
            http_response_code(400);
            die("Erreur : Paramètre 'site' manquant ou invalide.");
        }

        // 6. Stocker en session avec préfixe validé
        foreach ($parsed as $key => $value) {
            $_SESSION[$site . '-' . $key] = $value;
        }

        // 7. Échapper la sortie (anti-XSS)
        echo 'dataS OK in goBasket.php' . '<br>' . "\n";
        echo htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');

    } else {
        echo 'no $_POST["dataS"] in goBasket.php' . '<br>' . "\n";
    }

?>
