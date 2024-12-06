<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
<title>releases list webpage</title>
<link rel="stylesheet" href="../php_inc/styles.css">
<script src="../js/jQuery-3.6.3/jquery-3.6.3.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.min.css">
<link rel="stylesheet" href="../js/jquery-ui.theme.min.css">
<link rel="stylesheet" href="../js/jquery-ui.structure.min.css">

<!-- the modification of img style (img.anchor) is a precious help of M. Mellin ! -->
<script>
    function onclick_evt(value) {
        document.cookie = "hiName =" + value;
        var hiName = value;
        $('[id="tableauNG"]').hide();
    };
    </script>
</head>

<body>
<div class="sticky">    
    <?php include('checkKSNG_Header.php'); ?>
</div>
<main>
<?php

if (array_key_exists('hiName', $_COOKIE)) {
    $hiName = $_COOKIE['hiName'];
}
else {
    $hiName = '';
}
$_SESSION["hiName"] = $hiName;

$boul1 = $cumul0Flag && $n0Flag && $gifFlag ;
$boul2 = $cumulativeFlag || $KS_ttlDiffFlag ;
if ( $boul1 ) { // histos web page construction
    $histosFileName12 = file($chemin_eos . "/" . $histosFileName); // seems not used

    $handle_2 = fopen($chemin_eos . "/" . $histosFileName, "r");
    if ($handle_2)
    {
        while(!feof($handle_2))
        {
            $lineHisto[] = fgets($handle_2); // read the ElectronMC**Histos**.txt file
        }
        fclose($handle_2);
    }
    else {
        echo 'no histo file' . '<br>';
    }/**/

    //echo "--<br>";
    if ($DBoxflag) {
        $fileDiffpValueName = $chemin_eos . '/pValuesDiffHistosNames.txt'; // pbm to be corrected
        $handle_4 = fopen($fileDiffpValueName, "w");
    }

    echo '<div id="tableauNG" style="border:0px solid black;">';
    echo '<table class="tab1 clickable histos">' . "\n";
    for ($ic = 0; $ic < count($clefs); $ic++) {
        //echo $ic . '<br>';
        $aaa = $ic % 5;
        if ( $aaa == 0 ) {
            echo "\n<tr>";
        }
        $textToWrite = "";
        echo '<td class="b2"><b> ' . $clefs[$ic] . '</b>';
        $titleShortName = titleShortName($clefs[$ic]);
        echo "&nbsp;&nbsp;" . "\n" . "<a href=\"#" . $ic . "\">" ; // write group title $titleShortName
        echo '<img class=" clickable imgTest2" width="18" height="15" src=' . $image_point . ' alt="Top">' . ' <br><br>';
        $textToWrite .= "</a>";
        $histoPrevious = "";
        $numLine = 0;
    
        $j = 0;
        foreach ($histoArray[$clefs[$ic]] as $elem) {
            $otherTextToWrite = "";
            list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem); 
            list ($after, $before, $common) = testExtension($short_histo_name, $histoPrevious);
            $classColor = "lightGrey";
            $filehistoName = $chemin_eos . "/" . $short_histo_name . '.txt'; // . 'DBox/' 
            //echo $filehistoName . "<br>";
            $display_dataset = FALSE;
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
                $lR = explode('KS 3', $lRead2)[1];
                $lR = str_replace("pValue : ","",$lR);
                $lRead2 = substr($lR, -8);
                //echo htmlentities($lRead1). " - " . htmlentities($lRead2) . "<br>";
                $tempDiff = $short_histo_name . ' ' . $lRead1 . $lRead2 . "\n"; // . ' '
                $tempDiff = str_replace(array("\r", "\n"), '', $tempDiff);
                $tempDiff .= "\n";
                if ($handle_4) {
                    fwrite($handle_4, $tempDiff);
                }
                $classColor = getClassColor_cchoice($cchoice, $lRead1, $lRead2);
                $display_dataset = TRUE;
            }
            if ( $elem == "endLine" ) {
                $otherTextToWrite .= " <br>";
            }
            elseif ( $histo_positions[3] == "0" ) {
                if ($numLine == 0) {
                    if ($display_dataset) {
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $ic . '-' . $j . '" class="' . $classColor . '" onclick="onclick_evt(\'' . $short_histo_name . '\');" id="'. $short_histo_name . '">' . $short_histo_name . '</a>' . ' &nbsp;';
                    }
                    $common = $short_histo_name;
                    $numLine += 1;
                }
                else { // $numLine > 0
                    if ($display_dataset) {
                        if ( $after == "" ) {
                            $otherTextToWrite .= ' &nbsp;<a href="#' . $ic . '-' . $j . '" class="' . $classColor . '" onclick="onclick_evt(\'' . $short_histo_name . '\');" id="'. $short_histo_name . '">' . $before . '</a>' . ' &nbsp;';
                        } 
                        else{ // $after != ""
                            $otherTextToWrite .= ' &nbsp;<a href="#' . $ic . '-' . $j . '" class="' . $classColor . '" onclick="onclick_evt(\'' . $short_histo_name . '\');" id="'. $short_histo_name . '">' . $after . '</a>' . ' &nbsp;';
                        }
                    }
                    $common = $before;
                }
            }
            else { //$histo_positions[3] == "1"
                if ($numLine == 0) {
                    if ($display_dataset) {
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $ic . '-' . $j . '" class="' . $classColor . '" onClick="onclick_evt(\'' . $short_histo_name . '\');" id="'. $short_histo_name . '">' . $short_histo_name . '</a>' . ' &nbsp;';
                    }
                    $common = $short_histo_name;
                }
                else { // $numLine > 0
                    if ($display_dataset) {
                        if ( $after == "" ) {
                            $otherTextToWrite .= ' &nbsp;<a href="#' . $ic . '-' . $j . '" class="' . $classColor . '" onclick="onclick_evt(\'' . $short_histo_name . '\');" id="'. $short_histo_name . '">' . $before . '</a>' . ' &nbsp;';
                        }
                        else{ // $after != ""
                            $otherTextToWrite .= ' &nbsp;<a href="#' . $ic . '-' . $j . '" class="' . $classColor . '" onclick="onclick_evt(\'' . $short_histo_name . '\');" id="'. $short_histo_name . '">' . $after . '</a>' . ' &nbsp;';
                        }
                    }
                }
                $numLine = 0;
                $j += 1;
            }
    
            $histoPrevious = $common;
    
            $otherTextToWrite = str_replace("<br><br>", "<br>", $otherTextToWrite);
            $textToWrite .= $otherTextToWrite ;
        }
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

    fclose($handle_4);

    echo '</div>'; // tableauNG

    echo '<div id="histos" style="border:0px solid black;">';
    $lineFlag = True;
    $names = [];
    $names2 = [];
    for ($i = 0; $i < count($clefs); $i++) {
        echo '<div class="" style="padding-left:20px">';
        echo '<a href="#"><img class="s18 clickable imgTest" src=' . $image_up . ' alt="Top"></a>';
        $titleShortName = titleShortName($clefs[$i]);
        echo '<a id="' . $i . '" class="anchor"></a>';
        echo $clefs[$i] . '</b></div>';
        $j = 0;
        echo '<div class="" style="padding-left:20px">';
        echo '<table border="0"><tr>';
        foreach ($histoArray[$clefs[$i]] as $elem) {
            if ( $elem != "endLine" ) {
                list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem);
                $names[$short_histo_name] = $short_histo_names[0];
                $new_escaped_url = str_replace("/Dev/", "/Store/KS_Curves/", $old_escaped_url); //echo 'new escaped url :' . $new_escaped_url . "<br>";
                if (($curveChoice == 'classic') || ($curveChoice == '')){
                    $picture_name = $short_histo_names[0] . '.gif';
                }
                if ($curveChoice == 'ttlDiff'){
                    $picture_name = 'KS-ttlDiff_3_' . $short_histo_names[0] . '.png';
                }
                if ($curveChoice == 'comp'){
                    $picture_name = 'KSCompHisto_' . $short_histo_names[0] . '.png';
                }
                //echo $short_histo_names[0] . ' - ' . $short_histo_name . '<br>';
                $new_escaped_url_eos = str_replace($racine_html, $racine_eos, $new_escaped_url);
                $picture_name_eos = $new_escaped_url_eos . '/' . $picture_name;
                $picture_name = $new_escaped_url . '/' . $picture_name; //echo 'picture name :' . $picture_name . "<br>";
                if (file_exists($picture_name_eos)) {
                    
                    if ( $lineFlag ) {
                        echo '<a id="' . $i . "-" . $j . '" class="anchor"></a>';
                        $names2[$i . "-" . $j] = $short_histo_name;
                        //echo '<td class="VtextAlign" width="50">';
                        echo '<a href="#"><img class="s18 clickable imgTest" src=' . $image_up . ' alt="Top"></a>';
                        //echo '</td>';
                    }
                    
                    if (  $histo_positions[3] == "0" ) {
                        echo '<td class="VtextAlign">';
                        echo $short_histo_name . '<br>';
                        echo '<a href="' . $web_roots . '/valKS.php?actionFrom=' . $actionFrom . '&url=' . $picture_name;
                        echo '&curveChoice=' . $curveChoice . '&short_histo_name=' . $short_histo_name . '&cchoice=' . $cchoice;
                        echo '">';
                        echo '<img class="img" width="300" src="' . $picture_name . '" alt="" id="' . $short_histo_name . '_1"></a>';
                        $hiName = $short_histo_name;
                        echo '</td>';
                        echo "\n";
                        $lineFlag = False;
                    }
                    else { // line_sp[3]=="1"
                        echo '<td class="VtextAlign">';
                        echo $short_histo_name . '<br>';
                        echo '<a href="' . $web_roots . '/valKS.php?actionFrom=' . $actionFrom . '&url=' . $picture_name;
                        echo '&curveChoice=' . $curveChoice . '&short_histo_name=' . $short_histo_name . '&cchoice=' . $cchoice;
                        echo '">';
                        echo '<img class="img" width="300" src="' . $picture_name . '" alt="" id="' . $short_histo_name . '_1"></a>';
                        $hiName = $short_histo_name;
                        echo '</td>';
                        echo '</tr></table>';
                        $lineFlag = True;
                        $j += 1;
                        echo '</div>';
                        echo '<div class="" style="padding-left:20px">';
                        echo '<table border="0"><tr>';
                    }
                }
            }
        }
    }

    echo '<br><br><br>';
    echo '</div>'; // histos

}
elseif ($checkFlag) {
    echo '<table class=\"tab4\" border=\"1\"><tr>'; // width=\"20%\"
    echo "<td class=\"CtextAlign\"><b>histo name</b></td>";
    echo "<td class=\"CtextAlign\"><b>" . $text . "</b></td>";
    echo "</tr>\n";
    for ($i = 0; $i < $nb_Check; $i++) {
            $fName = $chemin . '/' . $CheckList[$i];
            //echo htmlspecialchars_decode($fName) . "<br>";
            $hName = str_replace('line-ttlDiff_', '', $CheckList[$i]);

            $hName = str_replace('.png', '', $hName);
            $hName = substr($hName, 3);
            $hName = str_replace('950_', '', $hName);
            $hName = str_replace('1000_', '', $hName);
            echo "<tr>\n";
            echo "<td class=\"CtextAlign\">" . '<br>' . $hName . "</td>";
            echo "<td class=\"CtextAlign\">";
            echo '<a href="' . $fName . '">';
            echo '<img class="img" width="240" src="' . $fName . '" alt=""></a>' ;//
            $fName2 = str_replace('line-ttlDiff_1_', 'map-ttlDiff_1_', $fName);
            $fName3 = str_replace('line-ttlDiff_1_', 'map-ttlDiff_2_', $fName);
            $fName4 = str_replace('line-ttlDiff_1_', 'map-ttlDiff_3_', $fName);
            echo '<a href="' . $fName2 . '">';
            echo '<img class="img" width="240" src="' . $fName2 . '" alt=""></a>' ;//
            echo '<a href="' . $fName3 . '">';
            echo '<img class="img" width="240" src="' . $fName3 . '" alt=""></a>' ;//
            echo '<a href="' . $fName4 . '">';
            echo '<img class="img" width="240" src="' . $fName4 . '" alt=""></a>' ;//
            echo "</td>";
        echo "</tr>\n";
    }
    echo "</table>" . "<br>";
}
else { // construction of folders list web page
    //echo 'action end page : ' . $actionFrom . '<br>';
    $checkPath = $chemin . '/Check';
    $checkPath_eos = str_replace($racine_html, $racine_eos, $checkPath);
    if (is_dir($checkPath_eos)) {
        echo '<br>';
        echo "KS check folder : " . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=" . $actionFrom . '/Check' ."'><span class=\"greenClass\">"."Check"."</span></a></b>";//
        echo '<br>';
    }

    echo "<p>List of the 5 last releases ";
    usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
    //echo '( here <b><span class="redClass">' . $dirsList_date[0] .'</span> and <span class="blueClass">' . $dirsList_date[1] .'</span></b> folders).</p>';//
    
    echo '<table class="tab4">';
    echo "<tr><td width=\"50%\">";
    echo "<b>Releases";
    echo "</td><td width=\"50%\">";
    echo "<b>Last Modified On ";
    echo "</td></tr>\n";

    $i = 0;
    echo "<td>\n";
    foreach($dirsList_date as $filename) // $dirsList_date
    {
        if ($filename !== 'Check') {
            if ( $i < 5 ) {
                echo '<tr><td>';
                if ( $i == 0 ) {
                    echo "<li>" . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=".$actionFrom.'/'. $filename ."'><span class=\"redClass\">".$filename."</span></a></b>" . "</li>";//
                    }
                elseif ( $i ==1 ) {
                    echo "<li>" . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=".$actionFrom.'/'. $filename ."'><span class=\"blueClass\">".$filename."</span></a></b>" . "</li>";//
                }
                else {
                    echo "<li>" . "<b><a href='$_SERVER[PHP_SELF]?actionFrom=".$actionFrom.'/'. $filename ."'>".$filename."</a></b>" . "</li>";//
                }
                $i++;
                echo '</td><td>';
                $new_path = $chemin_eos . '/' . $filename;
                echo @date('F d, Y, H:i:s', filemtime($new_path)) . ' <br>';
                echo '</td></tr>';
            }
        }
    }
    echo '</table>';
    echo ' <br>';

    $action_tmp = substr($actionFrom,1);
    $action_list = $t1 = explode("/", $action_tmp);
    /*if (!(strpos($url, 'index') !== false)) {
        echo ' <br><b><a href="'.$web_roots.'/checkKSNG.php">Roots</a></b>';
        echo ' <br><br>';
    }*/
    if ( count($action_list) == 2) {
        echo '<h2><center><b>' . $action_list[1] . '</b></center></h2><br>';
        echo '<b> ' . '<a href="' . $web_roots.'/index.php?action=/' . $action_list[0] . '">' . $action_list[0] . '</a></b>' . '<br>';//
    }

    echo '<table class="tab0">';
    echo '<tr><td width="20%">';
    echo '<b>Releases</b>';
    echo '</td><td>';
    echo '<b>Last Modified On </b>';
    echo '</td></tr>';

    usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
    foreach($dirsList_date as $filename) // $dirsList_date
    {
        if ($filename !== 'Check') {
            $aff = true;
            if ( $aff ) {
                if ( $choiceValue != '' ) {
                    if ( stristr($filename, $choiceValue) != false ) {
                        $new_path = $chemin_eos . '/' . $filename;
                        echo $new_path . '<br>' . "\n";
                        echo '<tr><td width="20%">';
                        echo '<b><a href="' . $_SERVER[PHP_SELF] . '?actionFrom=' . $actionFrom . '/' . $filename . '">' . $filename . '</a></b>' . "\n";//
                        echo '</td><td>';
                        echo @date('F d, Y, H:i:s', filemtime($new_path));
                        echo '</td></tr>';
                    }
                }
                else {
                    $new_path = $chemin_eos . '/' . $filename;
                    echo $new_path . '<br>' . "\n";
                    echo '<tr><td width="20%">';
                    echo '<b><a href="' . $_SERVER[PHP_SELF] . '?actionFrom=' . $actionFrom . '/' . $filename . '">' . $filename . '</a></b>' . "\n";//
                    echo '</td><td>';
                    echo @date('F d, Y, H:i:s', filemtime($new_path));
                echo '</td></tr>';/**/
                }
            }
            else {echo "aff = false";}
        }
    }
    echo  '</table>';
    echo '<br>';
    echo ' <br>';

    // TEMP : affichage de dirsDevList_date
    // create an equivalent globos path
    $egp0 = 'https://cms-egamma.web.cern.ch/validation/Electrons/Dev/index.php';
if ( $actionFrom == '' ) {
        echo '<table class="tab0">';
        echo '<th><td colspan=\"3\" class=\"CtextAlign\"><b>Older KS cases</b><br>============</td></tr>';
        echo '<tr><td width="20%">';
        echo '<b>Releases</b>';
        echo '</td><td width="20%">';
        echo '<b>Sub releases </b>';
        echo '</td><td>';
        echo '<b>Last Modified On </b>';
        echo '</td></tr>';

        $fn0 = '';
        foreach($dirsDevList_date as $filename) // $dirsList_date
        {
            
            echo '<tr><td width="20%">';
            if ($filename[0] !== $fn0) {
                echo $filename[0] . "\n";
            }
            echo '</td><td>';
            $egp = $egp0 . '?action=/' . str_replace('CMSSW_', '', $filename[0]) . '/' . $filename[1];
            $egp .= '/RECO-RECO_ZEE_14&cchoice=diff#';
            echo '<b><a href="' . $egp . '">' . str_replace('FullvsFull_', '', $filename[1]) . '</a></b>' . "\n";
            //echo '<b><a href="' . $egp . '">' . $filename[1] . '</a></b>' . "\n";
            echo '</td><td>';
            echo @date('F d, Y, H:i:s', $filename[2]);
            $fn0 = $filename[0];
            echo '</td></tr>';/**/
        }
        echo  '</table>';
        echo '<br><br>';

    }
} // end of folders list web page construction

?>

<script>
    var prefix = <?php echo json_encode($new_escaped_url); ?>;
    var histoNames = <?php echo json_encode($names); ?>;
    var histoNames2 = <?php echo json_encode($names2); ?>;
    var text_values = <?php echo json_encode($textValues); ?>;
    var url = <?php echo json_encode($url); ?>;
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
    $(document).ready(function(){
        // la class clickable est appliquée à tous les table qui auront des "boutons"
        $('table.clickable td').on('click', checkCurveChoice );
        $('span.clickable').on('click', checkHisto );
        $('img.clickable').on('click', checkImg );
    });
    function checkCurveChoice() {
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('curveChoice')) {
            $('table.curveChoice td').removeClass('Gras');//
            curveChoice($(this));
        }
    }
    function checkHisto() {
        console.log("testHisto");
        if ($(this).hasClass('histoTest')) {
            console.log("histoChoice");
            var ee = $(this).attr("id");
            //console.log(ee);
            console.log('https:' + url + '#' + ee);
            //$('[id="tableauNG"]').hide();
            $(location).attr('href','https:' + url + '#' + ee);
        }
        else {
            console.log('KO');
        }
    }
    function checkImg() {
        console.log("testImg");
        if ($(this).hasClass('imgTest')) {
            //curveChoice($(this));
            console.log("testImg");
            $('[id="tableauNG"]').show();
        }
        else if ($(this).hasClass('imgTest2')) {
            //curveChoice($(this));
            console.log("testImg2");
            $('[id="tableauNG"]').hide();
        }
        else {
            console.log('KO');
        }
        var dd = $(this).attr("id");
        console.log(dd);
    }
    function curveChoice(obj){
        var fragment = extractFragment();
        console.log('fragment 1 : ' + fragment);
        var shistoName = histoNames2[fragment];console.log('toto 1 : ' + shistoName);
        var histoName = histoNames[shistoName];console.log(histoName);
        var cc = obj.attr('curve-choice');
        var cc_all = obj.attr('curve-choice-all');
        var affiche = 'cc : ' + cc + ' - cc_all : ' + cc_all;
        console.log(affiche)
        var picture_name = '';
        var dictPictName_1 = {
            classic: [prefix + '/', '.gif'],
            ttlDiff: [prefix + '/KS-ttlDiff_3_', '.png'],
            //cumul: [prefix + '/cumulative_curve_', '.png'],
            comp: [prefix + '/KSCompHisto_', '.png'],
            clAll: [prefix + '/', '.gif'],
            ttAll: [prefix + '/KS-ttlDiff_3_', '.png'],
            //cuAll: [prefix + '/cumulative_curve_', '.png'],
            coAll: [prefix + '/KSCompHisto_', '.png'],
        };
        
        if (typeof cc !== "undefined") {
            if (cc == '') {
                cc = 'classic';
            }
            $('[curve-choice="' + cc + '"]').addClass('Gras');
            var picture_name = dictPictName_1[cc][0] + histoName + dictPictName_1[cc][1];
            $('#'+shistoName+'_1').attr('src', picture_name);
            var affiche = '[curve-choice="' + cc + '"]';
            console.log(affiche)
        }
        
        if (typeof cc_all !== "undefined") {
            $('[curve-choice-all="' + cc_all + '"]').addClass('Gras');
            //var last_item=$('div.cell img.img').length-1
            var last_item=$('table td img.img').length-1
            console.log('nb : '+ last_item)
            //$('div.cell img.img').each(function(index, elt) { 
            $('table td img.img').each(function(index, elt) { 
                console.log('ALL : ' + $(this).attr('id'));
                var aa = $(this).attr('id').slice(0,-2) ;
                console.log(histoNames[aa]);
                var picture_name = dictPictName_1[cc_all][0] + histoNames[aa] + dictPictName_1[cc_all][1];
                $('#'+aa+'_1').attr('src', picture_name);
                if (index == last_item) {
                    console.log('#' + extractFragment());
                    //$($('div.cell img.img')[last_item]).on("load", function() { $('html,body').animate({scrollTop: $('#' + extractFragment()).offset().top}, 0) });
                    $($('table td img.img')[last_item]).on("load", function() { $('html,body').animate({scrollTop: $('#' + fragment).offset().top}, 0) });
                }
            })
        }

    }
    function getCurrentURL() {
        return window.location.href
    }
    function extractFragment() {
        var u = getCurrentURL();
        return u.split('#')[1];
    }

</script>

</main>

<?php include('checkKS_footer.php'); ?>


</body>
</html>
