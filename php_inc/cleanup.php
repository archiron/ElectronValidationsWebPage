
<?php
session_start();

// Chemin de base (à vérifier)
$baseDir = '/eos/project/c/cmsweb/www/egamma/validation/Electrons/';

if (isset($_POST['file_to_delete'])) {
    $fileInput = $_POST['file_to_delete'];
    
    // Nettoyage du nom de fichier
    $file = basename($fileInput);
    
    // CONSTRUCTION DU CHEMIN : Ajoutez 'BasketList/' si vos fichiers y sont stockés
    // Si vos fichiers sont à la racine de $baseDir, retirez '/BasketList'
    $path = basename($baseDir . 'BasketList/' . $file); 

    if (file_exists($path)) {
        error_log("DELETE: {$_SERVER['REMOTE_USER']} -> {$file}");
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
