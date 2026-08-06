<?php

function cleanInput($data, $allowSlash = true) {
    if ($allowSlash) {
        // Pour les chemins comme actionFrom
        return preg_replace('/[^a-zA-Z0-9\/_\-\.]/', '', $data);
    } else {
        // Pour les IDs, noms, choix simples (comme cchoice)
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $data);
    }
}
// true autorise les slashes
// false bloque les slashes

/*
$actionFrom = isset($_REQUEST['actionFrom']) 
    ? preg_replace('/[^a-zA-Z0-9\/_\-\.]/', '', $_REQUEST['actionFrom']) 
    : '';
*/

// UTILISATION SURE
// echo '<a href="' . htmlspecialchars($url_safe) . '">Lien</a>';
// ou header('Location: ' . $url_safe);
// Utilisation :
// Pour une redirection PHP : header('Location: ' . $url); (le nettoyage étape 2 suffit)
// Pour un affichage HTML : echo '<a href="' . $url_html_safe . '">Lien</a>';


function cleanInput_V2($data, $allowSlash = false) {
    // Cas spécial pour les URL : validation stricte du protocole avant nettoyage
    if ($allowSlash === 'url') {
        $data = trim($data);
        
        // 1. Validation structurelle
        if (!filter_var($data, FILTER_VALIDATE_URL)) {
            return ''; // Rejet si structure invalide
        }

        // 2. Vérification du protocole (Liste blanche)
        $scheme = strtolower(parse_url($data, PHP_URL_SCHEME));
        if (!in_array($scheme, ['http', 'https'])) {
            return ''; // Rejet si javascript:, data:, file:, etc.
        }

        // 3. Nettoyage final des caractères superflus (optionnel mais recommandé)
        // On garde une URL propre
        return filter_var($data, FILTER_SANITIZE_URL); 
    }

    // Comportement existant pour les autres données
    if ($allowSlash) {
        return preg_replace('/[^a-zA-Z0-9\/_\-\.]/', '', $data);
    } else {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $data);
    }
}

?>
