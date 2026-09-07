<?php

$_fDL = '<br>' . "\n"; // fin de ligne
$baba = 'coucou';

function prePrint($text1, $text2) {
        echo "<pre>";
        echo $text1 . ' : ';
        print_r($text2);
        echo "</pre>";
}

function simPrintC($text1, $text2) {
    if ($text2 != '') {
        echo '<b><span class="blueClass">' . $text1 . '</span></b> : <span class="greyClass">' . $text2 . '</span><br>';
    }
}

function simPrint($text1, $text2) {
    if ($text2 != '') {
        echo $text1 . ' : ' . $text2 . '<br>';
    }
}


?>
