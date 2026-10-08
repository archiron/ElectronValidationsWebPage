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
    $t2 = [];  // histos (sans .sys.)
    foreach ($tab as $value0) { // level 0
        $tmp = $chemin . '/' . $value0[0] . '/config_target.txt';
        $lines = file($tmp, FILE_IGNORE_NEW_LINES);
        $t2[] = $lines;
    }
    return $t2;
}

function extractAllConfigs(array $tab, string $chemin) : array{
    $t2 = [];  // lignes definition.txt (sans .sys.)
    foreach ($tab as $value0) { // level 0
        $tmp = $chemin . '/' . $value0[0] . '/definitions.txt';
        $lines = file($tmp, FILE_IGNORE_NEW_LINES);
        $t2[] = $lines;
    }
    return $t2;
}

function extractAllMaxDiffPictures(array $tab, string $chemin) : array{ // same as extractAllPaths + extractAllHistos + extractAllConfigs
    $t2 = [];  // numeros des images maxFiff
    foreach ($tab as $key0 => $value0) { // level 0
        $tt = [];
        for ($x = 1; $x <= 3; $x++) {
            $tmp = $chemin . '/' . $value0[0] . '/pngs/maxDiff_comparison_values_' . $x . '.png';
            if (file_exists($tmp)) {
                $tt[] = $x;
            }
        }
        $t2[$key0] = $tt;
    }
    return $t2;
}

function extractAllNeeded(array $tab, string $chemin) : array{
    $t1 = [];  // histos (sans .sys.)
    $t2 = [];  // lignes definition.txt (sans .sys.)
    $t3 = [];  // numeros des images maxFiff
    foreach ($tab as $key0 => $value0) { // level 0
        $tmp1 = $chemin . '/' . $value0[0] . '/config_target.txt';
        $t1[$key0] = file($tmp1, FILE_IGNORE_NEW_LINES);
        $tmp2 = $chemin . '/' . $value0[0] . '/definitions.txt';
        $t2[$key0] = file($tmp2, FILE_IGNORE_NEW_LINES);
        $tt = [];
        for ($x = 1; $x <= 3; $x++) {
            $tmp3 = $chemin . '/' . $value0[0] . '/pngs/maxDiff_comparison_values_' . $x . '.png';
            if (file_exists($tmp3)) {
                $tt[] = $x;
            }
        }
        $t3[$key0] = $tt;
    }
    return [$t1, $t2, $t3];
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

function deepReverse(array &$data): void {
    $data = array_reverse($data, true);
    foreach ($data as &$l1) {
        $l1 = array_reverse($l1);
        foreach ($l1 as &$l2) {
            $l2 = array_reverse($l2);
            foreach ($l2 as &$l3) {
                $l3 = array_reverse($l3);
            }
        }
    }
}
function deepReverse3(array &$arr): void {
    $arr = array_reverse($arr, true);  // ← true = preserve keys
    foreach ($arr as &$l1) {
        if (is_array($l1)) {
            $l1 = array_reverse($l1, true);
            foreach ($l1 as &$l2) {
                if (is_array($l2)) {
                    $l2 = array_reverse($l2, true);
                }
            }
        }
    }
}

?>
