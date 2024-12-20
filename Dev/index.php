<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>releases list webpage</title>
<link rel="stylesheet" href="../php_inc/styles.css">
<link rel="stylesheet" href="../php_inc/all.min.css">
<link rel="stylesheet" href="../js/jquery-ui.min.css">
<script src="../js/jQuery-3.6.3/jquery-3.6.3.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<!-- the modification of img style (img.anchor) is a precious help of M. Mellin ! -->
</head>

<body>
<div class="sticky">    
    <?php include('header.php'); ?>
</div>
<main>
<?php

if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
{
    $handle_2 = fopen($chemin_eos . "/" . $histosFileName, "r");
    if ($handle_2)
    {
        while(!feof($handle_2))
        {
            $lineHisto[] = fgets($handle_2); // read the ElectronMC**Histos**.txt file
        }
        fclose($handle_2);
    }

    $histoArray_0 = createHistoArray($lineHisto);

$clefs_0 = array_keys($histoArray_0);

##### test with Title/Histo name choice
    $histoArray = $histoArray_0;
    $clefs = array_keys($histoArray);
if ( $choiceValue != '' ) {
    $histoArray = cleanHistoArray($histoArray, $clefs, $choiceValue);
    echo " <br>";
}
##### end test with Title/Histo name choice
if ($DBoxflag) {
    $fileDiffpValueName = $chemin_eos . "/" . 'DBox/pValuesDiffHistosNames.txt';
    $handle_4 = fopen($fileDiffpValueName, "w");
}
//echo $cchoice . "<br>";

echo '<table class="tab1">';
for ($ic = 0; $ic < count($clefs); $ic++) {
    $aaa = $ic % 5;
    if ( $aaa == 0 ) {
        echo "\n<tr>";
    }
    $textToWrite = "";
    echo '<td class="b2"><b> ' . $clefs[$ic] . '</b>';
    $titleShortName = titleShortName($clefs[$ic]);
    echo "&nbsp;&nbsp;" . "\n" . "<a href=\"#" . $ic . "\">" ; // write group title $titleShortName
    echo "<img width=\"18\" height=\"15\" src=" . $image_point . " alt=\"Top\">" . " <br><br>";
    $textToWrite .= "</a>";
    $histoPrevious = "";
    $numLine = 0;

    foreach ($histoArray[$clefs[$ic]] as $elem) {
        $otherTextToWrite = "";
        list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem); 

        list ($after, $before, $common) = testExtension($short_histo_name, $histoPrevious);
        $classColor = "blueClass";
        if ($DBoxflag) {
            $filehistoName = $chemin_eos . "/" . 'DBox/' . $short_histo_name . '.txt';
            //echo $filehistoName."<br>";
            $handle_3 = fopen($filehistoName, "r");
            if ($handle_3) {
                for ($ij = 0; $ij <= 13; $ij++) {
                    $tmp = fgets($handle_3);
                }
                $lRead1 = fgets($handle_3); // line 14
                $lRead1 = str_replace(" <p>diff. max. : ","",$lRead1);
                $lRead1 = str_replace("</p>","",$lRead1);
                $lRead2 = fgets($handle_3); // line 15
                $lRead2 = str_replace("</p>","",$lRead2);
                $lRead2 = substr($lRead2, -7);
                $tempDiff = $short_histo_name . ' ' . $lRead1 . $lRead2 . "\n"; // . ' '
                $tempDiff = str_replace(array("\r", "\n"), '', $tempDiff);
                $tempDiff .= "\n";
                if ($handle_4) {
                    fwrite($handle_4, $tempDiff);
                }
                $classColor = getClassColor_cchoice($cchoice, $lRead1, $lRead2);
                
            }
            else {
                echo "could not open " . $filehistoName . "<br>";
            }/**/
        fclose($filehistoName);
        }

        if ( $elem == "endLine" ) {
            $otherTextToWrite .= " <br>";
        }
        elseif ( $histo_positions[3] == "0" ) {
            if ($numLine == 0) {
                $otherTextToWrite .= " &nbsp;<a href=\"#" . $short_histo_name . "\" class=\"" . $classColor . "\">" . $short_histo_name . "</a>" . " &nbsp;";
                $common = $short_histo_name;
                $numLine += 1;
            }
            else { // $numLine > 0
                if ( $after == "" ) {
                    $otherTextToWrite .= " &nbsp;<a href=\"#" . $short_histo_name . "\" class=\"" . $classColor . "\">" . $before . "</a>" . " &nbsp;";
                }
                else{ // $after != ""
                    $otherTextToWrite .= " &nbsp;<a href=\"#" . $short_histo_name . "\" class=\"" . $classColor . "\">" . $after . "</a>" . " &nbsp;";
                }
                $common = $before;
            }
        }
        else { //$histo_positions[3] == "1"
            if ($numLine == 0) {
                $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="" . $classColor . "">' . $short_histo_name . '</a>' . ' &nbsp;';
                $common = $short_histo_name;
            }
            else { // $numLine > 0
                if ( $after == "" ) {
                    $otherTextToWrite .= " &nbsp;<a href=\"#" . $short_histo_name . "\" class=\"" . $classColor . "\">" . $before . "</a>" . " &nbsp;";
                }
                else{ // $after != ""
                    $otherTextToWrite .= " &nbsp;<a href=\"#" . $short_histo_name . "\" class=\"" . $classColor . "\">" . $after . "</a>" . " &nbsp;";
                }
            }
            $numLine = 0;
        }

        $histoPrevious = $common;

        /*if ( $histo_positions[4] == "1" ) {
            $otherTextToWrite .= " <br>";
        }*/
        $otherTextToWrite = str_replace("<br><br>", "<br>", $otherTextToWrite);
        $textToWrite .= $otherTextToWrite ;
    }/**/
    $textToWrite .= " <br>"; 
    $textReplace = TRUE;
    while ( $textReplace ) {
        $textToWrite = str_replace("<br><br>", "<br>", $textToWrite);
        if ( substr_count($textToWrite, '<br><br>') >= 1 ) {
            $textReplace = TRUE;
        }
        else {
            $textReplace = FALSE;
        }
    }
    if ( substr_count($textToWrite, "</a><br><a") >= 1 ) {
            $textToWrite = str_replace("</a><br><a", "</a><a", $textToWrite);
    }
    echo $textToWrite;
    echo "</td>";
    if ( $aaa == 4 ) {
        echo "</tr>";
    }
}

echo  "</table>\n";
echo " <br>";
if ($DBoxflag && $handle_4) {
    fclose($handle_4);
}

$lineFlag = True;
echo '<div class="parent">';
echo '<div class="line">';
for ($i = 0; $i < count($clefs); $i++) {
    echo '<a href="#"><img class="s18" src=' . $image_up . ' alt="Top"></a>';
    $titleShortName = titleShortName($clefs[$i]);

    /* DEV */
    echo '<div class="cell"><b>';
    echo '<a id="' . $i . '" class="anchor1"></a>';
    echo $clefs[$i] . '</b></div>';
    echo '</div><div class="line">';
    echo '<table border="0" bordercolor="pink" class="clickable addLink">';
    echo '<tr>';

    //echo '$lineHisto1 : ' . prePrint('lineHisto1', $lineHisto1) . '<br>';

    foreach ($histoArray[$clefs[$i]] as $elem) {
        if ( $elem != "endLine" ) {
            list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem);
            //simPrint('long histo name', $short_histo_names[0]);
            $pict_name = $escaped_url . "/" . $pictsValue ."/" . $short_histo_names[0] . $pictsExt;
            //simPrint('pict_name', $pict_name);
            $gif_name = $escaped_url . "/gifs/" . $short_histo_names[0] . ".gif";
            /* Test if url_http exist into the $lineHisto array */
            $testExistUrl = false;
            //echo '$gif_name : ' . $gif_name . '<br>';
            //echo '$pict_name : ' . $pict_name . '<br>';
            foreach ($lineHisto1 as $key => $value) {
                //if ( $value == 'https:' . $gif_name ) {
                if ( $value == 'https:' . $pict_name ) {
                        //echo "exist<br>";
                    $testExistUrl = true;
                }
            }
            
            if ( $lineFlag ) {
                echo '<td>';
                echo '<div class="cellUp"><a href="#"><img class="s18" src=' . $image_up . ' alt="Top"></a></div>' . "\n";
                echo '</td>';
            }
            $urlOptions = 'short_histo_name=' . $short_histo_name . '&url=' . $pict_name . '&basket=view&actionFrom=' . $actionFrom . '&addLink=KO';
            $urlOptions .= '&long_histo_name=' . $short_histo_names[0] . '"';
            if (  $histo_positions[3] == "0" ) {
                echo '<td>';
                echo '<div class="cell anchor1" id="' . $short_histo_name . '">';
                //echo '<a href="' . $web_roots . '/basket.php?short_histo_name=' . $short_histo_name . '&url=' . $gif_name . '&basket=view&actionFrom=' . $actionFrom . '&addLink=KO">';
                //echo '<img class="img " width="440" src="' . $gif_name . '" alt=""></a>';
                echo '<a href="' . $web_roots . '/basket.php?' . $urlOptions . '>';
                echo '<img class="img " width="440" src="' . $pict_name . '" alt=""></a>';
                echo '</div>';
                echo "\n";

                if ($DBoxflag) {
                    $filehistoName = 'DBox/' . $short_histo_name . '.txt';
                    $handle_3 = fopen($chemin_eos . "/" . $filehistoName, "r");
                    if ($handle_3)
                    {
                        $lineRead = fgets($handle_3);
                        $lineRead = fgets($handle_3);
                        //echo strlen($lineRead);
                        if (strlen($lineRead) > 1){
                            echo '<div class="cellKS" border="1">';
                            for ($ij = 2; $ij <= 12; $ij++) {
                                $lineRead = fgets($handle_3);
                            }

                            //echo strlen($lineRead);
                            echo '<a href="' . $web_roots . '/globos.php?short_histo_name=' . $short_histo_name . '&url=' . $escaped_url . '&actionFrom=' . $actionFrom . '">Decision Box</a>';
                            $lineRead = fgets($handle_3); // line 14
                            $lineRead = str_replace("<td>", "", $lineRead);
                            echo $lineRead ;
                            $lineRead = fgets($handle_3); // line 15
                            echo $lineRead ;
                            $lineRead = fgets($handle_3); // line 16
                            $lineRead = str_replace("</td>", "", $lineRead);
                            echo $lineRead . "\n";
                            $lineRead = fgets($handle_3); // line 17
                            $lineRead = str_replace("</td>", "", $lineRead);
                            echo $lineRead . "\n";
                            echo "</div>";
                        }
                        fclose($handle_3);
                    }
                }
                echo '</td>';
                //echo '<td align="center" addlink-choice="add to basket" width="200"><font color="blue">add link to basket</font></td>';
                if ( $testExistUrl) {
                    //echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><font color="red"><b>Remove</b></font>'; // </td>
                    //echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><img width="32" height="32" src="' . $image_remove . '" alt="Add"/>'; // </td>
                    echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" src="' . $image_remove . '" alt="Add"/>'; // </td>
                }
                else {
                    //echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><font color="blue"><b>Add</b></font>'; // </td>
                    //echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/>'; // </td>
                    echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/>'; // </td>
                }
                echo '</td>';/**/
                $lineFlag = False;
            }
            else { // line_sp[3]=="1"
                echo '<td>';
                echo '<div class="cell anchor1" id="' . $short_histo_name . '">';
                //echo '<a href="' . $web_roots . '/basket.php?short_histo_name=' . $short_histo_name . '&url=' . $gif_name . '&basket=view&actionFrom=' . $actionFrom . '&addLink=KO">';
                //echo '<img class="img" width="440" src="' . $gif_name . '" alt=""></a>' ;
                echo '<a href="' . $web_roots . '/basket.php?' . $urlOptions . '>';
                echo '<img class="img" width="440" src="' . $pict_name . '" alt=""></a>' ;

                if ($DBoxflag) {
                    echo '</div>';
                    $filehistoName = 'DBox/' . $short_histo_name . '.txt';
                    $handle_3 = fopen($chemin_eos . "/" . $filehistoName, "r");
                    if ($handle_3)
                    {
                        $lineRead = fgets($handle_3);
                        $lineRead = fgets($handle_3);
                        //echo strlen($lineRead);
                        if (strlen($lineRead) > 1) {
                            echo '<div class="cellKS" border="1">';
                            for ($ij = 2; $ij <= 12; $ij++) {
                                $lineRead = fgets($handle_3);
                            }
                            //echo strlen($lineRead);
                            echo '<a href="' . $web_roots . '/globos.php?short_histo_name=' . $short_histo_name . '&url=' . $escaped_url . '&actionFrom=' . $actionFrom . '">Decision Box</a>';
                            $lineRead = fgets($handle_3); // line 8
                            $lineRead = str_replace("<td>", "", $lineRead);
                            echo $lineRead ;
                            $lineRead = fgets($handle_3); // line 9
                            echo $lineRead ;
                            $lineRead = fgets($handle_3); // line 10
                            $lineRead = str_replace("</td>", "", $lineRead);
                            echo $lineRead . "\n";
                            $lineRead = fgets($handle_3); // line 17
                            $lineRead = str_replace("</td>", "", $lineRead);
                            echo $lineRead . "\n";
                            echo '</div>';
                        }
                        fclose($handle_3);
                    }
                }
                echo '</div>';
                echo '</td>';
                //echo '<td align="center" addlink-choice="add to basket" width="200"><font color="blue">add link to basket</font></td>';
                if ( $testExistUrl) {
                    //echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><font color="red"><b>Remove</b></font>'; // </td>
                    //echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><img width="32" height="32" class="selected" src="' . $image_remove . '" alt="Add"/>'; // </td>
                    echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" class="selected" src="' . $image_remove . '" alt="Add"/>'; // </td>
                }
                else {
                    //echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><font color="blue"><b>Add</b></font>'; // </td>
                    //echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $gif_name . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/>'; // </td>
                    echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/>'; // </td>
                }
            echo '</td>';/**/

                echo '</tr>';
                echo '</table>';
                echo '<div class="line">';
                echo '<table border="0" bordercolor="pink" class="clickable addLink">';
                echo '<tr>';
                $lineFlag = True;
            }
        }

    }
}
echo  '</div>';
echo '</table>';
echo '</div>';

} // end of web page construction of histos
else { // construction of folders list web page
    $temp = substr($web_roots,6) . "/index.php";
    if ( $url == $temp ) { // test for root url
        echo "<p>List of the 5 last releases ";
        usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
        echo '( here <b><span class="redClass">' . $dirsList_date[0] .'</span> and <span class="blueClass">' . $dirsList_date[1] .'</span></b> folders).</p>';//

        echo '<table class="tab4">';
        echo "<tr><td width=\"50%\">";
        echo "<b>Last releases";
        echo "</td><td width=\"50%\">";
        echo "<b>Last Modified On ";
        echo "</td></tr><tr>\n";
    
        $i = 0;
        echo "<td>\n";
        foreach($dirsList_date as $filename)
        {
            if ( $i < 5 ) {
                if ( $i == 0 ) {
                    echo "<li>" . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=" . $actionFrom . '/' . getPathPiece($filename) . "&cchoice=diff'><span class=\"redClass\">" . getPathPiece($filename) . "</span></a></b>" . "</li>";//
                    }
                elseif ( $i ==1 ) {
                    echo "<li>" . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=" . $actionFrom . '/' . getPathPiece($filename) . "&cchoice=diff'><span class=\"blueClass\">" . getPathPiece($filename) . "</span></a></b>" . "</li>";//
                }
                else {
                    echo "<li>" . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=" . $actionFrom . '/' . getPathPiece($filename) . "&cchoice=diff'>" . getPathPiece($filename) . "</a></b>" . "</li>";//
                }
            }
            $i++;
        }
        echo '</td><td>';
        $i = 0;
        foreach($dirsList_date as $filename)
        {
            if ( $i < 5 ) {
                echo @date('F d, Y, H:i:s', filemtime($filename)) . ' <br>';
            }
            $i++;
        }
        echo '</td></tr></table>';
        echo ' <br>';
        echo ' <br>';
    }

    $action_tmp = substr($actionFrom,1);
    $action_list = explode("/", $action_tmp);
    if ( count($action_list) == 2) {
        echo '<h2><center><b>' . $action_list[1] . '</b></center></h2><br>';

    }
    if (!(strpos($url, 'index') !== false)) {
        echo ' <br><b><a href="'.$web_roots.'/index.php">Roots</a></b>';
        echo ' <br><br>';
    }
    if ( count($action_list) == 2) {
        echo '<b> ' . '<a href="' . $web_roots.'/index.php?actionFrom=/' . $action_list[0] . '&cchoice=diff">' . $action_list[0] . '</a></b>' . '<br>';
    }

    if ( $actionFrom != '') {
        echo '<table class="tab0">';
        echo '<tr><td width="20%">';
        echo '<b>Releases</b>';
        echo '</td><td>';
        echo '<b>Last Modified On </b>';
        echo '</td></tr>';

        usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
        //simPrint('$choiceValue', $choiceValue);
        foreach($dirsList_date as $filename)
        {
            if ( $choiceValue != '' ) {
                if ( stristr($filename, $choiceValue) != FALSE ) {
                    $new_path = $filename;
                    echo '<tr><td width="20%">';
                    echo '<b><a href="' . $_SERVER['PHP_SELF'] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff">' . getPathPiece($filename) . '</a></b>' . "\n";
                    echo '</td><td>';
                    echo @date('F d, Y, H:i:s', filemtime($new_path));
                    echo '</td></tr>';
                }
            }
            else {
                $new_path = $filename;
                //simPrint('$new_path', $new_path);
                echo '<tr><td width="20%">';
                echo '<b><a href="' . $_SERVER['PHP_SELF'] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff">' . getPathPiece($filename) . '</a></b>' . "\n";
                echo '</td><td>';
                echo @date('F d, Y, H:i:s', filemtime($new_path));
                echo '</td></tr>';
            }
        }
        echo  '</table>';
    }
    else {
        $tab_Others = array();
        $tab_CMSSW = array();
        $tab_General = array();
        $tab_Keys = array();
        foreach($dirsList_date as $filename_0)
        {
            $filename = getPathPiece($filename_0);
            if ( $choiceValue != '' ) {
                if ( stristr($filename, $choiceValue) != FALSE ) {
                    $nom = explode('_', $filename);
                    $nom_0 = $nom[0];
                    $long = count($nom);
                    if ( $long == 1) {
                        $tab_Others[] = $filename;
                    }
                    else {
                        if ($nom_0 == 'CMSSW') {
                            $tab_CMSSW[] = $filename;
                        }
                        else {
                            if (is_numeric($nom_0[0])) {
                                $tab_General[$nom_0][] = $filename;
                            }
                            else {
                                $tab_Others[] = $filename;
                            }
                        }
                    }
                }
            }
            else {
                $nom = explode('_', $filename);
                $nom_0 = $nom[0];
                $long = count($nom);
                if ( $long == 1) {
                    $tab_Others[] = $filename;
                }
                else {
                    if ($nom_0 == 'CMSSW') {
                        $tab_CMSSW[] = $filename;
                    }
                    else {
                        if (is_numeric($nom_0[0])) {
                            $tab_General[$nom_0][] = $filename;
                        }
                        else {
                            $tab_Others[] = $filename;
                        }
                    }
                }
            }
        }
        foreach($tab_General as $key => $value)
        {
            $tab_Keys[] = $key;
        }

        echo '<div id="accordion">';
            if ( count($tab_Keys) > 0 ) {
                echo '<h3> General case</h3>';
                echo '<div>';
                foreach($tab_General as $key => $value)
                {
                    echo '<div class="cAccordion">';//
                    //echo '<h3><b> ' . $key . '</b> - Last : <a href="' . $web_roots . '/index.php?actionFrom=' . $actionFrom . '/' . $tab_General[$key][0] . '&cchoice=diff">' . $tab_General[$key][0] . '</a></h3>';
                    echo '<h3><b> ' . $key . '</b> - Last : <span class="greenClass">' . $tab_General[$key][0] . '</span></h3>';
                    echo '<div>';
                    displayReleaseDateTitle();
                    foreach($tab_General[$key] as $key2 => $value2) {
                        displayReleaseLinkDate($value2, $chemin_eos, $web_roots, $actionFrom);
                        }
                    echo '</div>';
                    echo '</div>';
                }/**/
                echo '</div>';
            }

            if ( count($tab_CMSSW) > 0 ) {
                echo '<h3> CMSSW case</h3>';
                echo '<div>';
                displayReleaseDateTitle();
                foreach($tab_CMSSW as $item)
                {
                    displayReleaseLinkDate($item, $chemin_eos, $web_roots, $actionFrom);
                }
                echo '</div>';
            }
            
            if ( count($tab_Others) > 0 ) {
                echo '<h3> Others cases</h3>';
                echo '<div>';
                displayReleaseDateTitle();
                foreach($tab_Others as $item)
                {
                    displayReleaseLinkDate($item, $chemin_eos, $web_roots, $actionFrom);
                }/**/
                echo '</div>';
            }
        echo '</div>';
    }
    echo '<br>';

} // end of folders list web page construction

?>

<script>
    var text_values = <?php echo json_encode($textValues);  ?>;
    var lineHisto1 = <?php echo json_encode($lineHisto1);  ?>;
    var url = <?php echo json_encode($url); ?>;
    var url0 = <?php echo json_encode($url_0); ?>;
    var url1 = <?php echo json_encode($url_1); ?>;
    var viewSelectedPath = <?php echo json_encode($viewSelectedPath); ?>;
    var img_add = <?php echo json_encode($image_add); ?>;
    var img_remove = <?php echo json_encode($image_remove); ?>;
</script>

<script>
$(document).ready(function(){
  $("p").click(function(){
    if ( $('[valInfo="t12"]').html() != '' ) {
        $('[valInfo="t12"]').html('');
    }
    else {
        $('[valInfo="t12"]').html(text_values);
    }
  });
});
</script>

<script>
    // jQuery
    $('#accordion').accordion({
        active: false,
        collapsible: true,
        heightStyle: "content"
    });
    $('.cAccordion').accordion({
        active: false,
        collapsible: true,
        heightStyle: "content"
    });
    $('#accordionHeader').accordion({
        active: false,
        collapsible: true,
        heightStyle: "content",
    });
</script>

<script>
    $(document).ready(function(){
        // la class clickable est appliquée à tous les table qui auront des "boutons"
        $('table.clickable td').on('click', checkAddLink );
        console.log('general nous voilà !');
        var nb = lineHisto1.length;
        //console.log('nb : ' + nb);
        if ((nb == 1) && (lineHisto1[0] == '')) {
            $('[soCol="bleu"]').html('<font color="blue">Unselect All</font>');
        }
        else { // if ( nb >= 1 ) 
            var nb2 = nb - 0;
            $('[soCol="bleu"]').html('<font color="red"><b>Unselect All (' + nb2 + ')</b></font>');
            $('[soCol="visio"]').show();
        }
        }
);

    function checkAddLink() {
        if ($(this).parents('table.clickable').hasClass('addLink')) {
            $('table.addLink td').removeClass('Gras');//
            addLink($(this));
            //console.log("addLink");
        }
        if ($(this).parents('table.clickable').hasClass('selectPictFormat')) {
            $('table.selectPictFormat td').removeClass('Gras');//
            selectPictFormat($(this));
            //console.log("selectPictFormat");
        }
    }
    function addLink(obj) {
        var cc = obj.attr('addlink-choice');
        var affiche = 'cc : ' + cc;
        var ee = obj.attr('select-choice'); // Unselect All choice
        var affiche2 = 'ee : ' + ee;
        //console.log(affiche2);
        var dd = obj.text();
        var aff = 'dd : ' + dd;
        var id = obj.attr('addlink-id');
        var imageName = 'https:' + obj.attr('addlink-url');
        var aff = 'id : ' + id + ' _ link : ' + imageName;
        console.log(aff);
        console.log('id : ' + id);
        //console.log('url0 : ' + url0);
        //console.log('imageName : ' + imageName);
        var ff = obj.attr('visio');
        var affiche3 = 'ff : ' + ff;
        console.log(affiche3);

        if (typeof ee !== "undefined") {
            //console.log('unselect all');
            var lineHisto3 = [];
            someText = ' - ' + lineHisto1.length + '<br>';
            $('table td ').each(function(index, elt) {
                var t1 = $(this).attr('addlink-id');
                var t2 = $(this).attr('addlink-choice');
                var t3 = 'https:' + $(this).attr('addlink-url');
                if (typeof t1 !== "undefined") {
                    //console.log(t1 + ' - ' + t2);
                    if ( t2 == "remove from basket" ) {
                        $(this).attr('addlink-choice', "add to basket");
                        $(this).html('<img width="32" height="32" src="' + img_add + '" alt="Add"/>');
                        $(this).css('border', "solid 0px blue");
                        lineHisto3.push(t3);
                    }
                }
            });
            //console.log('len lineHisto3 : ' + lineHisto3.length);
            list3 = JSON.stringify(lineHisto3);
            //console.log('list3 : ' + list3);
            lineHisto2 = [];
            lineHisto1 = clearArray(lineHisto1);
            var nb = lineHisto1.length;
            for (let j=0; j<nb; j++) {
                //console.log(j + '/' + (nb-1) + ' : ' + lineHisto1[j]);
                if ( !list3.includes(lineHisto1[j]) ) {
                    lineHisto2.push(lineHisto1[j]);
                    //someText += ' - ' + lineHisto1[j] + '<br>';
                }
            };
            lineHisto1 = lineHisto2;/**/
            TableData = JSON.stringify(lineHisto1);
            //console.log('tableData' + TableData);
            $.post(
                url0, 
                {pTableData: TableData},
                ).done(function(returnResult){
                //console.log('OK from url0 !');
                //console.log(returnResult);
                }
                ).fail(function(){
                    console.log('ERROR from index::ee !');
                });
            //someText += nb + '<br>';
            //$('[soDiv="Arghhhhh"]').html(someText);
            $('[soCol="bleu"]').html('<font color="blue">Unselect All</font>');
            $('[soCol="visio"]').hide();
        }
        else if (typeof cc !== "undefined") {
            if (cc.indexOf("add") >= 0) {
                obj.attr('addlink-choice', "remove from basket");
                //obj.html('<font color="red"><b>Remove</b></font>');
                obj.html('<img width="32" height="32" src="' + img_remove + '" alt="Rem"/>');
                $('[addlink-id="'+id+'"] img').css('border', "solid 3px blue");
                //$('[addlink-id="'+id+'"] img').addClass('selected');
                $('[soCol="visio"]').show();
                lineHisto1.push(imageName);
                lineHisto1 = clearArray(lineHisto1);
                var nb = lineHisto1.length;
                //console.log('nb : ' + nb);
                var someText = nb + '<br>';
                for (let j=0; j<nb; j++) {
                    someText += ' - ' + lineHisto1[j] + '<br>';
                }
                //$('[soDiv="Arghhhhh"]').html(someText);
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData},
                    ).done(function(returnResult){
            		//console.log('OK from url0 !');
                    //console.log(returnResult);
                	}
                  	).fail(function(){
                    	console.log('ERROR from index::cc::add !');
                    });
                if ((nb == 1) && (lineHisto1[0] == '')) {
                    $('[soCol="bleu"]').html('<font color="blue">Unselect All</font>');
                }
                else { // if ( nb >= 1 ) 
                    var nb2 = nb - 0;
                    //console.log('nb2 : ' + nb2);
                    $('[soCol="bleu"]').html('<font color="red"><b>Unselect All (' + nb2 + ')</b></font>');
                }

            }
            else if (cc.indexOf("remove") >= 0) {
                //console.log("remove");
                //$('[addlink-choice="remove from basket"]').attr('addlink-choice', "add to basket");
                obj.attr('addlink-choice', "add to basket");
                //obj.html('<font color="blue"><b>Add</b></font>');
                obj.html('<img width="32" height="32" src="' + img_add + '" alt="Add"/>');
                $('[addlink-id="'+id+'"] img').css('border', "solid 0px blue");
                $('[soCol="visio"]').hide();
                
                lineHisto1 = $.grep(lineHisto1, function(value) {
                    return value != imageName;
                });
                var lineHisto2 = [];
                lineHisto1 = clearArray(lineHisto1);
                var nb = lineHisto1.length;
                var someText = nb + '<br>';
                for (let j=0; j<nb; j++) {
                    if (lineHisto1[j] != imageName) {
                        lineHisto2.push(lineHisto1[j]);
                        someText += ' - ' + lineHisto1[j] + '<br>';
                    }
                }
                lineHisto1 = lineHisto2;
                //$('[soDiv="Arghhhhh"]').html(someText);
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData},
                    ).done(function(returnResult){
            		//console.log('OK from url0 !');
                    //console.log(returnResult);
                	}
                  	).fail(function(){
                    	console.log('ERROR from index::cc::remove !');
                    });
                if (((nb == 1) && (lineHisto1[0] == '')) || (nb == 0)) {
                    $('[soCol="bleu"]').html('<font color="blue">Unselect All</font>');
                }
                else { // if ( nb >= 1 ) 
                    var nb2 = nb - 0;
                    //console.log('nb2 : ' + nb2);
                    $('[soCol="bleu"]').html('<font color="red"><b>Unselect All (' + nb2 + ')</b></font>');
                }

            }
        }
        else if (typeof ff !== "undefined") {
            $(location).attr('href',viewSelectedPath);
        }
    }
    function clearArray(tab) {
        if ( tab[0] == '' ) {
            console.log('vide');
            tab.shift();
        }
        return tab;
    }
    function selectPictFormat(obj) {
        //console.log("selectPictFormat");
        var gg = obj.attr('pictFormat');
        var affiche = 'gg : ' + gg;
        //console.log(affiche)
        if (typeof gg !== "undefined") {
            //$('#'+code).addClass('Gras');
            //$('[curve-choice="' + cc + '"]').addClass('Gras');
            if (gg == 'gif') {
                //console.log('gif')
                $('[pictFormat="gif"]').addClass('Gras')
            }
            else if (gg == 'png') {
                //console.log('png')
                $('[pictFormat="png"]').addClass('Gras')
                //$('[pictFormat="png"]').html('<font color="blue"><b>png</b></font>')
            }
            var pictFormat = JSON.stringify(gg);
            //console.log("pict format : " + pictFormat);
            $.post(
                    url1, 
                    {pictFormat: pictFormat},
                    ).done(function(returnResult){
            		//console.log('OK from url1 !');
                    //console.log('result : '+returnResult);
                    $(location).attr('href',url);
                	}
                  	).fail(function(){
                    	console.log('ERROR from index::gg !');
                    });
        }
    }
/**/
</script>

</main>

<?php include('footer.php'); ?>


</body>
</html>
