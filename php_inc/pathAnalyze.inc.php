<?php

function extractAllPaths(array $tab, string $chemin) : array {
    $t2 = [];  // chemins complets (sans .sys.)
    
    foreach ($tab as $key0 => $value0) { // level 0
        foreach ($value0 as $key1 => $value1) { // level 1
            $path1 = $key0 . '/' . $key1;
            foreach ($value1 as $value2) { // level 2
                $folderPath = $path1 . '/' . $value2;
                $t2[] = [$folderPath, array_slice(scandir($chemin . '/' . $folderPath), 2)];
            }
        }

    }
    return $t2;
}

function extractAllHistos(array $tab, string $chemin) : array{
    $t2 = [];  // chemins complets (sans .sys.)
    foreach ($tab as $value0) { // level 0
        $tmp = $chemin . '/' . $value0[0] . '/config_target.txt';
        $lines = file($tmp, FILE_IGNORE_NEW_LINES);
        $t2[] = $lines;
    }
    return $t2;
}

function extractAllConfigs(array $tab, string $chemin) : array{
    $t2 = [];  // chemins complets (sans .sys.)
    foreach ($tab as $value0) { // level 0
        $tmp = $chemin . '/' . $value0[0] . '/definitions.txt';
        $lines = file($tmp, FILE_IGNORE_NEW_LINES);
        $t2[] = $lines;
    }
    return $t2;
}

function convertTabPaths(array $tab) : array {
    $tmp = [];
    foreach ($tab as $key0 => $value0) {
        $tmp[$value0[0]] = $key0;
    }
    return $tmp;
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
                //$log .= $value . '/' . $value1 . "\n";
            }
            $t1[$tmp] = $t2;
        }
    }
    return $t1;
}

?>
