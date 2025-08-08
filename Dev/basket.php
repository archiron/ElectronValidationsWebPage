<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Dev list webpage</title>
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

echo '====<br>' . "\n";
prePrint('SESSION', $_SESSION);
echo '====<br>' . "\n";
/**/

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
    if (strpos($url_http, $_SESSION['pictFormat']) !== false) {
        $tmp = explode('_', $ref, 2);//prePrint("ref", $tmp);
        $ref = $tmp[1];
        echo '<b><span>' . $tags . '</span></b>' . '<br>';
        echo '<b><span class="redClass">' . $ref . '</span></b>' . ' - ';
        echo '<b><span class="blueClass">' . $new . '</span></b>' . '<br>' . $_fDL;
        echo '<a id="' . $short_histo_name  . '" name="' . $short_histo_name  . '"';
        echo ' href="' . $url_http . '"><img border="0" class="image" width="480" src="' . $url_http . '" id="displayHisto"></a>' . "\n";
    }
    echo "</td>";

    echo "<td>";

    /* Test if url_http exist into the $lineHisto array */
    $testExistUrl = false;
    foreach ($lineHisto as $key => $value) {
        if ( $value == $url_http ) {
            $testExistUrl = true;
        }
    }
    echo "<br>";
    echo '<table border="1" width = "100" class="clickable addLink">';//
    echo '<tr>';// valign=\"top\"
    if ( $testExistUrl) {
        echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" width="60"><img width="32" height="32" src="' . $image_remove . '" alt="Rem"/></td>';
    }
    else {
        echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/></td>';
    }
    echo "</tr>";
    echo  "</table>";
    echo "<br><br><br>";

    echo "<br><div>";
    echo "&nbsp;<a href=\"$web_roots/basket.php?basket=work&actionFrom=" . $actionFrom . "\">Manage the links</a>&nbsp;" . "\n";
    echo "<br></div>";

    echo "</td>";

    echo '<td class="CtextAlign">';
    echo '<span class="darkBlueClass" style="font-size:150%; " id="Liste"><b>List of releases for comparison</b></span>' . $_fDL;
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
        $temp = [];
        foreach ($files1 as $key2 => $value2)
        {
            $path1 = $path0 . DIRECTORY_SEPARATOR . $value2;
            if (is_dir($path1))
            {
                foreach(array('gifs', 'pngs') as $value6) {
                    $histoName1 = explode('.', $histoName)[0];
                    $pictsExt = substr($value6, 0, 3);//simPrint('ext', $pictsExt);
                    $path2 = $path1 . DIRECTORY_SEPARATOR . $tags . DIRECTORY_SEPARATOR . $value6;
                    if (is_dir($path2)) {
                        $listDir2[] = $path2;
                        if (file_exists($path2 . DIRECTORY_SEPARATOR . $histoName1 . "." . $pictsExt)) {
                            $listPict[] = $path2 . DIRECTORY_SEPARATOR . $histoName1 . "." . $pictsExt;
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
    $timestamp = time();
    $dateString = date($format, $timestamp);
    echo "Fin du calcul : " . $dateString . $_fDL;

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
    $workLink = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name  . "&basket=work&actionFrom=" . $actionFrom ;
    $aFrom = explode("/", $actionFrom);
    $refLink = $web_roots . '/index.php?actionFrom=' . $actionFrom;
    $displayAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name  . "&basket=display&actionFrom=" . $actionFrom;
    $sharedAddress = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=work&actionFrom=" . $actionFrom . "&sharedF=" . getReducedName($_SESSION['fileForHistos_eos']);

    if ($url == '') {
        $url = $sharedAddress;
    }

    error_reporting(E_ALL);
    $lineHisto = array_filter($lineHisto);
    $text_0 = "[0] " . $refLink . "\n\n";

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
        $value2 = str_replace($racine_html . 'validation/Electrons/', '', $value);
        $parts = explode("/", $value2); # so, there is 6 parts
        $histoName = substr($parts[5], 0, -4);
        $compAnddataset = explode("_", $parts[3], 2);
        echo '<td align="center">' . sprintf('%02d', $key + 1) . ' <input type="checkbox" onchange="checkFunction2()" name="choix[]" id="' . sprintf('%02d', $key + 1);
        echo '" value="' . $key ;
        if (count($checked) >= 1) {
            if ($checked[$key] == '1') {
                echo '" checked="checked"';
            }
        }/**/
        echo '" />' . "</td>\n";
        echo "<td align=\"center\">" . $compAnddataset[0] . "</td>\n"; // comparison (RECO vs RECO, PU vs PU, ..)
        echo "<td align=\"center\">" . $compAnddataset[1] . "</td>\n"; // dataset (ZEE, TTbar, ..)
        echo "<td align=\"center\">" . $histoName . "</td>\n";
        
        if (strpos($url, $parts[1]) !== false)
        {
            echo "<td align=\"center\"><font color=\"blue\">" . $value . "</font>";
        }
        else {
            echo "<td align=\"center\"><font color=\"darkgrey\">" . $value . "</font>";
        }
        echo "</td>\n";
        echo "</tr>\n";
    }
    echo  "</table>\n";

    echo '<br>';
    echo '<table border="1" cellpadding="5" class="clickable buttonChoice">';
    echo '<tr><td class="CtextAlign" button-choice="line0" title="Click on text to add it on textArea" id="line0">';
    echo 'Release link : ' . $text_0 ;
    echo "</td></tr>";
    echo  "</table>\n";

    echo '<br>';
    echo '<table style="border:1px solid black;" cellpadding="5" class="clickable actionChoice">';
    echo '<tr>';
    echo '<td id="selectAll" style="border:1px solid black;width:180px" class="CtextAlign">Select/UnSelect all links</td>' . "\n"; 
    echo '<td id="removeAll" style="border:1px solid black;width:130px" class="CtextAlign">Remove all links</td>' . "\n"; 
    echo '<td id="removeSelected" style="border:1px solid black;width:160px" class="CtextAlign">Remove selected links</td>' . "\n"; 
    echo '<td id="copySelected" style="border:1px solid black;width:130px" class="CtextAlign">Copy selected links</td>' . "\n";
    echo '<td style="border:1px solid black;width:130px" class="CtextAlign">&nbsp;</td>' . "\n";
    echo '<td id="shareFile" style="border:1px solid black;width:130px" class="CtextAlign"><font color="grey">Select histos for sharing</font></td>' . "\n";
    echo '<td style="border:1px solid black;width:130px" class="CtextAlign">&nbsp;</td>' . "\n";
    echo '<td style="border:1px solid black;width:130px" class="CtextAlign">' . '<a href="' . $displayAddr . '">Display histos</a>' . '</td>' . "\n";
    echo "</tr>";
    echo  "</table>";

    echo "&nbsp;&nbsp;<font color=\"blue\">Please, note that the <b>remove</b> function act on the file</font> <b><font color=\"red\">AND NOT ONLY</font></b> <font color=\"blue\">on this webpage ! </font><br>" ;
    echo '<br>';

    echo '<textarea name="message_content" cols="100" rows="10" class="contentfont" id="textArea">' . $text . '</textarea>' . "<br>\n"; # 

    echo '<br>';
        echo '<label style="border:2px solid red;display:none" id="sharedAddress">Shared address : </label>';
    echo '<br>';

    $returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom;
    $pos = strpos($url, 'index.php');
    if ($pos === false)
    {
        $returnAddr = $returnAddr;
    }
    else {
        $returnAddr = $url;
    }

    echo '<table style="margin:0 auto;border:1px solid black;" cellpadding="5" width="30%">';
    echo '<tr valign="top">';
    echo '<td align="center" style="margin:0 auto;border:1px solid black;"><font color="black"><b>shared files to use</b></font></td>';
    echo '</tr>';

    //prePrint('sharedFilesList', $sharedFilesList);
    foreach ($sharedFilesList as $key => $value) {
        $name = $chemin_eos_base . '/' . $value;
        if (file_exists($name) && (filesize($name) !== 0)) {
            echo '<tr valign="top"><td align="center" style="margin:0 auto;border:1px solid black;">';
            //echo '[' . $key . '] := ' . $name . ' (' . filesize($name) . ')' . $_fDL;
            $reducedValue = str_replace('sharedList.', '', $value);
            $reducedValue = str_replace('.txt', '', $reducedValue);
            // recompute actionFrom
            $handleBasket = fopen($name, "r");
            $tmp_aF0 = fgets($handleBasket);
            $tmp_aF0 = str_replace(array("\r", "\n"), '', $tmp_aF0);
            fclose($handleBasket);

            $tmp_aF1 = str_replace($web_roots, '', $tmp_aF0);
            $tmp_aF2 = explode('/',$tmp_aF1);
            $tmp_aF3 = '/' . $tmp_aF2[1] . '/' . $tmp_aF2[2] . '/' . $tmp_aF2[3];
            //simPrint('aF1', $tmp_aF3);

            $address = $web_roots . "/basket.php?actionFrom=" . $tmp_aF3 . "&sharedF=" . $reducedValue . '&basket=work';
            //echo '[' . $key . '] := ' . $address . $_fDL;
            //echo '<a href="' . $address . '">sharedList.' . $reducedValue . '.txt</a>';
            $tmp_aF4 = explode('.', $reducedValue)[1];
            $tmp_AF5 = substr($tmp_aF4, 0, 8);//simPrint('$tmp_AF5', $tmp_AF5);
            $tmp_AF6 = substr($tmp_aF4, 8);//simPrint('$tmp_AF5', $tmp_AF6);
            echo '<a href="' . $address . '">' . $tmp_AF5 . ' - ' . $tmp_AF6 . '</a>';
            echo '</td></tr>';
        }
    }
    echo  '</table>' . "\n";
    echo $_fDL . $_fDL;
}
elseif ($basket == "display") {
    $returnAddr = $web_roots . "/basket.php?basket=work&actionFrom=" . $actionFrom;
    $returnAddr2 = $web_roots . "/index.php?actionFrom=" . $actionFrom . "#";
    
    $lineHisto = array_filter($lineHisto);
    echo "<h2><center><b><font color=red>shared histos display</font></b></center></h2><br>";

    $i=0;
    echo "<table border=\"1\" cellpadding=\"5\" width=\"100%\">";
    foreach ($lineHisto as $key => $value) {
        $value2 = substr($value, 52);
        $parts = explode("/", $value2); # so, there is 6 parts

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

?>

<script>
    var text_0 = <?php echo json_encode($text_0); ?>;
    var text_values = <?php echo json_encode($textValues); ?>;
    var lineHisto1 = <?php echo json_encode($lineHisto); ?>;
    var url0 = <?php echo json_encode($url_0); ?>;
    var url5 = <?php echo json_encode($url_5); ?>;
    var url6 = <?php echo json_encode($url_6); ?>;
    var url7 = <?php echo json_encode($url_7); ?>;
    var tags = <?php echo json_encode($tags); ?>;
    var histoName1 = <?php echo json_encode($histoName1); ?>;
    var origin = <?php echo json_encode($origin); ?>;
    var webRoots = <?php echo json_encode($web_roots); ?>;
    var urlhttp = <?php echo json_encode($url_http); ?>;
    var newUrl = <?php echo json_encode($newUrl); ?>;
    var actionFrom = <?php echo json_encode($actionFrom); ?>;
    var shareFileName = <?php echo json_encode(getSharedFileName(session_id())); ?>;
    var img_add = <?php echo json_encode($image_add); ?>;
    var img_remove = <?php echo json_encode($image_remove); ?>;
</script>

<script> // t12
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

<script> // check buttons, size, releases, ..
    $(document).ready(function(){
        // la class clickable est appliquée à tous les table qui auront des "boutons"
        $('table.clickable td').on('click', checkButtonChoice );
        $('table.clickable td').on('click', checkSizeChoice );
        $('table.clickable td').on('click', checkAddLink );
        $('table.clickable td').on('click', checkReleases );
        $('table.clickable td').on('click', checkActionChoice );
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
        if (typeof cc !== "undefined") {
            if (cc.indexOf("add") >= 0) {
                obj.attr('addlink-choice', "remove from basket");
                //obj.html('<font color="red"><b>Remove</b></font>');
                obj.html('<img width="32" height="32" src="' + img_remove + '" alt="Rem"/>');
                lineHisto1.push(imageName);
                var nb = lineHisto1.length;
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData, selLink2: "remove&nbsp;from&nbsp;basket"}, //
                    ).done(function(returnResult){
            		//console.log('OK from url0 !');
                    console.log(returnResult);
                	}
                  	).fail(function(){
                    	console.log('ERROR from basket::cc::add !');
                    });
            }
            else {
                //console.log("remove");
                obj.attr('addlink-choice', "add to basket");
                obj.html('<img width="32" height="32" src="' + img_add + '" alt="Add"/>');
                lineHisto1 = $.grep(lineHisto1, function(value) {
                    return value != imageName;
                });
                var lineHisto2 = [];
                var nb = lineHisto1.length;
                for (let j=0; j<nb; j++) {
                    if (lineHisto1[j] != imageName) {
                        lineHisto2.push(lineHisto1[j]);
                    }
                }
                lineHisto1 = lineHisto2;
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
            text = $("textarea#textArea").val()
            $("textarea#textArea").val(text_0 + text);
        }
        else {
            $('[button-choice="line1"]').attr('button-choice', "line0");
            var selected = [];
            $("input:checkbox:checked").each(function() {
                selected.push($(this).attr('id'));
                $(this).prop( "checked", true )
            });
            var text = ''
            if ($('#line0').hasClass('Gras')) {
                text = $('#line0').text().split(" : ")[1]
                //textA = textA.replace('[0]', '[00]')
            }
            var i_c = 1
            selected.forEach(element => {
                text += '[' + i_c + '] ' + lineHisto1[element-1] + "\n"
                i_c += 1
            });
            $("textarea#textArea").val(text);
        }/**/

    }
    function checkActionChoice() {
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('actionChoice')) {
            actionChoice($(this));
        }
    }
    function actionChoice(obj){
        var bc = obj.attr('id');
        var affiche = 'bc : ' + bc;
        console.log(affiche);
        var i_c = 0
        if (typeof bc !== "undefined") {
            if (bc == 'selectAll') {
                console.log('select All')
                if ($('#selectAll').hasClass('Gras')) {
                    $('#selectAll').removeClass('Gras');
                    $("input[type='checkbox']").each(function() {
                        $(this).prop( "checked", false )
                    });
                    var textA = ''
                    if ($('#line0').hasClass('Gras')) {
                        textA = $('#line0').text().split(" : ")[1]
                        textA = textA.replace('[0]', '[00]')
                    }
                    $('textarea#textArea').val(textA)
                    $('#shareFile').html('<font color="grey">Select histos for sharing</font>')
                }
                else {
                    $('#selectAll').addClass('Gras');
                    $("input[type='checkbox']").each(function() {
                        $(this).prop( "checked", true )
                    });
                    $('#shareFile').html('Share selected links')
                }
            }
            else if (bc == 'removeAll') {
                console.log('remove All')
                stayingAddresses = JSON.stringify([]);
                var destination = webRoots + '/basket.php?actionFrom=' + actionFrom + '&basket=work'
                $.post(
                    url5, 
                    {basketFile: stayingAddresses},
                    ).done(function(returnResult){
                    //console.log('OK from url5 !');
                    console.log(returnResult);
                    $(location).attr('href',destination);
                    }
                    ).fail(function(){
                        console.log('ERROR from basket::bc::removeAll !');
                    });/**/
            }
            else if (bc == 'removeSelected') {
                console.log('remove Selected')
                var unselected = [];
                //var i_compte = 0
                $("input:checkbox:not(:checked)").each(function() { // get unchecked checkboxes
                    unselected.push(lineHisto1[$(this).attr('id')-1]);
                });
                if (unselected.length !== lineHisto1.length) {
                    stayingAddresses = JSON.stringify(unselected);
                    var destination = webRoots + '/basket.php?actionFrom=' + actionFrom + '&basket=work'
                    $.post(
                        url5, 
                        {basketFile: stayingAddresses},
                        ).done(function(returnResult){
                        //console.log('OK from url5 !');
                        console.log(returnResult);
                        $(location).attr('href',destination);
                        }
                        ).fail(function(){
                            console.log('ERROR from basket::bc::removeSelected !');
                        });/**/
                }
                else {
                    console.log('meme valeur : ' + unselected.length + ', rien a retirer')
                }
            }
            else if (bc == 'copySelected') {
                var selected = [];
                $("input:checkbox:checked").each(function() {
                    selected.push($(this).attr('id'));
                    $(this).prop( "checked", true )
                });
                var textA = ''
                if ($('#line0').hasClass('Gras')) {
                    textA = $('#line0').text().split(" : ")[1]
                    //textA = textA.replace('[0]', '[00]')
                }
                var i_c = 1
                selected.forEach(element => {
                    textA += '[' + i_c + '] ' + lineHisto1[element-1] + "\n"
                    i_c += 1
                });
                $('textarea#textArea').val(textA)
            }
            else if (bc == 'shareFile') {
                var selected = [];
                $("input:checkbox:checked").each(function() {
                    selected.push($(this).attr('id'));
                    $(this).prop( "checked", true )
                });
                if (selected.length >= 1) {
                    var ListA = []
                    selected.forEach(element => {
                        ListA.push(lineHisto1[element-1])
                    });
                    var destination = webRoots + '/basket.php?actionFrom=' + actionFrom + '&basket=work'
                    console.log(destination)
                    sharedText = JSON.stringify([webRoots, shareFileName, ListA]);
                    console.log('sharedText = ' + sharedText)
                    $.post(
                        url6, 
                        {sharedFile: sharedText},
                        ).done(function(returnResult){
                        //console.log('OK from url6 !');
                        console.log(returnResult);
                        //$(location).attr('href',destination);
                        }
                        ).fail(function(){
                            console.log('ERROR from basket::zc::sharedFile !');
                        });
                    var tmp1 = shareFileName.replace('sharedList.', '').replace('.txt', '')
                    //'<a href="' . $address . '">sharedList.' . $redValue . '.txt</a>';
                    var addr = 'https://cms-egamma.web.cern.ch/validation/Electrons/Dev/basket.php?actionFrom=' + actionFrom
                    addr += '&sharedF=' + tmp1 + '&basket=work'                
                    //var tmp2 = '<font color="blue">https://cms-egamma.web.cern.ch/validation/Electrons/Dev/basket.php?actionFrom=' + actionFrom
                    //tmp2 += '&sharedF=' + tmp1 + '&basket=work' + '</font>'
                    var tmp2 = '<a href="' + addr + '">' + addr + '</a>'
                    $('#sharedAddress').html('Shared address : ' + tmp2)
                    $('#sharedAddress').show()
                }
                else {
                    ;
                }
            }
        }
    }

    function checkSizeChoice() {
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('sizeChoice')) {
            $('table.sizeChoice td').removeClass('Gras');//
            sizeChoice($(this));
            console.log("sizeChoice");
        }
    }
    function sizeChoice(obj){
        var cc = obj.attr('size-choice');
        //var affiche = 'cc : ' + cc;
        //console.log(affiche);
        //alert(cc);
        
        if (typeof cc !== "undefined") {
            $('[size-choice="' + cc + '"]').removeClass('Gras');
            if (cc == '') {
                cc = 480;
            }
            $('[size-choice="' + cc + '"]').addClass('Gras');
            $('#displayHisto').attr('width', cc);
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
                $('#displayHistos').toggle();
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

<script> // checked
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
                var name = webRoots + '/' + tmp1[0] + '/' + tags + '/' + tmp1[1] + 's/' + histoName1 + '.' + tmp1[1];
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

<script> // checked2
    function checkFunction2() {
        //var select2 = [];
        var nb = 0
        $('input:checked').each(function() {
            //select2.push($(this).attr('id'));
            nb += 1
        });
        if (nb >= 1) {
            $('#shareFile').html('Share selected links')
        }
        else { // 
            $('#shareFile').html('<font color="grey">Select histos for sharing</font>')
        }
    }
</script>

<script> // display/remove Releases array
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
