<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Dev list webpage</title>
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
/*echo '====<br>' . "\n";
prePrint('SESSION', $_SESSION);
echo '====<br>' . "\n";*/

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

    /* Write the TABLE with all histos */
    if ( $url_flag ) {
        echo '<div id="tableHistos" class="parent" style="border:1px solid black;display:none;">';
    }
    else {
        echo '<div id="tableHistos" class="parent" style="border:1px solid black;display:block;">';
    }
    echo '<table class="tab6">';
    for ($ic = 0; $ic < count($clefs); $ic++) {
        $aaa = $ic % 5;
        if ( $aaa == 0 ) {
            echo "\n<tr>";
        }
        $textToWrite = "";
        echo '<td class="b2"><b> ' . $clefs[$ic] . '</b>';
        echo '&nbsp;&nbsp;' . "\n" . '<a href="#' . $ic . '" onclick="goToHisto()">' ; // write group title
        echo "<img width=\"18\" height=\"15\" src=" . $image_point . " alt=\"Top\">" . " <br><br>";
        $textToWrite .= "</a>";
        $histoPrevious = "";
        $numLine = 0;
        $jc = 0;
        $kc = 0;

        foreach ($histoArray[$clefs[$ic]] as $elem) {
            $otherTextToWrite = "";
            list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem); 

            list ($after, $before, $common) = testExtension($short_histo_name, $histoPrevious);
            $classColor = "blueClass";
            if ($DBoxflag) {
                $filehistoName = $chemin_eos . "/" . 'DBox/' . $short_histo_name . '.txt';
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
            $ImageName = 'https:' . $escaped_url . "/" . $pictsValue ."/" . $short_histo_names[0] . $pictsExt;
            $ImageName = str_replace($racine_html, $racine_eos, $ImageName);//simPrint('image', $ImageName);
            if (file_exists($ImageName)) {
                $classColor = $classColor;
            } else {
                $classColor = "lightGreyClass";
                //echo $ImageName."<br>";
            }/**/

            if ( $elem == "endLine" ) {
                $otherTextToWrite .= " <br>";
                $jc += 1;
                $kc = 0;
            }
            elseif ( $histo_positions[3] == "0" ) {
                if ($numLine == 0) {
                    $otherTextToWrite .= ' &nbsp;<a href="#' . $ic.$jc . '0" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $short_histo_name . '</a>' . " &nbsp;";//.$ic.$jc.$kc
                    $common = $short_histo_name;
                    $numLine += 1;
                }
                else { // $numLine > 0
                    if ( $after == "" ) {
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $ic.$jc . '0" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $before . '</a>' . " &nbsp;";//.$ic.$jc.$kc
                    }
                    else{ // $after != ""
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $ic.$jc . '0" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $after . '</a>' . " &nbsp;";//.$ic.$jc.$kc
                    }
                    $common = $before;
                }
                $kc += 1;
            }
            else { //$histo_positions[3] == "1"
                if ($numLine == 0) {
                    $otherTextToWrite .= ' &nbsp;<a href="#' . $ic.$jc . '0" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $short_histo_name . '</a>' . " &nbsp;";//.$ic.$jc.$kc
                    $common = $short_histo_name;
                }
                else { // $numLine > 0
                    if ( $after == "" ) {
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $ic.$jc . '0" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $before . '</a>' . " &nbsp;";//.$ic.$jc.$kc
                    }
                    else { // $after != ''
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $ic.$jc . '0" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $after . '</a>' . " &nbsp;";//.$ic.$jc.$kc
                    }
                }
                $numLine = 0;
                $kc += 1;
            }

            $histoPrevious = $common;

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
    echo '</div>';
    
    $lineFlag = True;
    /* Write the HISTOS pictures */
    echo '<div id="listeHistos" class="parent" style="border:0px solid green;display:block;text-align: center;">';
    displayAllHistos($histoArray, $clefs, $lineHisto1, $escaped_url, $pictsValue, $pictsExt, $histoSize, $url_flag, $code, 'Dev');
    echo '</div>'; // listeHistos
    echo '<br><br><br><br>';

} // end of web page construction of histos
else { // construction of folders list web page
    echo '<div id="part1" class="parent" style="border:0px solid blue;float:left;text-align: center;width: 45%;">';
    $temp = substr($web_roots,6) . "/index.php";
    /*if ( $url == $temp ) { // test for root url
        echo "<p>List of the 5 last releases candidates ";
        usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
        echo '( here <b><span class="redClass">' . $dirsList_date[0] .'</span> and <span class="blueClass">' . $dirsList_date[1] .'</span></b> folders).</p>';//

        echo '<table class="tab5 clickable folders">';
        echo "<tr><td width=\"50%\">";
        echo "<b>Last release candidates";
        echo "</td><td width=\"50%\">";
        echo "<b>Last Modified On ";
        echo "</td></tr><tr>\n";
    
        $i = 0;
        echo '<td>' . "\n";
        foreach($dirsList_date as $filename)
        {
            if ( $i < 5 ) {
                $link1 = $_SERVER["PHP_SELF"] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff';
                if ( $i == 0 ) {
                    echo "<li>" . '<b><a href="' . $link1 . '"><span class="redClass">' . getPathPiece($filename) . '</span></a></b>' . '</li>';//
                    }
                elseif ( $i ==1 ) {
                    echo "<li>" . '<b><a href="' . $link1 . '"><span class="blueClass">' . getPathPiece($filename) . '</span></a></b>' . '</li>';//
                }
                else {
                    echo "<li>" . '<b><a href="' . $link1 . '">' . getPathPiece($filename) . '</a></b>' . '</li>';//
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
    }*/

    if ( $actionFrom != '') {
        echo '<br>';
        echo '<b>Release candidate : </b>';
        echo '<span class="blueClass"><b>' . $action_list[0] . '</b></span>';
        echo '<br><br>';
        
        echo '<table class="tab0">';
        echo '<tr><td style="width=:20%">';
        echo '<b>Release references</b>';
        echo '</td><td>';
        echo '<b>Last Modified On </b>';
        echo '</td></tr>';

        usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
        foreach($dirsList_date as $filename)
        {
            if ( $choiceValue != '' ) {
                if ( stristr($filename, $choiceValue) != FALSE ) {
                    $new_path = $filename;
                    echo '<tr><td style="width=:20%">';
                    echo '<b><a href="' . $_SERVER['PHP_SELF'] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff">' . getPathPiece($filename) . '</a></b>' . "\n";
                    echo '</td><td>';
                    echo @date('F d, Y, H:i:s', filemtime($new_path));
                    echo '</td></tr>';
                }
            }
            else {
                $new_path = $filename;
                echo '<tr><td style="width=:20%">';
                echo '<b><a href="' . $_SERVER['PHP_SELF'] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff">' . getPathPiece($filename) . '</a></b>' . "\n";
                echo '</td><td>';
                echo @date('F d, Y, H:i:s', filemtime($new_path));
                echo '</td></tr>';
            }
        }
        echo  '</table>';
    }
    else {
        //simPrintC('actionFrom', $actionFrom); // actionFrom = ''
        //echo "len : " . gettype($actionFrom) . $_fDL;
        //if ( $url == $temp ) { // test for root url
            echo "<p>List of the 5 last releases candidates ";
            usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
            echo '( here <b><span class="redClass">' . $dirsList_date[0] .'</span> and <span class="blueClass">' . $dirsList_date[1] .'</span></b> folders).</p>';//

            echo '<table class="tab5 clickable folders">';
            echo "<tr><td width=\"50%\">";
            echo "<b>Last release candidates";
            echo "</td><td width=\"50%\">";
            echo "<b>Last Modified On ";
            echo "</td></tr><tr>\n";
        
            $i = 0;
            echo '<td>' . "\n";
            foreach($dirsList_date as $filename)
            {
                if ( $i < 5 ) {
                    $link1 = $_SERVER["PHP_SELF"] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff';
                    if ( $i == 0 ) {
                        echo "<li>" . '<b><a href="' . $link1 . '"><span class="redClass">' . getPathPiece($filename) . '</span></a></b>' . '</li>';//
                        }
                    elseif ( $i ==1 ) {
                        echo "<li>" . '<b><a href="' . $link1 . '"><span class="blueClass">' . getPathPiece($filename) . '</span></a></b>' . '</li>';//
                    }
                    else {
                        echo "<li>" . '<b><a href="' . $link1 . '">' . getPathPiece($filename) . '</a></b>' . '</li>';//
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
        //}
    }
    echo '</div>'; // part1

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

    echo '<br>';
    echo '<div id="part2" class="parent" style="border:0px solid green;float:right;text-align: center;width: 54%;">';
    if ($l_actionFrom == 1){
        echo "<p>List of all releases " . "<br>";
        echo 'here the <b>General case</b> release is a CMSSSW and <b>Others cases</b> not.</p>';//
    }
    /*if ( $actionFrom != '') {
        echo '<br>';
        echo '<b>Release candidate : </b>';
        echo '<span class="blueClass"><b>' . $action_list[0] . '</b></span>';
        echo '<br><br>';
        
        echo '<table class="tab0">';
        echo '<tr><td style="width=:20%">';
        echo '<b>Release references</b>';
        echo '</td><td>';
        echo '<b>Last Modified On </b>';
        echo '</td></tr>';

        usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
        foreach($dirsList_date as $filename)
        {
            if ( $choiceValue != '' ) {
                if ( stristr($filename, $choiceValue) != FALSE ) {
                    $new_path = $filename;
                    echo '<tr><td style="width=:20%">';
                    echo '<b><a href="' . $_SERVER['PHP_SELF'] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff">' . getPathPiece($filename) . '</a></b>' . "\n";
                    echo '</td><td>';
                    echo @date('F d, Y, H:i:s', filemtime($new_path));
                    echo '</td></tr>';
                }
            }
            else {
                $new_path = $filename;
                echo '<tr><td style="width=:20%">';
                echo '<b><a href="' . $_SERVER['PHP_SELF'] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff">' . getPathPiece($filename) . '</a></b>' . "\n";
                echo '</td><td>';
                echo @date('F d, Y, H:i:s', filemtime($new_path));
                echo '</td></tr>';
            }
        }
        echo  '</table>';
    }
    else {*/
    if ( $actionFrom == '') {
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
    echo '</div>'; // part2

} // end of folders list web page construction

/*echo '====<br>' . "\n";
prePrint('SESSION', $_SESSION);
echo '====<br>' . "\n"*/

?>

<script>
    var text_values = <?php echo json_encode($textValues);  ?>;
    var lineHisto1 = <?php echo json_encode($lineHisto1);  ?>;
    var url = <?php echo json_encode($url); ?>;
    var url0 = <?php echo json_encode($url_0); ?>;
    var url1 = <?php echo json_encode($url_1); ?>;
    var url2 = <?php echo json_encode($url_2); ?>;
    var url4 = <?php echo json_encode($url_4); ?>;
    var web_roots_KS = <?php echo json_encode($web_roots_KS . '/main_display_KS.php'); ?>;
    var viewSelectedPath = <?php echo json_encode($viewSelectedPath); ?>;
    var img_add = <?php echo json_encode($image_add); ?>;
    var img_remove = <?php echo json_encode($image_remove); ?>;
    var web_path = <?php echo json_encode($web_roots . '/basket.php?'); ?>;
    var Transf = <?php echo json_encode($Transf); ?>;
</script>

<script> // t12
$(document).ready(function(){ // p=t12
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

<script> // accordion
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

<script> // addLink
    $(document).ready(function(){
        // la class clickable est appliquée à tous les table qui auront des "boutons"
        $('table.clickable td').on('click', checkAddLink );
        $('table.clickable td').on('click', checkTable ); // TEMP
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
        if ($(this).parents('table.clickable').hasClass('folders')) {
            //$('table.selectPictFormat td').removeClass('Gras');//
            folders($(this));
            //console.log("folders");
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
        var gg = obj.attr('img_id')
        console.log('=== img_id= ' + gg)

        if (typeof gg !== "undefined") {
            //console.log('=== img_id= ' + gg)
            dataSession = JSON.stringify(gg);
            //$("#listeHistos").hide();
            $.post(
                url2, 
                {dataS: dataSession},
                ).done(function(returnResult){
                console.log('OK from url2 !');
                console.log(returnResult);
                }
                ).fail(function(){
                    console.log('ERROR from index::gg !');
                });
                $(location).attr('href', web_path + gg);
        }
        else if (typeof ee !== "undefined") {
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
                        $(this).html('<img width="32" height="32" src="' + img_add + '" alt="Add">');
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
                obj.html('<img width="32" height="32" src="' + img_remove + '" alt="Rem">');
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
                obj.html('<img width="32" height="32" src="' + img_add + '" alt="Add">');
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
    function folders(obj) {
        console.log('folders')
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
    function checkTable() { // TEMP
        if ($(this).parents('table.clickable').hasClass('Table')) {
            var cc2 = $(this).attr('id');
            console.log('cc2 : ' + cc2)
            $("#tableHistos").toggle();
        }
    }
</script>

<script> // gotoHisto
    function goToHisto(valeur) {
        //console.log('goToHisto ' )
        console.log('valeur='+valeur)
        if (typeof valeur !== "undefined") {
            $('div.cell img.image.img').each(function(index, elt) {
                //console.log($(this).attr('id'));
                var bb = $(this).attr('id').slice(0,-2) ;
                //console.log(histoNames[aa]);
                $('#'+bb+'_1').css('border', "solid 2px blue");
            })
            var aa = valeur.slice(0,-2)
            console.log('aa='+aa)
            $('#'+aa+'_1').css('border', "solid 3px red");
        }
        else {
            $('div.cell img.image.img').each(function(index, elt) {
                var bb = $(this).attr('id').slice(0,-2) ;
                $('#'+bb+'_1').css('border', "solid 2px blue");
            })
        }
        if ($('#tableHistos').is(":hidden")) {
            $('#tableHistos').show()
        }
        else {
            $('#tableHistos').hide()
        }
    }
</script>

<script> // gotoTable
    function goToTable(valeur) {
        console.log('goToTable ' )
        $('div.cell img.image.img').each(function(index, elt) {
            var cc = $(this).attr('id').slice(0,-2) ;
            $('#'+cc+'_1').css('border', "solid 2px blue");
        })
        $('#tableHistos').show()
    }
</script>

<script> // KS click
    function KS_Evclick() {
        console.log(Transf)
        var transfert = {run: Transf[0], operation: Transf[1], dataSet: Transf[2], precision: Transf[3], buttons: ''};
        console.log(transfert)
        Transfert = JSON.stringify(transfert);
        $.post(
            url4, 
            {boldSelection: Transfert},
            ).done(function(returnResult){
            console.log('OK from url4 !');
            console.log(returnResult);
            }
            ).fail(function(){
                console.log('ERROR from url4 !');
        });
        $(location).attr('href', web_roots_KS);/**/
    }
</script>

</main>

<?php include('footer.php'); ?>


</body>
</html>
