<?php

$_fDL = '<br>' . "\n"; // fin de ligne
$baba = 'coucou';

function prePrint($text1, $text2) {
        echo "<pre>";
        echo htmlspecialchars($text1) . ' : ';
        print_r($text2);
        echo "</pre>";
}

function simPrintC($text1, $text2) {
    if ($text2 != '') {
        echo '<b><span class="blueClass">' . htmlspecialchars($text1) . '</span></b> : <span class="greyClass">' . htmlspecialchars($text2) . '</span><br>';
    }
}

function simPrint($text1, $text2) {
    if ($text2 != '') {
        echo htmlspecialchars($text1) . ' : ' . htmlspecialchars($text2) . '<br>';
    }
}


?>
