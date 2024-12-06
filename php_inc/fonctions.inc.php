<?php

$_fDL = '<br>' . "\n"; // fin de ligne

function testExtension($histoName, $histoPrevious){

    $after = ""; # $histoName;
    $before = stristr($histoName, '_', true);
    $common = "";
    if ( $before == '' ) { # no _ in histo name
        $before = $histoName;
        $common = $histoName;
    }
    else {
        $afters = explode('_', $histoName);
        $before = $afters[0];
        $nMax = count($afters);

        if ( $afters[$nMax - 1] == "endcaps" ) {
            $after = "endcaps";
            for ( $i = 1; $i < $nMax-1; $i++) {
                $before .= "_" . $afters[$i];
            }
        }
        elseif ( $afters[$nMax - 1] == "barrel" ) {
            $after = "barrel";
            for ( $i = 1; $i < $nMax-1; $i++) {
                $before .= "_" . $afters[$i];
            }
        }
        else {
            if ( $histoPrevious = "" ) {
                $before = $histoName;
                $after = ""; 
                $common = $histoName;
            }
            else {
                $avant =  $afters[0];
                $after = "";
                for ( $i = 1; $i < $nMax-1; $i++) {
                    $avant = $avant . "_" . $afters[$i];
                    if ( $avant == $histoPrevious ) {
                        $before = $avant;
                        $common = $histoPrevious;
                        break;
                    }
                }
                for ( $j = $nMax - $i; $j < $nMax; $j++ ) {
                    $after .= "_" . $afters[$j]; 
                }
                $after = substr($after, 1);
            }
        }

    }

    return array($after, $before, $common);
}

function cleanArray($tmpArray) {
    $tmpArray2 = str_replace(array("\r", "\n"), '', $tmpArray);
    $tmpArray2 = array_filter($tmpArray2);
    $tmpArray2 = array_unique($tmpArray2);
    $tmpArray2 = array_values($tmpArray2);
    return $tmpArray2;
}

function getFileName($num) {
    $name = "basketList." . $num . ".txt";
    return $name;
}

function getSharedFileName($num) {
    $today = date("YmdHis");  
    $name = "sharedList." . $num . "." . $today . ".txt";
    return $name;
}

function getReducedName($Name){
    $tmp = preg_split("/\//", $Name);
    $name = end($tmp);
    return $name;
}

function backToLocal() {
    $_SESSION['fileForHistos_eos'] = $_SESSION['localFileForHistos_eos'];
}

function shortHistoName($Name) {
    $histo_names = explode("/", $Name);
    //prePrint('histo_names', $histo_names);
    //$histo_name = $histo_names[0];
    //simPrint('$Name', $Name);
    //simPrint('count($histo_names)', count($histo_names));
    if ( count($histo_names) > 1 ) {
        $histoShortNames = $histo_names[1];
        $histo_pos = $histoShortNames;
        $histo_positions = preg_split("/[\s,]+/", $histo_pos);
        $short_histo_names = explode(" ", $histoShortNames);
        $short_histo_name = str_replace("h_", "", $short_histo_names[0]);
        if ( stristr($short_histo_name, 'ele_') !== FALSE) {
                $short_histo_name = str_replace("ele_", "", $short_histo_name);
            }
        if ( stristr($short_histo_name, 'scl_') !== FALSE) {
            $short_histo_name = str_replace("scl_", "", $short_histo_name);
        }
        if ( stristr($short_histo_name, 'bcl_') !== FALSE) {
            $short_histo_name = str_replace("bcl_", "", $short_histo_name);
        }
        $short_histo_name = str_replace("\n", '', $short_histo_name);
        return array($short_histo_name, $short_histo_names, $histo_positions);
    }
    else {
        return '';
    }
}

function shorterHistoName($name) {
    $name = str_replace("h_", "", $name);
    $name = str_replace("ele_", "", $name);
    $name = str_replace("scl_", "", $name);
    $name = str_replace("bcl_", "", $name);
    $name = str_replace("\n", '', $name);
    return $name;
}

function titleShortName($elem) {
    $titles = explode(" ", $elem);
    if ( count($titles) == 1 ) {
        $titleShortName = $titles[0];
    }
    else {
        $titleShortName = $titles[0] . "_" . $titles[1];
    }
    $titleShortName = substr($titleShortName, 0, -1);
    $titleShortName = str_replace("\n", '', $titleShortName);
    return $titleShortName;
}

function histosFileNameJSON($data) {
    $tmp = array();
    $obj = json_decode($data);
    //print_r ($obj);
    foreach($obj as $key => $value) {
        echo $key . " => " . $value . "<br>";
        $tmp[] = $key;
    }
    
    //echo "len tmp : " . count($tmp) . "<br>";
    foreach ($tmp as $k1) {
        //$k1 = $tmp[0];
        echo "0 " . $k1 . "<br>"; // affiche Cases
        $aa = $obj->$k1;
        //print_r ($aa );
        //echo "<br>";//<br>
        $tmp2 = array();
        foreach($aa as $key => $value) { //
            echo "1 " . $key . "<br>" ;//. " => " . $value . "<br>";
            $tmp2[] = $key;
        }
        //echo "len tmp2 : " . count($tmp2) . "<br>";
        
        foreach ($tmp2 as $k2) {
            //$k2 = $tmp2[1];
            echo "00 " . $k2 . "<br>"; // affiche 1
            $aa2 = $aa[$k2];
            //print_r ($aa2 );
            //echo "<br>";//<br>
            $tmp3 = array();
            foreach($aa2 as $key => $value) { //
                echo "11 " . $key. " => " . $value . "<br>" ;//. " => " . $value . "<br>";
                $tmp3[] = $key;
            }/**/
            //echo "len tmp3 : " . count($tmp3) . "<br>";
            foreach ($tmp3 as $k3) {
                //$k3 = $tmp3[0];
                echo "000 " . $k3 . "<br>"; // 
                $aa3 = $aa2->$k3;
                //print_r ($aa3 );
                //echo "<br>";//<br>
                $tmp4 = array();
                foreach($aa3 as $key => $value) { //
                    echo "111 " . $key . "<br>" ;//. " => " . $value . "<br>";
                    $tmp4[] = $key;
                }/**/
                //echo "len tmp4 : " . count($tmp4) . "<br>";
                foreach ($tmp4 as $k4) {
                    //$k4 = $tmp4[0];
                    echo "0000 " . $k4 . "<br>"; // 
                    $aa4 = $aa3[$k4];
                    //print_r ($aa4 );
                    //echo "<br>";//<br>
                    $tmp5 = array();
                    foreach($aa4 as $key => $value) { //
                        echo "1111 " . $key. " => " . $value . "<br>" ;//. " => " . $value . "<br>";
                        $tmp5[] = $key;
                    }/**/
                }
            }
        }
    }/**/

    return array($obj, $tmp5);
}

function pageFooter($image_up, $previous_url, $text1, $text2) {
    echo '<table class="tab0">';
    echo '<tr><td class="LtextAlign b0">';
    echo '<a href="#"><img class="s18" src=' . $image_up . ' alt="Top"></a>';
    echo '</td>';
    echo '<td class="LtextAlign">' . $text1 . '</td>';

    echo '<td class=\"LtextAlign\">';
    echo $previous_url;/**/
    echo '</td>';
    
    echo'<td class="RtextAlign">' . $text2 . '</td>';
    echo '<td>';
    echo '<a href="' . $previous_url . '"><img class="s18" src="' . $image_up . '" alt="Up"></a>';
    echo '&nbsp; </td>';
    
    echo '<td></td>';
    echo '</tr>';
    echo '</table>';
}

function filter($url, $image_loupe) {
    echo  ' &nbsp;Filter';
    echo '<form action="" method="post">'; //putSomeTextToRetrieve
    echo '<table>';
    echo '<tr>';
    echo '<td class="CtextAlign">'; // b3
    //echo  ' &nbsp;Filter';
    echo '<img class="s18" src=' . $image_loupe . ' alt="Top">';
    echo '</td>';
    echo '<td class="CtextAlign">';
    echo '<input type="text" name="choiceValue" id="other_test">';// onkeyup="recupvaleur()"
    echo '</td>';
    echo '<td>&nbsp; </td>';
    echo '<td class="CtextAlign">';
    echo '<a href="'.$url.'">Clear filter</a>';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</form>';
}

function getPathPiece($base_dir) {
    $pieces = explode("/", $base_dir);
    $N = count($pieces);
    return $pieces[$N-1];
}

function writeHeaderLinks($base_dir, $url) {
    $web_roots = getRootPath($base_dir);
    include '../php_inc/defaults.inc.php';
    $parties = explode("/", $url);
    $Np = count($parties);
    
    $piece = getPathPiece($base_dir);
    echo "<a href=\"$web_rootsR/index.php\">";
    if ($piece == "Releases") {
        echo "<b>Releases</b>";
    }
    else {
        echo "Releases";
    }
    echo "</a>";
    echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"$web_rootsT/index.php\">";
    if ($piece == "Test") {
        echo "<b>Test</b>";
    }
    else {
        echo "Test";
    }
    echo "</a>";
    echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"$web_rootsD/index.php\">";
    if (($piece == "Dev") && ($parties[$Np-1] != "checkKS.php")) {
        echo "<b>Dev</b>";
    }
    else {
        echo "Dev";
    }
    echo "</a>";
    echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"$web_rootsD/checkKS.php\">";
    if (($piece == "Dev") && ($parties[$Np-1] == "checkKS.php")) {
        echo "<b>Kolmogorov - Smirnov</b>";
    }
    else {
        echo "Kolmogorov - Smirnov";
    }
    echo "</a>";
    echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"https://llrvalidation.in2p3.fr/Validations/index.php\">";
    if ($piece == "Validations") {
        echo "<b>AE</b>";
    }
    else {
        echo "AE";
    }
    echo "</a>";

}

function getRootPath($base_dir) {
    //echo $base_dir . '<br>' . "\n";;
    $tmp = getPathPiece($base_dir); // $pieces[$N-1];
    //echo $tmp . '<br>' . "\n";
    include '../php_inc/defaults.inc.php';
    //echo $web_roots . '-' . strlen($tmp) . "<br>\n";
    if ($tmp == "Dev") {
        return $web_rootsD;
    }
    elseif ($tmp == "Test") {
        return $web_rootsT;
    }
    elseif ($tmp == "Releases") {
        return $web_rootsR;
    }
    elseif ($tmp == 'Validations') {
        return "https://llrvalidation.in2p3.fr/Validations";
    }
    else {
        echo "here we have a pbm with path into getRootPath !!<br>";
    }
}

function getClassColor_cchoice($cchoice, $lRead1, $lRead2) {
    if ($cchoice == "diff") {
        $diff = abs(floatval($lRead1));
        if ($diff > 0.10) { // red
            return "redClass";
        }
        elseif ($diff > 0.05) { // grey
            return "greyClass";
        }
        else {
            return "blueClass";
        }
    }
    elseif ($cchoice == "pValue") {
        $pV = abs(floatval($lRead2));
        if ($pV < 0.05) { // red
            return "redClass";
        }
        elseif ($pV < 0.95) { // grey
            return "greyClass";
        }
        else {
            return "blueClass";
        }
    }
}

function createHistoArray($lineHisto) {
    //prePrint("", $lineHisto);
    $histoArray_0 = array();
    $key = "";
    $tmp = array();
    foreach ($lineHisto as $line) {
        //echo $line;
        if ( iconv_strlen($line) == 1 ) // len == 0, empty line
        {
            //echo "1" . "<br>\n";
            if ( (iconv_strlen($key) != 0) and (count($tmp) != 0) ) {
                $histoArray_0[strval($key)] = $tmp;
                $key = "";
                $tmp = array();
            }
        }
        else { // len <> 0
            //echo "gen" . "<br>\n";
            if ( iconv_strlen($key) == 0 ) {
                $key = $line; // get title
            }
            else {
                $tmp[] = $line; // histo name
                $t1 = explode("/", $line);
                $short_positions = preg_split("/[\s,]+/", $t1[1]);
                if ( $short_positions[3] == 1 ) {
                    $tmp[] = "endLine";
                }
            }
        }
    }
    //prePrint("histoArray_0", $histoArray_0);
    return $histoArray_0;
}

function cleanHistoArray($histoArray, $clefs, $choiceValue) {
    for ($i = 0; $i < count($clefs); $i++) {
        if ( stristr($clefs[$i], $choiceValue) != FALSE ) {
            echo $clefs[$i] . "<br>";
        }
        else {
            $j = 0;
            foreach ($histoArray[$clefs[$i]] as $elem ) {
                if ( $elem == "endLine" ) {
                    ;
                }
                elseif ( stristr($elem, $choiceValue) != FALSE ) {
                }
                else {
                    unset($histoArray[$clefs[$i]][$j]);
                }
                $j++;
            }
        }
    }
    return $histoArray;
}

function imageSize($returnAddr) {
    echo '<table border="1" cellpadding="15" width="50%" align="right" class="clickable sizeChoice">';//
    echo '<tr>';// valign=\"top\"
    echo '<td colspan="6" align="center"><font color="blue"><b>picture definition</b></font></td>';
    for($x = 480; $x <= 960; $x+=120)
    {
        echo '<td align="center" size-choice="' . $x . '">' . $x . "</td>";
    }
    echo "<td></td>";
    echo "<td colspan=\"6\" align=\"center\">";
    echo "<a href=\"" . $returnAddr . "\">BACK</a>";// . "\n"; 
    echo "</td";
    echo "</tr>";
    echo  "</table>";
}

function displayReleaseDateTitle() {
    echo '<span class="ex1">';
    echo '<b>Releases</b>' . "\n";
    echo'</span>';
    echo '<span class="ex1">';
    echo '<b>Last Modified On </b>' . "\n";
    echo'</span><br>';
    echo '<span class="ex1">';
    echo '<b>---</b>' . "\n";
    echo'</span>';
    echo '<span class="ex1">';
    echo '<b>---</b>' . "\n";
    echo'</span><br>';
}

function displayReleaseLinkDate($item, $chemin_eos, $web_roots, $actionFrom) {
    $new_path = $chemin_eos . '/' . $item;
    echo '<span class="ex1">';
    echo '<b><a href="' . $web_roots . '/index.php?actionFrom=' . $actionFrom . '/' . $item . '&cchoice=diff">' . $item . '</a></b>' . "\n";
    echo'</span>';
    echo '<span class="ex1">';
    echo @date('F d, Y, H:i:s', filemtime($new_path));
    echo'</span><br>';
}

function displayVariablesValues($url, $actionFrom, $cchoice, $basket, $createF, $fileForHistos, $curveChoice, $sharedF, $short_histo_name) {
    echo "<br>";
    //echo '<div id="accordionHeader" class = "ex2">';
    //echo '<span>variables values</span>';
    //echo '<div>';
    //echo '<table class="tab5">'; // border="1" border-collapse="collapse"
    //echo '<tr><td class="tab5">';
    echo '<p>variables values</p>';
    //echo '</td></tr>';
    //echo '<tr><td class="tab5">';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">url = </span><span>' . $url . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">actionFrom = </span><span>' . $actionFrom . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">cchoice = </span><span>' . $cchoice . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">basket = </span><span>' . $basket . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">createF = </span><span>' . $createF . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">fileForHistos = </span><span>' . $fileForHistos . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">curveChoice = </span><span>' . $curveChoice . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">sharedF = </span><span>' . $sharedF . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">short_histo_name = </span><span>' . $short_histo_name . '</span><br>';
    //echo '</td></tr>';
    //echo '</table>';
    //echo '</div>';
    //echo '</div>';
    echo "<br>";
}

function displayVariablesValues2($url, $actionFrom, $cchoice, $basket, $createF, $fileForHistos, $curveChoice, $sharedF, $short_histo_name) {
    $tmp = ''; //"<br>";
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">url = </span><span>' . $url . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">actionFrom = </span><span>' . $actionFrom . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">cchoice = </span><span>' . $cchoice . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">basket = </span><span>' . $basket . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">createF = </span><span>' . $createF . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">fileForHistos = </span><span>' . $fileForHistos . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">curveChoice = </span><span>' . $curveChoice . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">sharedF = </span><span>' . $sharedF . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">short_histo_name = </span><span>' . $short_histo_name . '</span><br>';
    //$tmp .= "<br>";
    return $tmp;
}

function prePrint($text1, $text2) {
    echo "<pre>";
    echo $text1 . ' : ';
    print_r($text2);
    echo "</pre>";
}

function simPrint($text1, $text2) {
    echo $text1 . ' : ' . $text2 . '<br>';
}

function rotate($tablo, $name) {
    $index = 0;
    foreach ($tablo as $elem => $value) {
        if ($name == $value) {
            $index = $elem;
            break;
        }
    }
    echo 'index = ' . $index . ' for ' . $name . '<br>' . "\n";
    if ($index == 0) { // no rotation
        return $tablo;
    }
    else {
        $tmp = array();
        $nbMax = count($tablo);
        for ($i=0; $i<$index; $i++) {
            $tmp[] = $tablo[$i];
        }
        foreach ($tmp as $elem => $value) {
            echo 'tmp ' . $elem . ' : ' . $value . '<br>' . "\n";
        }
        for ($i=0; $i<$nbMax-$index; $i++) {
            $tablo[$i] = $tablo[$i + $index];
        }
        for ($i=0; $i<$index; $i++) {
            $tablo[$i+$nbMax-$index] = $tmp[$i];
        }

    }
    return $tablo;
}
?>

