<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>releases list webpage</title>
<link rel="stylesheet" href="../php_inc/styles.css">
<script src="../js/jQuery-3.6.3/jquery-3.6.3.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.min.css">
<link rel="stylesheet" href="../js/jquery-ui.theme.min.css">
<link rel="stylesheet" href="../js/jquery-ui.structure.min.css">
</head>

<body>
<div class="sticky">    
    <?php include('basket_header.php'); ?>
</div>
<main>
<?php

if ($basket == "view") {
    $returnAddr = $web_roots .  "/index.php?action=" . $actionFrom . "#" . $short_histo_name ;
    $workLink = "basket.php?short_histo_name=" . $short_histo_name  . "&basket=view&actionFrom=" . $actionFrom;
    if (isset($_GET['local'])) {
        backToLocal();
    }

    echo '<table border=1 style="border-color:blue">';
    echo "<tr>";
    echo "<td>";

    $parts = explode('/', $actionFrom);
    $new = $parts[1];
    $ref = $parts[2];
    $tags = $parts[3];
    $origin = $new . DIRECTORY_SEPARATOR . $ref;
    //simPrint('origin', $origin);
    //if (strpos($url_http, "gif") !== false) {
    if (strpos($url_http, $_SESSION['pictFormat']) !== false) {
        $tmp = explode('_', $ref, 2);//prePrint("ref", $tmp);
        $ref = $tmp[1];
        echo '<b><span>' . $tags . '</span></b>' . '<br>';
        echo '<b><span class="redClass">' . $ref . '</span></b>' . ' - ';
        echo '<b><span class="blueClass">' . $new . '</span></b>' . '<br>' . $_fDL;
        //simPrint('rr', $parts[3]); // get the comp (i.e. RECO vs RECO) & dataset (i.e. ZEE)
        echo '<a id="' . $short_histo_name  . '" name="' . $short_histo_name  . '"';
        echo ' href="' . $url_http . '"><img border="0" class="image" width="480" src="' . $url_http . '" id="displayHisto"></a>' . "\n";
    }
    echo "</td>";

    echo "<td>";

    /* Test if url_http exist into the $lineHisto array */
    $testExistUrl = false;
    foreach ($lineHisto as $key => $value) {
        if ( $value == $url_http ) {
            //echo "exist<br>";
            $testExistUrl = true;
        }
    }
    echo "<br>";
    echo '<table border="1" width = "100" class="clickable addLink">';//
    echo '<tr>';// valign=\"top\"
    if ( $testExistUrl) {
        //echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" width="60"><font color="red"><b>Remove</b></font></td>';
        echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" width="60"><img width="32" height="32" src="' . $image_remove . '" alt="Rem"/></td>';
    }
    else {
        //echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" width="60"><font color="blue"><b>Add</b></font></td>';
        echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/></td>';
    }
    echo "</tr>";
    echo  "</table>";
    echo "<br><br><br>";

    echo "<br><div>";
    echo "<a href=\"$web_roots/basket.php?short_histo_name=" . $short_histo_name   . "&basket=work&actionFrom=" . $actionFrom . "\">Manage the links</a>" . "\n";
    echo "<br></div>";

    echo "<br><div>";
    echo "<a href=\"$web_roots/basket.php?short_histo_name=" . $short_histo_name   . "&basket=share&actionFrom=" . $actionFrom . "\">Create - Use a shared file</a>" . "\n";
    echo "&nbsp;- &nbsp;";
    echo "<a href=\"$web_roots/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom . "&local=true" . "\">Use local file</a>" . "\n";
    echo "<br></div>";

    echo "</td>";

    echo '<td class="CtextAlign">';
    echo '<span class="darkBlueClass" style="font-size:150%; " id="Liste"><b>List of releases for comparison</b></span>' . $_fDL;
    //filter($url, $image_loupe);
    $listDir0 = array();
    $listDir1 = array();
    $listDir2 = array();
    $listPict = array();

    foreach ($files as $key => $value)
    {
        $path0 = $chemin_eos_base . DIRECTORY_SEPARATOR . $value;
        if (is_dir($path0))
        {
            $first = $value[0];
            if (is_numeric($first)) {
                //echo $key . ' : ' . $path0 . $_fDL;
                $listDir0[] = $value;
            }
        }
    }
    rsort($listDir0);
    //prePrint('listDir0', $listDir0); // OK
    $timestamp = time();
    $format = 'd-m-Y H:i:s'; // Format de date et heure souhaité
    $dateString = date($format, $timestamp);
    echo "Début du calcul : " . $dateString . $_fDL;

    $tableau = array();
    foreach ($listDir0 as $key1 => $value1)
    {
        $path0 = $chemin_eos_base . DIRECTORY_SEPARATOR . $value1;
        $listDir1 = [];
        $files1 = array_slice(scandir($path0), 2);
        //echo '<tr><td>' . $value1 . '</td><td>';
        $temp = [];
        foreach ($files1 as $key2 => $value2)
        {
            $path1 = $path0 . DIRECTORY_SEPARATOR . $value2;
            if (is_dir($path1))
            {
                foreach(array('gifs', 'pngs') as $value6) {
                    //echo '==' . $key2 . ' : ' . $value2 . $_fDL;
                    //$path2 = $path1 . DIRECTORY_SEPARATOR . $tags . DIRECTORY_SEPARATOR . 'gifs';
                    $histoName1 = explode('.', $histoName)[0];
                    $pictsExt = substr($value6, 0, 3);//simPrint('ext', $pictsExt);
                    $path2 = $path1 . DIRECTORY_SEPARATOR . $tags . DIRECTORY_SEPARATOR . $value6;
                    //simPrint('path2', $path2);
                    if (is_dir($path2)) {
                        $listDir2[] = $path2;
                        if (file_exists($path2 . DIRECTORY_SEPARATOR . $histoName1 . "." . $pictsExt)) {
                            $listPict[] = $path2 . DIRECTORY_SEPARATOR . $histoName1 . "." . $pictsExt;
                            //$temp[] = $value2;
                            $temp[] = $value2 . "." . $pictsExt;
                        }
                    }
                }
            }
        }
        if (count($temp) > 0) {
            $tableau[$value1] = $temp;
        }
    }
    //prePrint('listdirs2', $listDir2);
    //prePrint('list pict', $listPict);
    //prePrint('tableau', $tableau);
    //simPrint('ref', $ref);echo strlen($ref) . $_fDL;
    //simPrint('new', $new);echo strlen($ref) . $_fDL;
    //$name = $new . DIRECTORY_SEPARATOR . $ref;
    //simPrint('name', $name);
    $timestamp = time();
    $dateString = date($format, $timestamp);
    echo "Fin du calcul" . $dateString . $_fDL;

    echo '<div id="ListeReleases" style="display: none;">';
    echo '<table border=1>';
    echo '<tr><td class="CtextAlign"><b>Release</b></td><td class="CtextAlign"><b>Reference</b></td></tr>';
    foreach ($tableau as $key3 => $value3)
    {
        echo '<tr><td class="LtextAlign">' . $key3;
        echo '</td><td class="LtextAlign">';
        echo '<table border=0 width="100%">';
        foreach ($value3 as $key4 => $value4) {
            $value5 = str_replace('FullvsFull_', '', $value4);
            echo '<tr><td>' . explode(".", $value5)[0] . '</td>';// . $_fDL
            echo '<td width="20px">' . '<input type="checkbox" onchange="checkFunction()" id="' . $key3. DIRECTORY_SEPARATOR . $value4 . '" ';
            if (($ref == explode(".", $value5)[0]) && ($new == $key3)) {
                echo ' checked';
            }
            echo '>' . '</td></tr>' . "\n";
        }
        echo "</table>";

        echo '</td></tr>';
    }
    echo '</table>';

    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo '<br><br><br>';

    echo '</div>'; // ListeReleases
    echo '<div id="displayHistos">';
    echo '</div';
    echo '<br><br><br><br><br><br>';
} 
elseif ($basket == "work") {
    $workLink = "basket.php?short_histo_name=" . $short_histo_name  . "&basket=work&actionFrom=" . $actionFrom ;
    $aFrom = explode("/", $actionFrom);
    $refLink = $web_roots . '/index.php?actionFrom=/' . $aFrom[1] . '/' . $aFrom[2];
    $displayAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name  . "&basket=display&actionFrom=" . $actionFrom;
    $sharedAddress = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=work&actionFrom=" . $actionFrom . "&sharedF=" . getReducedName($_SESSION['fileForHistos_eos']);
    if ($url == '') {
        $url = $sharedAddress;
    }

    echo '<form method="POST" action="'.$workLink.'">';
    error_reporting(E_ALL);
    $lineHisto = array_filter($lineHisto);
    $text_0 = "[0] " . $refLink . "\n\n";
    $text = ''; //$text_0;

    if (!empty($_POST['rmva'])){
        $lineHisto2 = [];
        file_put_contents($_SESSION['fileForHistos_eos'], implode(PHP_EOL, $lineHisto2));
        header( "Location: " . $workLink );
    }
    
    if( !empty($_POST['cpal']) ) {
        //$allSelected = true; // not used
        $checked = array_fill(0, $Nlinks, 1);
    }

    if( !empty($_POST['choix']) )
    {
       $checked = array_fill(0, $Nlinks, 0);
        if( !empty($_POST['copy']) ) {
            foreach($_POST['choix'] as $val)
            {
                //$text = $text . sprintf('[%d]', $val + 1) . " https:" . $lineHisto[$val] . "\n";
                $text = $text . sprintf('[%d]', $val + 1) . " " . $lineHisto[$val] . "\n";
                $checked[$val] = 1;
            }
        }
        if( !empty($_POST['rmvs']) ) {
            $checked2 = array_fill(0, $Nlinks, 1);
            echo "rmvs : " . $_POST['rmvs'] . "<br>\n";
            foreach($_POST['choix'] as $val) {
                $checked2[$val] = 0;
            }
            $ii = 0;
            foreach($lineHisto as $key => $value) {
                if ($checked2[$ii] == 1) {
                    $lineHisto2[] = $value;
                    $ii += 1;
                }
                else {
                    $ii += 1;
                }
            }
            file_put_contents($_SESSION['fileForHistos_eos'], implode(PHP_EOL, $lineHisto2));
            header( "Location: " . $workLink );
        }
    }
    echo '<br />';
    
    echo "<table border=\"1\" cellpadding=\"5\" width=\"100%\">";
    echo "\n<tr valign=\"top\">";
    echo "<td align=\"center\"><font color=\"blue\"><b>link to select</b></font></td>\n";
    echo "<td align=\"center\"><font color=\"blue\"><b>comparison</b></font></td>\n";
    echo "<td align=\"center\"><font color=\"blue\"><b>dataset</b></font></td>\n";
    echo "<td align=\"center\"><font color=\"blue\"><b>histoName</b></font></td>\n";
    echo "<td align=\"center\"><font color=\"blue\"><b>url</b></font></td>\n";
    echo "</tr>\n";
    foreach($lineHisto as $key => $value)
    {
        echo "\n<tr valign=\"top\">";
        //$value2 = substr($value, 46); // classique
        //$value2 = substr($value, 52); // new version
        //simPrint('chemin html : ', $racine_html);
        $value2 = str_replace($racine_html . 'validation/Electrons/', '', $value);
        //simPrint("value1 : " , $value);
        //simPrint("value2 : " , $value2);
        $parts = explode("/", $value2); # so, there is 6 parts
        //prePrint('parts', $parts);
        //simPrint('parts[5] : ', $parts[5]);
        //simPrint('parts[3] : ', $parts[3]);
        $histoName = substr($parts[5], 0, -4);
        $compAnddataset = explode("_", $parts[3], 2);
        //prePrint('compAnddataset', $compAnddataset);
        //simPrint('compAnddataset[0] ', $compAnddataset[0]);
        //simPrint('compAnddataset[1] ', $compAnddataset[1]);
        echo "<td align=\"center\">" . sprintf('%02d', $key + 1) . ' <input type="checkbox" name="choix[]" value="' . $key ;
        if (count($checked) >= 1) {
            if ($checked[$key] == '1') {
                echo "\" checked=\"checked\"" ;
            }
        }/**/
        echo "\">" . "</td>\n";
        echo "<td align=\"center\">" . $compAnddataset[0] . "</td>\n"; // comparison (RECO vs RECO, PU vs PU, ..)
        echo "<td align=\"center\">" . $compAnddataset[1] . "</td>\n"; // dataset (ZEE, TTbar, ..)
        echo "<td align=\"center\">" . $histoName . "</td>\n";
        
        if (strpos($url, $parts[1]) !== false)
        {
            //echo "<td align=\"center\"><font color=\"blue\">" . "https:" . $value . "</font>";
            echo "<td align=\"center\"><font color=\"blue\">" . $value . "</font>";
        }
        else {
            //echo "<td align=\"center\"><font color=\"darkgrey\">" . "https:" . $value . "</font>";
            echo "<td align=\"center\"><font color=\"darkgrey\">" . $value . "</font>";
        }
        echo "</td>\n";
        echo "</tr>\n";
    }
    echo  "</table>\n";

    echo '<br>';
    echo '<table border="1" cellpadding="5" class="clickable buttonChoice">';
    echo '<tr><td class="CtextAlign" button-choice="line0" title="Click on text to add it on textArea">';
    echo 'Release link : ' . $text_0 ;
    echo "</td></tr>";
    echo  "</table>\n";

    echo "<br>";
    echo '    <input type="submit" name="cpal" value="Select/UnSelect all links" >' . "\n"; 
    echo '&nbsp;&nbsp;';
    echo '    <input type="submit" name="rmva" value="Remove all links" ">' . "\n"; 
    echo '&nbsp;&nbsp;';
    echo '    <input type="submit" name="rmvs" value="Remove selected links" >' . "\n"; 
    echo '&nbsp;&nbsp;';
    echo '    <input type="submit" name="copy" value="Copy selected links">' . "\n";

    echo "<br>&nbsp;&nbsp;<font color=\"blue\">Please, note that the <b>remove</b> function act on the file and not only on this webpage ! </font><br>" ;
    
    echo '</form>';
    
    echo "<textarea name=\"message_content\" cols=\"100\" rows=\"10\" class=\"contentfont\" id=\"textArea\">".$text."</textarea>" . "<br>\n"; # 

    $returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom;
    $pos = strpos($url, 'index.php');
    if ($pos === false)
    {
        $returnAddr = $returnAddr;
    }
    else {
        $returnAddr = $url;
    }
    echo "<br><div>\n";
    if(isset($_POST['button1'])) {
        header( "Location: " . $workLink ); 
    }
    echo "<form method=\"post\">";
    echo "<input type=\"submit\" name=\"button1\" class=\"button\" value=\"Refresh\" >";
    echo " &nbsp;&nbsp;&nbsp; ";
    //echo "<a href=\"" . $returnAddr . "\">BACK</a>" . "\n"; 
    //echo "$nbsp - $nbsp\n";
    echo "<a href=\"" . $displayAddr . "\">Display histos</a>" . "\n"; 
    echo "<br></div>\n";

    if (strpos(getReducedName($_SESSION['fileForHistos_eos']), "shared") !== false) {
        echo "<table border=\"1\" cellpadding=\"5\" width=\"100%\">";
        echo "\n<tr valign=\"top\">";
        echo "<td align=\"center\"><font color=\"blue\"><b>link to share</b></font></td>\n";
        echo "</tr>\n";
        echo "\n<tr valign=\"top\">";
        echo "<td align=\"center\">" . $sharedAddress . "</td>\n";
        echo "</tr>\n";
        echo  "</table>\n";
    }
    echo $_fDL . $_fDL;
}
elseif ($basket == "share") {
    $returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name  . "&basket=view&actionFrom=" . $actionFrom;
    #echo "share session : " . $_SESSION['url'] . "<br>\n";
    
    echo "<table border=\"1\" cellpadding=\"5\" width=\"100%\">";
    echo "\n<tr valign=\"top\">";
    echo "<td align=\"center\" width=\"30%\"><font color=\"blue\"><b>Create a shared file</b></font></td>\n";
    echo "<td align=\"center\" width=\"50%\"><font color=\"blue\"><b>Use a shared file</b></font></td>";
    echo "<td align=\"center\" width=\"20%\"><font color=\"blue\"><b>Stay using a local file</b></font></td>";
    echo "</tr>\n";
    echo "\n<tr valign=\"top\">";
    echo "<td align=\"center\" width=\"30%\">";
    echo "<br>";
    echo "<a href=\"$web_roots/basket.php?short_histo_name=" . $short_histo_name   . "&basket=share&actionFrom=" . $actionFrom . "&createF=OK" . "\">Create a shared file</a>" . "\n";
    echo "<br><br>";
    echo "</td>";
    echo "<td align=\"center\" width=\"50%\">";

    foreach($sharedFilesList as $key => $value)
    {
        $sLink = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom . "&sharedF=" . $value;
        echo " <a href=\"" . $sLink . "\">".$value."</a><br>\n";
    }
    
    echo "</td>";
    echo "<td width=\"20%\" align=\"center\">";
    echo "<br>";
    echo "<a href=\"" . $returnAddr . "\">BACK</a>" . "\n"; 
    echo "<br><br>";
    echo "</td>";
    echo "\n<tr valign=\"top\">";
    echo  "</table>\n";
    
}
elseif ($basket == "display") {
    //$returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name . "&basket=view&actionFrom=" . $actionFrom;
    $returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name . "&basket=work&actionFrom=" . $actionFrom . "&url=" . $url;
    $returnAddr2 = $web_roots . "/index.php?actionFrom=" . $actionFrom . "#";// #vertexX
    
    $lineHisto = array_filter($lineHisto);
    #$clefs = array_keys($histoArray);
    echo "<h2><center><b><font color=red>shared histos display</font></b></center></h2><br>";

    $i=0;
    echo "<table border=\"1\" cellpadding=\"5\" width=\"100%\">";
    foreach ($lineHisto as $key => $value) {
        //echo "value1 : " . $value . "<br>\n";
        $value2 = substr($value, 52);
        //echo "value2 : " . $value2 . "<br>\n";
        $parts = explode("/", $value2); # so, there is 6 parts
        //prePrint('parts', $parts);

        if ( $i % 3  == 0 ) {
            echo "\n<tr valign=\"top\">";
        }
        echo "\n<td width=\"10\">\n ";
        if (strpos($url, $parts[1]) !== false)
        {
            echo "<font color=\"blue\">" . "https:" . $parts[1] . "</font>";
        }
        else {
            echo "<font color=\"darkgrey\">" . "https:" . $parts[1] . "</font>";
        }
        //echo "<a href=\"" . "http:" . $value . "\"><img border=\"0\" class=\"image\" width=\"" . "440" . "\" src=\"" . "http:" . $value . "\"></a>" . "\n";
        echo "<a href=\"" . $value . "\"><img border=\"0\" class=\"image\" width=\"" . "440" . "\" src=\"" . $value . "\"></a>" . "\n";

        echo "</td>";
        if ( $i % 3 == 2 ) {
            echo "</tr>";
        }
        $i+=1;
    }
    echo  "</table>\n";
            
    echo "<br><br>";
    echo "<a href=\"" . $returnAddr . "\">BACK to links management</a>" . "\n"; 
    echo "&nbsp; - &nbsp;";
    echo "<a href=\"" . $returnAddr2 . "\">BACK to histos selection</a>" . "\n"; 
    
}
else { # manage the basket
    echo "HOUSTON WE HAVE A BIG PBM !!!"; 
}

echo '<br><br><br><br><br><br>';
?>

<script>
    var text_values = <?php echo json_encode($textValues); ?>;
    var text = <?php echo json_encode($text); ?>;
    var text_0 = <?php echo json_encode($text_0); ?>;
    var lineHisto1 = <?php echo json_encode($lineHisto); ?>;
    var url0 = <?php echo json_encode($url_0); ?>;
    var img_add = <?php echo json_encode($image_add); ?>;
    var img_remove = <?php echo json_encode($image_remove); ?>;
    var listPict = <?php echo json_encode($listPict); ?>;
    var tags = <?php echo json_encode($tags); ?>;
    var webRoots = <?php echo json_encode($web_roots); ?>;
    var urlhttp = <?php echo json_encode($url_http); ?>;
    var histoName1 = <?php echo json_encode($histoName1); ?>;
    var origin = <?php echo json_encode($origin); ?>;
    var tableau = <?php echo json_encode($tableau); ?>;
    var pictsValue = <?php echo json_encode($pictsValue); ?>;
    var newUrl = <?php echo json_encode($newUrl); ?>;
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
        $('table.clickable td').on('click', checkButtonChoice );
        $('table.clickable td').on('click', checkSizeChoice );
        $('table.clickable td').on('click', checkAddLink );
        $('table.clickable td').on('click', checkReleases );
    });

    function checkAddLink() {
        if ($(this).parents('table.clickable').hasClass('addLink')) {
            $('table.addLink td').removeClass('Gras');//
            addLink($(this));
            //console.log("addLink");
        }
    }
    function addLink(obj) {
        var cc = obj.attr('addlink-choice');
        var affiche = 'cc : ' + cc;
        var dd = obj.text();
        var aff = 'dd : ' + dd;
        var id = obj.attr('addlink-id');
        var imageName = obj.attr('addlink-url');
        var aff = 'id : ' + id + ' _ link : ' + imageName;
        //console.log(aff);
        //console.log('url0 : ' + url0);
        //console.log('imageName : ' + imageName);
        if (typeof cc !== "undefined") {
            if (cc.indexOf("add") >= 0) {
                obj.attr('addlink-choice', "remove from basket");
                //obj.html('<font color="red"><b>Remove</b></font>');
                obj.html('<img width="32" height="32" src="' + img_remove + '" alt="Rem"/>');
                lineHisto1.push(imageName);
                var nb = lineHisto1.length;
                /*var someText = nb + '<br>';
                for (let j=0; j<nb; j++) {
                    someText += ' - ' + lineHisto1[j] + '<br>';
                }*/
                //lineHisto1 = lineHisto2;
                //$('[soDiv="Arghhhhh"]').html(someText);
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData, selLink2: "remove&nbsp;from&nbsp;basket"}, //
                    ).done(function(returnResult){
            		//console.log('OK from url0 !');
                    console.log(returnResult);
                    //location.reload(true);
                	}
                  	).fail(function(){
                    	console.log('ERROR from basket::cc::add !');
                    });
            }
            else {
                //console.log("remove");
                //$('[addlink-choice="remove from basket"]').attr('addlink-choice', "add to basket");
                obj.attr('addlink-choice', "add to basket");
                //obj.html('<font color="blue"><b>Add</b></font>');
                obj.html('<img width="32" height="32" src="' + img_add + '" alt="Add"/>');
                lineHisto1 = $.grep(lineHisto1, function(value) {
                    return value != imageName;
                });
                var lineHisto2 = [];
                var nb = lineHisto1.length;
                //var someText = nb + '<br>';
                for (let j=0; j<nb; j++) {
                    if (lineHisto1[j] != imageName) {
                        lineHisto2.push(lineHisto1[j]);
                        //someText += ' - ' + lineHisto1[j] + '<br>';
                    }
                }
                lineHisto1 = lineHisto2;
                //$('[soDiv="Arghhhhh"]').html(someText);
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData, selLink2: "add&nbsp;to&nbsp;basket"},
                    ).done(function(returnResult){
            		//console.log('OK from url0 !');
                    console.log(returnResult);
                	}
                  	).fail(function(){
                    	console.log('ERROR from basket::cc::remove !');
                    });
            }
        }
    }

    function checkButtonChoice() {
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('buttonChoice')) {
            $('table.buttonChoice td').removeClass('Gras');//
            buttonChoice($(this));
            console.log("buttonChoice");
        }
    }
    function buttonChoice(obj){
        var cc = obj.attr('button-choice');
        var affiche = 'cc : ' + cc;
        console.log(affiche);
        //alert(cc);
        if(cc=="line0")     {
            console.log("line0");
            $('[button-choice="line0"]').addClass('Gras');
            $('[button-choice="line0"]').attr('button-choice', "line1");
            $("textarea#textArea").val(text_0 + text);
        }
        else {
            $('[button-choice="line1"]').attr('button-choice', "line0");
            $("textarea#textArea").val(text);
        }/**/

    }

    function checkSizeChoice() {
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('sizeChoice')) {
            $('table.sizeChoice td').removeClass('Gras');//
            sizeChoice($(this));
            //console.log("curveChoice");
        }
    }
    function sizeChoice(obj){
        var cc = obj.attr('size-choice');
        //var affiche = 'cc : ' + cc;
        //console.log(affiche);
        //alert(cc);
        
        if (typeof cc !== "undefined") {
            if (cc == '') {
                cc = 480;
            }
            $('[size-choice="' + cc + '"]').addClass('Gras');
            $('#displayHisto').attr('width', cc);
            //var affiche = '[size-choice="' + cc + '"]';
            //console.log(affiche)
        }/**/
        
    }
    function checkReleases() {
        if ($(this).parents('table.clickable').hasClass('Releases')) {
            var cc2 = $(this).attr('id');
            console.log('cc2 : ' + cc2)
            if ( cc2 == 'Histos' ) {
                var tild = $("#Histos").text();
                if (tild.indexOf("remove") >= 0) {
                    $("#Histos").html('<span class="blueClass"><b>Press here to display Releases array</b></span>');
                }
                else if (tild.indexOf("display") >= 0) {
                    $("#Histos").html('<span class="blueClass"><b>Press here to remove Releases array</b></span>');
                }
                //console.log('tild : ' + tild);
                $("#ListeReleases").toggle();
            }
            else if ( cc2 == 'displayHistosLink' ) {
                $(location).attr('href',newUrl);
            }
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

<script>
    function checkFunction() {
        var select1 = [];
        console.log(tags);
        console.log('#table' + ' input:checked');
        var last_item=$('input:checked').length-1;
        console.log('input ' + last_item);
        $('input:checked').each(function() {
            select1.push($(this).attr('id'));
            //console.log($(this).attr('id'))
        });
        console.log(select1 + ' ' + select1.length);
        var filter = $('#filter').val()
        if (filter != '') {
            console.log('filter : ' + filter)
        }
        var affiche = '';

        if (select1.length >= 1) {
            affiche = '<table border=1><tr>'; //'<img border="0" class="image" width="480" src="' + urlhttp + '">' + "\n";
            //affiche += '<td>' + origin + '<img border="0" class="image" width="480" src="' + urlhttp + '"></td>';
            $.each(select1, function(key, value) {
                tmp1 = value.split(".");
                //console.log(key + ' - ' + tmp1[0]);
                //var name = webRoots + '/' + tmp1[0] + '/' + tags + '/gifs/' + histoName1;
                var name = webRoots + '/' + tmp1[0] + '/' + tags + '/' + tmp1[1] + 's/' + histoName1 + '.' + tmp1[1];
                //console.log("name : " + name)
                //console.log("value : " + tmp1[0])
                affiche += '<td>' + tmp1[0] + '<img border="0" class="image" width="480" src="' + name + '"></td>';
            });
            affiche += '</tr></table><br>';
        }
        $('#displayHistos').html(affiche);

        $('input:not(:checked)').each(function() {
            if ($(this).attr('id') == origin) {
                //console.log($(this).attr('id'))
                $(this).prop( "checked", true )
            }
        });

    }
</script>

<script>
$(document).ready(function(){
  $("#Liste").click(function(){
    var tild = $("#Histos").html();//console.log('histos : '+tild)
    if (tild.indexOf("remove") >= 0) {
        //$("#Histos").html('<span class="blueClass"><b>Press here to remove Releases array</b></span>');
        $("#Histos").html('<span class="blueClass"><b>Press here to display Releases array</b></span>');
    }
    else if (tild.indexOf("display") >= 0) {
        $("#Histos").html('<span class="blueClass"><b>Press here to remove Releases array</b></span>');
        //$("#Histos").html('<span class="blueClass"><b>Press here to display Releases array</b></span>');
    }
    $("#ListeReleases").toggle();
  });
});
</script>

</main>
<?php include('basket_footer.php'); ?>
</body>
</html>
