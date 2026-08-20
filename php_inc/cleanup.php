
<?php
/*header('Content-Type: text/plain; charset=utf-8');

session_start();
    //$uploadDir = dirname(__DIR__) ; // . '/BasketList/'
    $uploadDir = '/eos/project/c/cmsweb/www/egamma/validation/Electrons/';
    echo "upload dir : " . $uploadDir;

    // Vérifiez la sécurité (ex: token de session) pour éviter qu'un tiers ne supprime des fichiers
    if (isset($_POST['file_to_delete'])) {
        $file = basename($_POST['file_to_delete']); // Sécurité : empêche la remontée de dossier
        $path = $uploadDir . '/' . $file;

        // Supprime le fichier s'il existe
        if (file_exists($path)) {
            // Optionnel : ne supprimer que si vide
            if (filesize($path) === 0) {
                //unlink($path);
                if (unlink($path)) {
                    echo "Fichier supprimé avec succès : " . $path;
                } else {
                    echo "Échec de la suppression : " . $path;
                }
            }
        }
    }
*/


session_start();

// Chemin de base (à vérifier)
$baseDir = '/eos/project/c/cmsweb/www/egamma/validation/Electrons/';

if (isset($_POST['file_to_delete'])) {
    $fileInput = $_POST['file_to_delete'];
    
    // Nettoyage du nom de fichier
    $file = basename($fileInput);
    
    // CONSTRUCTION DU CHEMIN : Ajoutez 'BasketList/' si vos fichiers y sont stockés
    // Si vos fichiers sont à la racine de $baseDir, retirez '/BasketList'
    $path = $baseDir . 'BasketList/' . $file; 

    // --- DEBUG ---
    // On renvoie le chemin testé dans la réponse (même en HTML)
    echo "<strong>DEBUG INFO:</strong><br>";
    echo "Chemin testé : " . $path . "<br>";
    echo "Fichier existe : " . (file_exists($path) ? 'OUI' : 'NON') . "<br>";
    echo "Nom reçu : " . $file . "<br>";
    echo "Contenu du dossier (si lisible) : <br><pre>";
    if (is_dir($baseDir . 'BasketList/')) {
        print_r(scandir($baseDir . 'BasketList/'));
    } else {
        echo "Le dossier BasketList n'existe pas ou n'est pas lisible.";
    }
    echo "</pre><hr>";
    // ---------------

    if (file_exists($path)) {
        if (unlink($path)) {
            http_response_code(200);
            echo "SUCCES: Fichier supprimé.";
        } else {
            http_response_code(500);
            echo "ERREUR: Permission refusée.";
        }
    } else {
        http_response_code(404);
        echo "ERREUR: Fichier introuvable à ce chemin.";
    }
} else {
    http_response_code(400);
    echo "ERREUR: Aucune donnée reçue.";
}
exit;


?>
