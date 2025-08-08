<?php

require '../php_inc/sorties.inc.php';

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

function getPathPiece($base_dir) {
    $pieces = explode("/", $base_dir);
    $N = count($pieces);
    return $pieces[$N-1];
}

function backToLocal() {
    $_SESSION['fileForHistos_eos'] = $_SESSION['localFileForHistos_eos'];
}

function getRootPath($base_dir) {
    $tmp = getPathPiece($base_dir); // $pieces[$N-1];
    include '../php_inc/defaults.inc.php';
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
    elseif ($tmp == 'AE') {
        return "https://llrvalidation.in2p3.fr/AE";
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

function shortHistoName($Name) {
    $histo_names = explode("/", $Name);
    //$histo_name = $histo_names[0];
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

function shortHistoName2($Name) {
    $histo_names = explode("/", $Name);
    if ( count($histo_names) > 1 ) {
        $histoShortNames = $histo_names[1];
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
        return array($short_histo_name, $short_histo_names);
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

    echo '<td class="LtextAlign">';
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
    echo '<form method="post">'; //putSomeTextToRetrieve
    echo '<table>';
    echo '<tr>';
    echo '<td class="CtextAlign">'; // b3
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

function writeHeaderLinks($base_dir, $url) {
    //$web_roots = getRootPath($base_dir);
    include '../php_inc/defaults.inc.php';
    $parties = explode("/", $url);
    $Np = count($parties);
    
    $piece = getPathPiece($base_dir);//echo $piece;
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
    echo "<a href=\"https://cms-egamma.web.cern.ch/validation/Electrons/Comparisons/main_display_comparison.php\">";
    if ($piece == "Comparisons") {
        echo "<b>Comparisons</b>";
    }
    else {
        echo "Comparisons";
    }
    echo "</a>";
    echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"https://cms-egamma.web.cern.ch/validation/Electrons/KS_Evaluation/main_display_KS.php\">";
    if ($piece == "KS_Evaluation") {
        echo "<b>KS Evaluation</b>";
    }
    else {
        echo "KS Evaluation";
    }
    echo "</a>";

}

function writeHeaderMenu() {
    //include '../php_inc/defaults.inc.php';
    echo '<div class="navbar">';
        echo '<a href="https://cms-egamma.web.cern.ch/validation/Electrons/indexElectrons.php">Home</a>';
        echo '<div class="subnav">';
            echo '<button class="subnavbtn">CERN <i class="fa fa-caret-down"></i></button>';
            echo '<div class="subnav-content">';
                echo '<a href="https://cms-egamma.web.cern.ch/validation/Electrons/Test/index.php">Test</a>';
                echo '<a href="https://cms-egamma.web.cern.ch/validation/Electrons/Releases/index.php">Releases</a>';
                echo '<a href="https://cms-egamma.web.cern.ch/validation/Electrons/Dev/index.php">Dev</a>';
                echo '<a href="https://cms-egamma.web.cern.ch/validation/Electrons/Comparisons/main_display_comparison.php">Comparisons</a>';
                echo '<a href="https://cms-egamma.web.cern.ch/validation/Electrons/KS_Evaluation/main_display_KS.php">KS Evaluation</a>';
            echo '</div>';
        echo '</div> '; // subnav
        echo '<div class="subnav">';
            echo '<button class="subnavbtn">LLR <i class="fa fa-caret-down"></i></button>';
            echo '<div class="subnav-content">';
                echo '<a href="https://llrvalidation.in2p3.fr/Validations/index.php">KS</a>';
                echo '<a href="https://llrvalidation.in2p3.fr/AE/index.php">AE</a>';
                echo '<a href="https://llrnotes.in2p3.fr/index_notes.php">Notes</a>';
            echo '</div>';
        echo '</div> '; // subnav
        echo '<a href="#contact">Contact</a>';
    echo '</div>';/**/ // navbar
}

function writeAllHistos($histoArray, $clefs, $lineHisto1, $escaped_url, $pictsValue, $pictsExt, $histoSize, $url_flag, $code, $site) {
    include '../php_inc/defaults.inc.php';
    $tab_histo2Write = [];
    for ($ic = 0; $ic < count($clefs); $ic++) {
        $partialHistoArray = $histoArray[$clefs[$ic]];
        list ($nb_pHA, $partialArray2) = getNbOfLines($partialHistoArray);
        for ($i_line = 0; $i_line< $nb_pHA; $i_line++) {
            $partialA3 = $partialArray2[$i_line];
            $histoToWrite .= '<div class="line">';
            $histoToWrite .= '<table border="0" bordercolor="pink" class="clickable addLink">';
            $histoToWrite .= '<tr>';

            $histoToWrite .= '<td style="vertical-align:top">';
            $histoToWrite .= '<div class="cellUp"><a href="#" onclick="goToTable()"><img class="s18" src=' . $image_up . ' alt="Top"></a></div>' . "\n";
            $histoToWrite .= '<a id="' . $ic.$j . '0" class="anchor6"></a>';
            $histoToWrite .= '</td>';

            $jd = $i_line;
            $kd = 0;
            foreach ($partialA3 as $elem) {
                list ($short_histo_name, $short_histo_names) = shortHistoName2($elem);
                $pict_name = $escaped_url . "/" . $pictsValue ."/" . $short_histo_names[0] . $pictsExt;
                $pict_name_eos = str_replace($racine_html, $racine_eos, 'https:' . $pict_name);
                // Test if url_http exist into the $lineHisto array 
                $testExistUrl = false;
                foreach ($lineHisto1 as $key => $value) {
                    if ( $value == 'https:' . $pict_name ) {
                        $testExistUrl = true;
                    }
                }
                $pictFlag = true;
                if (!file_exists($pict_name_eos)) {
                    $pictFlag = false;
                    //echo $pict_name . ' n\'existe pas' . $_fDL;
                }
                    
                // new options
                $urlOptions = 'url=' . $pict_name . '&basket=view&addLink=KO&code='.$ic.$jd . '0&site=' . $site . '"';
                $histoToWrite .= '<td img_id="' . $urlOptions . '">';
                //$histoToWrite .= '<td>';
                $histoToWrite .= '<div class="cell">';//
                if ($pictFlag) {
                    $n_code = $i.$j.$k;//
                    //simPrintC('new code', $n_code);
                    if ( $url_flag && ($code == $n_code) ) {
                        $histoToWrite .= '<img class="image img" width="' . $histoSize . '" src="' . $pict_name . '" alt="" style="border: 3px solid red;" id="' . $short_histo_name . '_1">';//</a>
                    }
                    else {
                        $histoToWrite .= '<img class="image img" width="' . $histoSize . '" src="' . $pict_name . '" alt="" style="border: 2px solid blue;" id="' . $short_histo_name . '_1">';//</a>
                    }
                }
                else { // no file
                    $histoToWrite .= '<img class="image img" width="200px" src="' . $image_missingFile . '" alt="" style="border: 3px solid red;" id="' . $short_histo_name . '_1">';
                }
                $histoToWrite .= '</div>'; // fin Cell
                $histoToWrite .= "\n";

                $histoToWrite .= '</td>';
                if ( $testExistUrl) {
                    $histoToWrite .= '<td addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" style="width=:60px;text-align:center"><img width="32" height="32" src="' . $image_remove . '" alt="Add">'.$ic.$jd.$kd; // </td>
                }
                else {
                    $histoToWrite .= '<td addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" style="width=:60px;text-align:center"><img width="32" height="32" src="' . $image_add . '" alt="Add">'.$ic.$jd.$kd; // </td>
                }
                $histoToWrite .= '</td>';
                $kd += 1;
            } // partial A3
            $histoToWrite .= '</tr>';
            $histoToWrite .= '</table>';
            $histoToWrite .= '</div>';
            $kd = 0;
        } // i_line

        $tab_histo2Write[] = $histoToWrite;
        $histoToWrite = '';
    } // ic
    return $tab_histo2Write;
}

function createHistoArray($lineHisto) {
    $histoArray_0 = array();
    $key = "";
    $tmp = array();
    foreach ($lineHisto as $line) {
        if ( iconv_strlen($line) == 1 ) // len == 0, empty line
        {
            if ( (iconv_strlen($key) != 0) and (count($tmp) != 0) ) {
                $histoArray_0[strval($key)] = $tmp;
                $key = "";
                $tmp = array();
            }
        }
        else { // len <> 0
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

function getNbOfLines($histoArray) {
    $nb = 0;
    $h2 = [];
    foreach ($histoArray as $elem) {
        //echo $elem . ' : ' . $nb . "<br>\n";
        if ($elem == 'endLine') {
            $nb += 1;
            $h2[] = $tmp;
            $tmp = [];
        }
        else {
            $tmp[] = $elem;
        }
    }
    return array($nb, $h2);
}

function imageSize($returnAddr) {
    echo '<table border="1" cellpadding="15" style="width=:60%;text-align:right" class="clickable sizeChoice">';//
    echo '<tr>';
    echo '<td colspan="6"  style="text-align:center"><font color="blue"><b>picture definition</b></font></td>';
    for($x = 480; $x <= 960; $x+=120)
    {
        echo '<td style="text-align:center" size-choice="' . $x . '">' . $x . "</td>";
    }
    echo "<td></td>";
    echo '<td colspan="6"  style="text-align:center">';
    echo "<a href=\"" . $returnAddr . "\">BACK to histos</a>";// . "\n"; 
    echo "</td>";
    echo "</tr>";
    echo  "</table>";
}

function imageSize2($returnAddr, $name) {
    echo '<table border="1" cellpadding="15" style="width=:50%"; text-align:right" class="clickable sizeChoice">';//
    echo '<tr>';
    echo '<td colspan="6"  style="text-align:center"><font color="blue"><b>picture definition</b></font></td>';
    for($x = 480; $x <= 960; $x+=120)
    {
        echo '<td  style="text-align:center" size-choice="' . $x . '">' . $x . "</td>";
    }
    echo "<td></td>";
    echo '<td colspan=\"6\"  style="text-align:center\">';
    echo '<a href="' . $returnAddr . '" onclick="goToHisto(' . $name . ')>BACK to histos</a>';// . "\n"; 
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

function displayVariablesValues($url, $actionFrom, $cchoice, $basket, $fileForHistos, $curveChoice, $sharedF, $short_histo_name) {
    echo "<br>";
    echo '<p>variables values</p>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">url = </span><span>' . $url . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">actionFrom = </span><span>' . $actionFrom . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">cchoice = </span><span>' . $cchoice . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">basket = </span><span>' . $basket . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">fileForHistos = </span><span>' . $fileForHistos . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">curveChoice = </span><span>' . $curveChoice . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">sharedF = </span><span>' . $sharedF . '</span><br>';
    echo '<span class="blueClass" style="font-weight: bold" valInfo="t11">short_histo_name = </span><span>' . $short_histo_name . '</span><br>';
    echo "<br>";
}

function displayVariablesValues2($url, $actionFrom, $cchoice, $basket, $fileForHistos, $curveChoice, $sharedF, $short_histo_name) {
    $tmp = '';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">url = </span><span>' . $url . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">actionFrom = </span><span>' . $actionFrom . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">cchoice = </span><span>' . $cchoice . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">basket = </span><span>' . $basket . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">fileForHistos = </span><span>' . $fileForHistos . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">curveChoice = </span><span>' . $curveChoice . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">sharedF = </span><span>' . $sharedF . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">short_histo_name = </span><span>' . $short_histo_name . '</span><br>';
    return $tmp;
}

function displayVariablesValues1($url, $actionFrom, $cchoice, $choice, $htmlName, $timeFolderName, $short_histo_name, $long_histo_name) {
    $tmp = ''; //"<br>";
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">url = </span><span>' . $url . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">actionFrom = </span><span>' . $actionFrom . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">cchoice = </span><span>' . $cchoice . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">choice = </span><span>' . $choice . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">htmlName = </span><span>' . $htmlName . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">timeFolderName = </span><span>' . $timeFolderName . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">short_histo_name = </span><span>' . $short_histo_name . '</span><br>';
    $tmp .= '<span class="blueClass" style="font-weight: bold" valInfo="t11">long_histo_name = </span><span>' . $long_histo_name . '</span><br>';
    //$tmp .= "<br>";
    return $tmp;
}

function displayHistos($histoArray, $clefs, $lineHisto1, $i, $escaped_url, $pictsValue, $pictsExt, $histoSize, $url_flag, $code, $site) {
    include '../php_inc/defaults.inc.php';
    $partialHistoArray = $histoArray[$clefs[$i]];
    list ($nb_pHA, $partialArray2) = getNbOfLines($partialHistoArray);

    echo '<b>' . $_SESSION[$site . '-tablo1'] . '</b>'; 
    for ($i_line = 0; $i_line< $nb_pHA; $i_line++) {
        $partialA3 = $partialArray2[$i_line];

        echo '<div class="line">'; // line

        echo '<table style="margin:auto;border:0px solid pink;" class="clickable addLink">'; // center align
        //echo '<table border="0" bordercolor="pink" class="clickable addLink">'; // left align
        echo '<tr>';

        $j = $i_line;
        $k = 0;
        echo '<td style="vertical-align:top">';
        echo '<div class="cellUp"><a href="#" onclick="goToTable()"><img class="s18" src=' . $image_up . ' alt="Top"></a></div>' . "\n";
        //echo '<a id="' . $i.$j . '0" class="anchor6"></a>';
        echo '</td>';

        foreach ($partialA3 as $elem) {
            list ($short_histo_name, $short_histo_names) = shortHistoName2($elem);
            $pict_name = $escaped_url . "/" . $pictsValue ."/" . $short_histo_names[0] . $pictsExt;
            $pict_name_eos = str_replace($racine_html, $racine_eos, 'https:' . $pict_name);
            // Test if url_http exist into the $lineHisto array //
            $testExistUrl = false;
            foreach ($lineHisto1 as $key => $value) {
                if ( $value == 'https:' . $pict_name ) {
                    $testExistUrl = true;
                }
            }
            $pictFlag = true;
            if (!file_exists($pict_name_eos)) {
                $pictFlag = false;
            }
        
            echo '<td>';
            echo '<a id="' . $i.$j.$k . '" class="anchor6"></a>';
            echo '</td>';

                // new options
            $urlOptions = 'url=' . $pict_name . '&basket=view&addLink=KO&code='.$i.$j.$k . '&site=' . $site . '"';
            echo '<td img_id="' . $urlOptions . '">';
            echo '<div class="cell">'; // Cell
            if ($pictFlag) {
                    $n_code = $i.$j.$k;//
                    if ( $url_flag && ($code == $n_code) ) {
                    echo '<img class="image img" width="' . $histoSize . '" src="' . $pict_name . '" alt="" style="border: 3px solid red;" id="' . $short_histo_name . '_1">';//</a>
                }
                else {
                    echo '<img class="image img" width="' . $histoSize . '" src="' . $pict_name . '" alt="" style="border: 2px solid blue;" id="' . $short_histo_name . '_1">';//</a>
                }
            }
            else { // no file
                echo '<img class="image img" width="200px" src="' . $image_missingFile . '" alt="" style="border: 3px solid red;" id="' . $short_histo_name . '_1">';
            }
            echo '</div>'; // fin Cell
            echo "\n";

            echo '</td>';
            if ( $testExistUrl) {
                echo '<td addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" style="width=:60px;text-align:center"><img width="32" height="32" src="' . $image_remove . '" alt="Add">'; // </td>.$i.$j.$k
            }
            else {
                echo '<td addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" style="width=:60px;text-align:center"><img width="32" height="32" src="' . $image_add . '" alt="Add">'; // </td>.$i.$j.$k
            }
            echo '</td>';
            $k += 1;
        } // partialA3
        echo '</tr>';
        echo '</table>';
        echo '</div>'; // line
        $k = 0;
    } // i_line
    echo '<br><br>';
}

function displayAllHistos($histoArray, $clefs, $lineHisto1, $escaped_url, $pictsValue, $pictsExt, $histoSize, $url_flag, $code, $site) {
    include '../php_inc/defaults.inc.php';
    for ($i = 0; $i < count($clefs); $i++) {
        $partialHistoArray = $histoArray[$clefs[$i]];
        list ($nb_pHA, $partialArray2) = getNbOfLines($partialHistoArray);

        echo '<b>' . $clefs[$i] . '</b>'; 
        for ($i_line = 0; $i_line< $nb_pHA; $i_line++) {
            $partialA3 = $partialArray2[$i_line];

            echo '<div class="line">'; // line

            echo '<table style="margin:auto;border:0px solid pink;" class="clickable addLink">'; // center align
            //echo '<table border="0" bordercolor="pink" class="clickable addLink">'; // left align
            echo '<tr>';

            $j = $i_line;
            $k = 0;
            echo '<td style="vertical-align:top">';
            echo '<div class="cellUp"><a href="#" onclick="goToTable()"><img class="s18" src=' . $image_up . ' alt="Top"></a></div>' . "\n";
            echo '<a id="' . $i.$j . '0" class="anchor6"></a>';
            echo '</td>';

            foreach ($partialA3 as $elem) {
                list ($short_histo_name, $short_histo_names) = shortHistoName2($elem);
                $pict_name = $escaped_url . "/" . $pictsValue ."/" . $short_histo_names[0] . $pictsExt;
                $pict_name_eos = str_replace($racine_html, $racine_eos, 'https:' . $pict_name);
                // Test if url_http exist into the $lineHisto array //
                $testExistUrl = false;
                foreach ($lineHisto1 as $key => $value) {
                    if ( $value == 'https:' . $pict_name ) {
                        $testExistUrl = true;
                    }
                }
                $pictFlag = true;
                if (!file_exists($pict_name_eos)) {
                    $pictFlag = false;
                }
                // new options
                $urlOptions = 'url=' . $pict_name . '&basket=view&addLink=KO&code='.$i.$j.$k . '&site=' . $site . '"';
                //$urlOptions = 'url=' . $pict_name . '&basket=view&addLink=KO&code='.$i.$j . '0&site=' . $site . '"';
                echo '<td img_id="' . $urlOptions . '">';
                echo '<div class="cell">'; // Cell
                if ($pictFlag) {
                    $n_code = $i.$j.$k;//
                    if ( $url_flag && ($code == $n_code) ) {
                        echo '<img class="image img" width="' . $histoSize . '" src="' . $pict_name . '" alt="" style="border: 3px solid red;" id="' . $short_histo_name . '_1">';//</a>
                    }
                    else {
                        echo '<img class="image img" width="' . $histoSize . '" src="' . $pict_name . '" alt="" style="border: 2px solid blue;" id="' . $short_histo_name . '_1">';//</a>
                    }
                }
                else { // no file
                    echo '<img class="image img" width="200px" src="' . $image_missingFile . '" alt="" style="border: 3px solid red;" id="' . $short_histo_name . '_1">';
                }
                echo '</div>'; // fin Cell
                echo "\n";

                echo '</td>';
                if ( $testExistUrl) {
                    echo '<td addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" style="width=:60px;text-align:center"><img width="32" height="32" src="' . $image_remove . '" alt="Add">'; // </td>.$i.$j.$k
                }
                else {
                    echo '<td addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" style="width=:60px;text-align:center"><img width="32" height="32" src="' . $image_add . '" alt="Add">'; // </td>.$i.$j.$k
                }
                echo '</td>';
                $k += 1;
            } // partialA3
            echo '</tr>';
            echo '</table>';
            echo '</div>'; // line
            $k = 0;
        } // i_line
    } // loop i
    echo '<br><br>';
}

function getFolderName1($name, $tab, $nb) {
    // 0 : nb evts
    // 1 : path
    // 2 : dataset (ZEE)
    // 3 : PU/noPU
    // 4 : release
    // 5 : comment
    for ($ii = 1; $ii < $nb - 1; $ii++) {
        $aa = explode(' :', $tab[$ii]);
        if ($name == $aa[0]) {
            return array($aa[1], $aa[2]);
        }
    }
}

function getFolderName2($name, $tab, $nb) {
    // 0 : nb evts
    // 1 : path
    // 2 : dataset (ZEE)
    // 3 : PU/noPU
    // 4 : release
    // 5 : comment
    for ($ii = 0; $ii < $nb - 1; $ii++) {
        $aa = explode(' :', $tab[$ii]);
        //echo $name . ' : ' . $aa[1] . '<br>';
        if ($name == $aa[1]) {
            echo $name . ' == ' . $aa[1] . '<br>';
            return array($aa[2], $aa[4]);
        }
        else {
            echo $name . ' != ' . $aa[1] . '<br>';
        }
    }
}

function prePrint($text1, $text2) {
    echo "<pre>";
    echo $text1 . ' : ';
    print_r($text2);
    echo "</pre>";
}

function simPrintC($text1, $text2) {
    echo '<b><span class="blueClass">' . $text1 . '</span></b> : <span class="greyClass">' . $text2 . '</span><br>';
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

function fillSESSION($urlIN) {
    //https://cms-egamma.web.cern.ch/validation/Electrons/Dev/basket.php?short_histo_name=recEleNum&url=//cms-egamma.web.cern.ch/validation/Electrons/Dev/14_2_0_pre1_2024_DQM_dev/FullvsFull_CMSSW_14_1_0_pre7/RECO-RECO_ZpToEE_m6000_14TeV/pngs/h_recEleNum.png&basket=view&actionFrom=/14_2_0_pre1_2024_DQM_dev/FullvsFull_CMSSW_14_1_0_pre7/RECO-RECO_ZpToEE_m6000_14TeV&addLink=KO&long_histo_name=h_recEleNum
    $temp0 = explode("?", $urlIN)[1];
    //simPrint('tmp0', $temp0);
    $temp1 = explode("&", $temp0);
    foreach ($temp1 as $key => $value) {
        echo $key . " : " . $value . '<br>' . "\n";
        $temp2 = explode("=", $value);
        if ($temp2[0] != 'url') {
            $_SESSION[$temp2[0]] = $temp2[1];
        }
    }
}

function diffMaxKS($refArr, $newArr) {
    //simPrint('ref', $refArr);
    //simPrint('new', $newArr);
    $s0 = explode(' ', $refArr);
    $s1 = explode(' ', $newArr);
    $N0 = count($s0);
    $N1 = count($s1);
    if ($N0 != $N1) {
        //echo 'not the same lengths' . "<br>\n";
        //echo 's0 has ' . $N1 . ' elements' . "<br>\n";
        //echo 's1 has ' . $N1 . ' elements' . "<br>\n";
        //exit();
        //echo 'KOLOSSAL ERROR' . "<br>\n";
        return -1.;
    }
    $min0 = min($s0);
    $min1 = min($s1);
    if ($min0 < 0.) {
        //echo 'pbm whith histo ref, min < 0' . "<br>\n";
        return -1;
    }
    elseif ($min1 < 0.) {
        //echo 'pbm whith histo new, min < 0' . "<br>\n";
        return -1;
    }
    $min01 = min($min0, $min1);
    if ($min01 > 0.) {
        $min01 = 0.;
    }
    else {
        $min01 = abs($min01);
    }
    //simPrint('min01', $min01);
    $SumSeries0 = array_sum($s0) + $N0 * $min01;
    $SumSeries1 = array_sum($s1) + $N1 * $min01;
    //simPrint('SumSeries0', $SumSeries0);
    //simPrint('SumSeries1', $SumSeries1);
    if ($SumSeries0 == 0.) {
        //echo 'pbm whith histo ref, sum = 0' . "<br>\n";
        return -1;
    }
    elseif ($SumSeries1 == 0.) {
        //echo 'pbm whith histo new, sum = 0' . "<br>\n";
        return -1;
    }
    $v0 = 0.;
    $v1 = 0.;
    $s = [];
    for ($i = 0; $i < $N0; $i++) {
        $t0 = ($min01 + $s0[$i]) / $SumSeries0;
        $t1 = ($min01 + $s1[$i]) / $SumSeries1;
        $v0 += $t0;
        $v1 += $t1;
        $s[] = abs($v1 - $v0);
    }
    $v = max($s);
    return $v;


}

function extractRelease($v, $r, $o, $d) {
    $v = str_replace($r, '', $v);
    $v = str_replace($o, '', $v);
    $v = str_replace($d, '', $v);
    $v = substr($v, 30);
    $v = explode('-', $v)[0];
    return $v;
}

function extractRECO($t){
    $u = [];
    foreach($t as $k => $v) {
        //echo $k . ' - ' . $v . "<br>\n";
        if (substr($v, -5) == '0.txt') {
            $u[] = $v;
        }
    }
    return $u;
}

function extractMiniAOD($t){
    $u = [];
    foreach($t as $k => $v) {
        //echo $k . ' - ' . $v . "<br>\n";
        if (substr($v, -5) == '1.txt') {
            $u[] = $v;
        }
    }
    return $u;
}

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
?>

