<?php

function extracFolder1(array $tab, string $chemin) : array {
    $t2 = [];  // chemins complets (sans .sys.)

    foreach ($tab as $value)
    {
        $full = $chemin . '/' . $value;
        if (is_dir($full))
        {
            if ( substr($value, 0, 5) !== '.sys.') {
                $t2[] = $full;
            }
        }
    }
    return array($t2);
}

function extractFilesFolders(array $tab, string $chemin) : array {
    $t1 = [];  // noms de dossiers
    $t2 = [];  // chemins complets (sans .sys.)
    $t3 = [];  // noms de fichiers

    foreach ($tab as $value)
    {
        $full = $chemin . '/' . $value;
        if (is_dir($full))
        {
            $t1[] = $value;
            if ( substr($value, 0, 5) !== '.sys.') {
                $t2[] = $full;//
            }
        }
        elseif (is_file($full))
        {
            $t3[] = $value;
        }
        else
        {
            simPrint("unknown type :", $value);
        }
    }
    return array($t1, $t2, $t3);
}

function extractFolders4Accordion(array $tab) : array {
    $t3 = [];  // noms des dossiers generaux

    foreach($tab as $key => $filename_0)
    {
        $filename = getPathPiece($key);
        $nom = explode('_', $filename);
        $nom_0 = $nom[0];
        $t3[$nom_0][$key] = $filename_0; 
    }

    return $t3;
}

function extractAllFolders(array $tab) {
    $t1 = [];  // noms des dossiers
    $t2 = [];  // noms des dossiers intermédiaires

    foreach($tab as $value) {
        $tmp = $value;
        if (is_numeric($tmp[1])) {
            $tmp_files = array_slice(scandir($value), 2);
            $t2 = []; // vide le tableau
            foreach($tmp_files as $value1) {
                $tmp_files1 = array_slice(scandir($value . '/' . $value1), 2);
                $t2[$value1] = $tmp_files1;
            }
            $t1[$tmp] = $t2;
        }
    }
    return $t1;
}

?>
