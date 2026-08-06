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
?>
