<?php
/*if (!defined('MAIN_INDEX_LOADED')) {
    http_response_code(403);
    die('Accès direct interdit');
}*/

// UTILISATION SURE
// echo '<a href="' . htmlspecialchars($url_safe) . '">Lien</a>';
// ou header('Location: ' . $url_safe);
// Utilisation :
// Pour une redirection PHP : header('Location: ' . $url); (le nettoyage étape 2 suffit)
// Pour un affichage HTML : echo '<a href="' . $url_html_safe . '">Lien</a>';

function cleanInput($data, $mode = 'strict') {
    if ($mode === 'url') {
        // Pour les chemins comme actionFrom
        return preg_replace('/[^a-zA-Z0-9\/_\-\.]/', '', $data);
    } else {
        // Pour les IDs, noms, choix simples (comme cchoice)
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $data);
    }
}
// true autorise les slashes
// false bloque les slashes

function cleanInput_V2($data, $allowSlash = false) {
    // Cas spécial pour les URL : validation stricte du protocole avant nettoyage
    if ($allowSlash === 'url') {
        $data = trim($data);
        
        // 1. Validation structurelle
        if (!filter_var($data, FILTER_VALIDATE_URL)) {
            return ''; // Rejet si structure invalide
        }

        // 2. Vérification du protocole (Liste blanche)
        $parsed = parse_url($data);
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (!in_array($scheme, ['http', 'https'])) {
            return ''; // Rejet si javascript:, data:, file:, etc.
        }

        // 3. Nettoyage final des caractères superflus (optionnel mais recommandé)
        // On garde une URL propre
        $allowed_hosts = ['cms-egamma.web.cern.ch', 'localhoist'];
        $host = $parsed['host'] ?? '';
        if (!in_array($host, $allowed_hosts)) {
            return ''; 
        }

        // 4. Whitelist des caractères (REMPLACE FILTER_SANITIZE_URL)
        return preg_replace('/[^a-zA-Z0-9\/_\-\.?=&%:]/', '', $data);
    }

    // Comportement existant pour les autres données
    if ($allowSlash) {
        return preg_replace('/[^a-zA-Z0-9\/_\-\.]/', '', $data);
    } else {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $data);
    }
}

function enforceCleanUri() {
    $uri    = $_SERVER['REQUEST_URI'];
    $script = basename($_SERVER['SCRIPT_NAME']); // "index.php"

    $pos = strpos($uri, $script);
    if ($pos === false) return; // script pas trouvé, on laisse passer

    $afterScript = substr($uri, $pos + strlen($script));

    // S'il y a un "/" après le nom du script → format inattendu
    if (str_starts_with($afterScript, '/')) {
        // Récupérer la query string si elle existe
        $qPos  = strpos($afterScript, '?');
        $query = ($qPos !== false) ? substr($afterScript, $qPos) : '';

        // Rediriger vers l'URI propre
        $cleanUri = substr($uri, 0, $pos + strlen($script)) . $query;
        header("Location: $cleanUri", true, 301);
        exit;
    }
}

/**
 * Nettoie et valide un nom de fichier pour empêcher le Path Traversal et les scripts PHP.
 */
function validate_safe_filename($input_filename, $allowed_extensions = ['txt', 'json', 'csv']) {
    $filename = basename($input_filename); // Bloque le "Téléversement/Traversée de répertoire" (../)
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (empty($extension) || !in_array($extension, $allowed_extensions)) {
        http_response_code(403);
        die("Erreur : Extension '$extension' non autorisée.");
    }
    return $filename;
}

/**
 * Vérifie qu'une chaîne ou un tableau ne contient pas de code PHP injecté.
 */
function check_php_injection($data) {
    $string_to_check = is_array($data) ? implode(' ', $data) : (string)$data;
    if (preg_match('/<\?php|<\?/i', $string_to_check)) {
        http_response_code(403);
        die("Erreur : Tentative d'injection de code détectée.");
    }
}

function securePath($chemin) {
    $tmp = stripcslashes($chemin);
    $tmp = json_decode($tmp, true);
    return $tmp;
}

function secureURL(): string {
    // 1. SÉCURISATION DE L'URL COURANTE (À placer ici)
    $raw_host = $_SERVER['HTTP_HOST'] ?? '';
    $raw_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Suppression des caractères de contrôle (Header Injection)
    $clean_host = preg_replace('/[\r\n\t\x00]/', '', $raw_host);
    $clean_uri = preg_replace('/[\r\n\t\x00]/', '', $raw_uri);
    enforceCleanUri();

    // Reconstruction
    return "//{$clean_host}{$clean_uri}";
}

function cleanReferer() {
    // 1. Récupération et nettoyage de base (suppression des caractères de contrôle)
    $raw_referer = $_SERVER['HTTP_REFERER'] ?? '';
    $clean_referer = preg_replace('/[\r\n\t\x00]/', '', $raw_referer);

    // 2. Validation stricte via votre fonction cleanInput (mode 'url' ajouté précédemment)
    return cleanInput($clean_referer, 'url');
}

?>
