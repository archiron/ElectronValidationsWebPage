<!DOCTYPE HTML>
<html lang="en">

<?php
// On génère un jeton aléatoire sécurisé (si ce n'est pas déjà fait)
if (!isset($nonce)) {
    $nonce = base64_encode(random_bytes(16));
}
?>

<head>
<meta charset="UTF-8" >
<!--meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'nonce-<?php echo $nonce; ?>' https://jquery.com; style-src 'self' 'nonce-<?php echo $nonce; ?>';" -->
<!-- meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com; style-src 'self' 'unsafe-inline';" -->

<title>Dev list webpage</title>
<link rel="icon" type="image/x-icon" href="/validation/Electrons/img/filetype-root-256.ico"> 
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/all.min.css">
<link rel="stylesheet" href="../js/jQuery-4.0.0/jquery-ui-1.14.2/jquery-ui.min.css">
<script src="../js/jQuery-4.0.0/jquery.min.js"></script>
<script src="../js/jQuery-4.0.0/jquery-ui-1.14.2/jquery-ui.min.js"></script>

</head>

<body>
<?php
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
// --- SECURITY (Haut de page) ---
    include '../php_inc/security.inc.php';

    // 1. Sanitization de l'URL avant toute utilisation
    $actionFrom = cleanInput_V2($_REQUEST['actionFrom'] ?? '', true);  // true autorise les slashes
    $url = cleanInput_V2($_REQUEST['url'] ?? '', true);    // false bloque les slashes
    $site = cleanInput_V2($_REQUEST['site'] ?? '', false);    // false bloque les slashes
    $basket = cleanInput_V2($_REQUEST['basket'] ?? '', false);    // false bloque les slashes
    $fileForHistos = cleanInput_V2($_REQUEST['fileForHistos'] ?? '', false);    // false bloque les slashes

    $url_safe = cleanInput($_GET['redirect'] ?? '', 'url');
    if (!empty($url_safe) && !preg_match('#^https?://#i', $url_safe)) {
        $url_safe = ''; // Fallback si jamais le protocole a été altéré
    }

    // Vérification CRITIQUE anti-traversal
    if (strpos($actionFrom, '..') !== false) {
        die("Chemin invalide : tentative de traversal détectée");
    }

    // Si votre header.php utilise $_REQUEST ou $_GET directement, mettez-les à jour :
    $_REQUEST['actionFrom'] = $actionFrom;
    $_REQUEST['url'] = $url;
    $_REQUEST['basket'] = $basket;
    $_REQUEST['site'] = $site;
    $_REQUEST['redirect'] = $url_safe;

// --- END SECURITY ---

?>
<div class="sticky">    
    <?php include('basket_header.php'); ?>
</div>
<main>
<?php

/*echo '====<br>' . "\n";
prePrint('SESSION', $_SESSION);
echo '====<br>' . "\n";
*/
//prePrint('shared', $sharedFilesList);
//prePrint('files', $filesList);

if ($basket == "view") {
    $returnAddr = $web_roots .  "/index.php?action=" . $actionFrom . "#" . $short_histo_name ;
    $workLink = "basket.php?short_histo_name=" . $short_histo_name  . "&basket=view&actionFrom=" . $actionFrom;
    if (isset($_GET['local'])) {
        backToLocal();
    }

    echo '<table class="blueBorder1">';
    echo '<tr>';
    echo '<td>';

    $parts = explode('/', $actionFrom);
    $new = $parts[1];
    $ref = $parts[2];
    $tags = $parts[3];
    $origin = $new . DIRECTORY_SEPARATOR . $ref;
    //simPrintC('origin', $origin);
    if (strpos($url_http, $_SESSION['pictFormat']) !== false) {
        $tmp = explode('_', $ref, 2);//prePrint("ref", $tmp);
        $ref = $tmp[1];
        echo '<b><span>' . $tags . '</span></b>' . '<br>';
        echo '<b><span class="redClass">' . $ref . '</span></b>' . ' - ';
        echo '<b><span class="blueClass">' . $new . '</span></b>' . '<br>' . $_fDL;
        echo '<a id="' . $short_histo_name  . '" name="' . $short_histo_name  . '"';
        echo ' href="' . $url_http . '" target="_blank" rel="noopener noreferrer"><img class="image w-480px" src="' . $url_http . '" id="displayHisto"></a>' . "\n";
    }
    echo '</td>';

    echo '<td>';

    /* Test if url_http exist into the $lineHisto array */
    $testExistUrl = false;
    foreach ($lineHisto as $key => $value) {
        if ( $value == $url_http ) {
            $testExistUrl = true;
        }
    }
    echo '<br>';
    echo '<table class="clickable addLink w-100px blackBorder1">';//
    echo '<tr>';
    if ( $testExistUrl) {
        echo '<td class="CtextAlign w-60px blackBorder1" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" ><img width="32" height="32" src="' . $image_remove . '" alt="Rem"/></td>';
    }
    else {
        echo '<td class="CtextAlign w-60px blackBorder1" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $url_http . '" ><img width="32" height="32" src="' . $image_add . '" alt="Add"/></td>';
    }
    echo '</tr>';
    echo '</table>';
    echo '<br><br><br>';

    echo '<br><div>';
    echo '&nbsp;<a href="' . $web_roots . '/basket.php?basket=work&actionFrom=' . $actionFrom . '" target="_blank" rel="noopener noreferrer">Manage the links</a>&nbsp;' . "\n";
    echo '<br></div>';

    echo '</td>';

    echo '<td class="CtextAlign blackBorder1">';
    echo '<span class="darkBlueClass text-150pct" id="Liste"><b>List of releases for comparison</b></span>' . $_fDL;
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
                    $pictsExt = substr($value6, 0, 3);//simPrintC('ext', $pictsExt);
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
    simPrint("Fin du calcul", $dateString);

    echo '<div id="ListeReleases hidden">';
    echo '<table border=1>';
    echo '<tr><td class="CtextAlign"><b>Release</b></td><td class="CtextAlign"><b>Reference</b></td></tr>';
    foreach ($tableau as $key3 => $value3)
    {
        echo '<tr><td class="LtextAlign">' . $key3;
        echo '</td><td class="LtextAlign">';
        echo '<table class="blackBorder0 w-100pct">';
        foreach ($value3 as $key4 => $value4) {
            $value5 = str_replace('FullvsFull_', '', $value4);
            echo '<tr><td>' . explode(".", $value5)[0] . '</td>';
            echo '<td class="w-20px">' . '<input type="checkbox" onchange="checkFunction()" id="' . $key3. DIRECTORY_SEPARATOR . $value4 . '" ';
            if (($ref == explode(".", $value5)[0]) && ($new == $key3)) {
                echo ' checked';
            }
            echo '>' . '</td></tr>' . "\n";
        }
        echo '</table>';

        echo '</td></tr>';
    }
    echo '</table>';

    echo '</td>';
    echo '</tr>';
    echo '</table>';
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

    echo '<table class="tab1">';
    echo '<tr class="TtextAlign">';
    echo '<td class="CtextAlign blueClass blackBorder1"><b>link to select</b></td>';
    echo '<td class="CtextAlign blueClass blackBorder1"><b>comparison</b></td>';
    echo '<td class="CtextAlign blueClass blackBorder1"><b>dataset</b></td>';
    echo '<td class="CtextAlign blueClass blackBorder1"><b>histoName</b></td>';
    echo '<td class="CtextAlign blueClass blackBorder1"><b>url</b></td>';
    echo '</tr>';
    foreach($lineHisto as $key => $value)
    {
        echo '<tr class="TtextAlign">';
        $value2 = str_replace($racine_html . 'validation/Electrons/', '', $value);
        $parts = explode("/", $value2); # so, there is 6 parts
        $histoName = substr($parts[5], 0, -4);
        $compAnddataset = explode("_", $parts[3], 2);
        echo '<td class="CtextAlign">' . sprintf('%02d', $key + 1) . ' <input type="checkbox" onchange="checkFunction2()" name="choix[]" id="' . sprintf('%02d', $key + 1);
        echo '" value="' . $key ;
        if (count($checked) >= 1) {
            if ($checked[$key] == '1') {
                echo '" checked="checked"';
            }
        }
        echo '" />' . "</td>\n";
        echo '<td class="CtextAlign">' . $compAnddataset[0] . '</td>'; // comparison (RECO vs RECO, PU vs PU, ..)
        echo '<td class="CtextAlign">' . $compAnddataset[1] . '</td>'; // dataset (ZEE, TTbar, ..)
        echo '<td class="CtextAlign">' . $histoName . '</td>';
        
        if (strpos($url, $parts[1]) !== false)
        {
            echo '<td class="CtextAlign blueClass">' . $value;
        }
        else {
            echo '<td class="CtextAlign darkGreyClass">' . $value;
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';

    echo '<br>';
    echo '<table class="clickable buttonChoice p-5px blackBorder1">';
    echo '<tr><td class="CtextAlign" button-choice="line0" title="Click on text to add it on textArea" id="line0">';
    echo 'Release link : ' . $text_0 ;
    echo '</td></tr>';
    echo '</table>';

    echo '<br>';
    echo '<table class="clickable actionChoice blackBorder1 p-5px">';
    echo '<tr>';
    echo '<td id="selectAll" class="CtextAlign MtextAlign blackBorder1 w-180px">Select/UnSelect all links</td>' . "\n"; 
    echo '<td id="removeAll" class="CtextAlign MtextAlign blackBorder1 w-130px">Remove all links</td>' . "\n"; 
    echo '<td id="removeSelected" class="CtextAlign MtextAlign blackBorder1 w-160px">Remove selected links</td>' . "\n"; 
    echo '<td id="copySelected" class="CtextAlign MtextAlign blackBorder1 w-130px">Copy selected links</td>' . "\n";
    echo '<td class="CtextAlign MtextAlign blackBorder1 w-130px">&nbsp;</td>' . "\n";
    echo '<td id="shareFile" class="CtextAlign MtextAlign blackBorder1 w-130px greyClass">Select histos for sharing</td>' . "\n";
    echo '<td class="CtextAlign MtextAlign blackBorder1 w-130px">&nbsp;</td>' . "\n";
    echo '<td class="CtextAlign MtextAlign blackBorder1 w-130px">' . '<a href="' . $displayAddr . '" target="_blank" rel="noopener noreferrer">Display histos</a>' . '</td>' . "\n";
    echo '</tr>';
    echo '</table>';

    echo '&nbsp;&nbsp;<span class="blueClass">Please, note that the <b>remove</b> function act on the file</span> <b><span class="redClass">AND NOT ONLY</span></b> <span class="blueClass">on this webpage ! </span><br>' ;
    echo '<br>';

    echo '<textarea name="message_content" cols="100" rows="10" class="contentfont" id="textArea">' . $text . '</textarea>' . "<br>\n"; # 

    echo '<br>';
        echo '<label class="redBorder2 hidden" id="sharedAddress">Shared address : </label>';
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

    echo '<table class="m-0-auto blackBorder1 p-5px w-30pct" >';
    echo '<tr class="TtextAlign">';
    echo '<td class="CtextAlign blackBorder1 blackClass m-0-auto"><b>shared files to use</b></td>';
    echo '</tr>';

    //prePrint('sharedFilesList', $sharedFilesList);
    foreach ($sharedFilesList as $key => $value) {
        $name = $chemin_eos_base . '/BasketList/' . $value;
        if (file_exists($name) && (filesize($name) !== 0)) {
            echo '<tr class="TtxtAlign"><td class="CtextAlign blackBorder1 m-0-auto">';
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
            //simPrintC('aF1', $tmp_aF3);

            $address = $web_roots . "/basket.php?actionFrom=" . $tmp_aF3 . "&sharedF=" . $reducedValue . '&basket=work';
            $tmp_aF4 = explode('.', $reducedValue)[1];
            $tmp_AF5 = substr($tmp_aF4, 0, 8);//simPrintC('$tmp_AF5', $tmp_AF5);
            $tmp_AF6 = substr($tmp_aF4, 8);//simPrintC('$tmp_AF5', $tmp_AF6);
            echo '<a href="' . $address . '" target="_blank" rel="noopener noreferrer">' . $tmp_AF5 . ' - ' . $tmp_AF6 . '</a>';
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
    echo '<h2><center><b><span class="redClass">shared histos display</span></b></center></h2><br>';

    $i=0;
    echo '<table class="tab1 p-5px w-100pct">';
    foreach ($lineHisto as $key => $value) {
        $value2 = substr($value, 52);
        $parts = explode("/", $value2); # so, there is 6 parts

        if ( $i % 3  == 0 ) {
            echo '<tr class="TtextAlign">';
        }
        echo '<td class="w-10px"> ';
        if (strpos($url, $parts[1]) !== false)
        {
            echo '<span class="blueClass">' . "https:" . $parts[1] . '</span>';
        }
        else {
            echo '<span class="darkGreyClass">' . "https:" . $parts[1] . '</span>';
        }
        echo '<a href="' . $value . '" target="_blank" rel="noopener noreferrer"><img class="image" width="' . "440" . '" src="' . $value . '"></a>' . "\n";

        echo '</td>';
        if ( $i % 3 == 2 ) {
            echo '</tr>';
        }
        $i+=1;
    }
    echo '</table>';
            
    echo '<br><br>';
    echo '<a href="' . $returnAddr . '" target="_blank" rel="noopener noreferrer">BACK to links management</a>' . "\n"; 
    echo '&nbsp; - &nbsp;';
    echo '<a href="' . $returnAddr2 . '" target="_blank" rel="noopener noreferrer">BACK to histos selection</a>' . "\n"; 
    
}
else { # manage the basket
    echo "HOUSTON WE HAVE A BIG PBM !!!"; 
}

?>

<script nonce="<?php echo $nonce; ?>">
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
    var fileName_0 = <?php echo json_encode($fileName_eos); ?>; //console.log('fileName_0 : ' + fileName_0);
    var img_add = <?php echo json_encode($image_add); ?>;
    var img_remove = <?php echo json_encode($image_remove); ?>;
</script>

<script nonce="<?php echo $nonce; ?>"> // t12
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

<script nonce="<?php echo $nonce; ?>"> // check buttons, size, releases, ..
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
                obj.html('<img width="32" height="32" src="' + img_remove + '" alt="Rem"/>');
                lineHisto1.push(imageName);
                var nb = lineHisto1.length;
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData, selLink2: "remove&nbsp;from&nbsp;basket"}, //
                    ).done(function(returnResult){
            		console.log('OK from url0 add !');
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
            		console.log('OK from url0 remove !');
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
        }

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
                    $('#shareFile').html('<span class="greyClass">Select histos for sharing</span>')
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
                    console.log('OK from url5 remove all !');
                    console.log(returnResult);
                    $(location).attr('href',destination);
                    }
                    ).fail(function(){
                        console.log('ERROR from basket::bc::removeAll !');
                    });
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
                        console.log('OK from url5 remove selected !');
                        console.log(returnResult);
                        $(location).attr('href',destination);
                        }
                        ).fail(function(){
                            console.log('ERROR from basket::bc::removeSelected !');
                        });
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
                    console.log('destination : ' + destination)
                    console.log('shareFileName : ' + shareFileName)
                    sharedText = JSON.stringify([webRoots, shareFileName, ListA]);
                    console.log('sharedText = ' + sharedText)
                    $.post(
                        url6, 
                        {sharedFile: sharedText},
                        ).done(function(returnResult){
                        console.log('OK from url6 shared file !');
                        console.log(returnResult);
                        //$(location).attr('href',destination);
                        }
                        ).fail(function(){
                            console.log('ERROR from basket::zc::sharedFile !');
                        });
                    var tmp1 = shareFileName.replace('sharedList.', '').replace('.txt', '')
                    //'<a href="' . $address . '" target="_blank" rel="noopener noreferrer">sharedList.' . $redValue . '.txt</a>';
                    var addr = 'https://cms-egamma.web.cern.ch/validation/Electrons/Dev/basket.php?actionFrom=' + actionFrom
                    addr += '&sharedF=' + tmp1 + '&basket=work'                
                    var tmp2 = '<a href="' + addr + '" target="_blank" rel="noopener noreferrer">' + addr + '</a>'
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
        }
        
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

<script nonce="<?php echo $nonce; ?>"> // checked
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
            affiche = '<table border=1><tr>'; 
            $.each(select1, function(key, value) {
                tmp1 = value.split(".");
                var name = webRoots + '/' + tmp1[0] + '/' + tags + '/' + tmp1[1] + 's/' + histoName1 + '.' + tmp1[1];
                affiche += '<td>' + tmp1[0] + '<img class="image" width="480" src="' + name + '"></td>';
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

<script nonce="<?php echo $nonce; ?>"> // checked2
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
            $('#shareFile').html('<span class="greyClass">Select histos for sharing</span>')
        }
    }
</script>

<script nonce="<?php echo $nonce; ?>"> // display/remove Releases array
$(document).ready(function(){
  $("#Liste").click(function(){
    var tild = $("#Histos").html();//console.log('histos : '+tild)
    if (tild.indexOf("remove") >= 0) {
        $("#Histos").html('<span class="blueClass"><b>Press here to display Releases array</b></span>');
    }
    else if (tild.indexOf("display") >= 0) {
        $("#Histos").html('<span class="blueClass"><b>Press here to remove Releases array</b></span>');
    }
    $("#ListeReleases").toggle();
  });
});
</script>

<script nonce="<?php echo $nonce; ?>">
$(document).ready(function() {
    // Liste des événements en ligne courants à intercepter et nettoyer
    var eventsToFix = ['onclick', 'onchange', 'onkeyup', 'onkeydown', 'onsubmit'];

    $.each(eventsToFix, function(index, eventName) {
        // jQuery trouve tous les éléments qui possèdent cet attribut (ex: [onchange])
        $("[" + eventName + "]").each(function() {
            var rawCode = $(this).attr(eventName); // Récupère le code JS brut
            
            if (rawCode) {
                $(this).removeAttr(eventName); // Supprime l'attribut pour valider la CSP
                
                // Extrait le nom de l'événement jQuery (ex: "click", "change")
                var jqEvent = eventName.substring(2); 
                
                // Attache l'événement de manière moderne et autorisée
                $(this).on(jqEvent, function(event) {
                    // Pour le changement d'un select/input, on ne bloque pas forcément le comportement par défaut
                    if (jqEvent === 'click') { event.preventDefault(); }
                    
                    // Exécute le code d'origine dans le contexte de l'élément (conserve "this")
                    new Function(rawCode).call(this); 
                });
            }
        });
    });
});
</script>

</main>
<?php include('basket_footer.php'); ?>


</body>
</html>
