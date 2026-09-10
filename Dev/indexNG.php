<!DOCTYPE html>
<html lang="en">

<?php
// On génère un jeton aléatoire sécurisé (si ce n'est pas déjà fait)
if (!isset($nonce)) {
    $nonce = base64_encode(random_bytes(16));
}
?>

<head>
<meta charset="UTF-8">
<!--meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://jquery.com;"-->

<title>Dev list webpage</title>
<link rel="icon" type="image/x-icon" href="/validation/Electrons/img/filetype-root-256.ico"> 
<link rel="stylesheet" href="../css/all.min.css">
<link rel="stylesheet" href="../css/styles.css">
<script src="../js/jQuery-4.0.0/jquery.min.js"></script>

</head>

<body>
<?php
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

define('MAIN_INDEX_LOADED', true);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        //'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,      // Nécessite HTTPS
        'httponly' => true,    // Bloque l'accès JS
        'samesite' => 'Strict' // Protection CSRF
    ]);
    session_start();

    // Autoriser uniquement /mon/chemin/index.php, rien d'autre
    $allowed = '/validation/Electrons/Dev/indexNG.php';
    $allowed_hosts = ['cms-egamma.web.cern.ch', 'localhost'];

    if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) !== $allowed || !empty($_GET)) {
        http_response_code(404);
        die();
    }

    // --- SECURITY (Haut de page) ---
    require_once '../php_inc/security.inc.php';
    require_once '../php_inc/defaults.inc.php';
    require_once '../php_inc/sorties.inc.php';
    require_once '../php_inc/fonctions.inc.php';
    require_once '../php_inc/pathAnalyze.inc.php';
    require_once '../php_inc/init_vars.inc.php';

    // 1. Sanitization de l'URL avant toute utilisation
    $actionFrom = cleanInput_V2($_REQUEST['actionFrom'] ?? '', true);  // true autorise les slashes
    $cchoice    = cleanInput_V2($_REQUEST['cchoice'] ?? '', true);    // false bloque les slashes
    $short_histo_name    = cleanInput_V2($_REQUEST['short_histo_name'] ?? '', false);    // false bloque les slashes

    $url_safe = cleanInput_V2($_GET['redirect'] ?? '', 'url');
    if (!empty($url_safe) && !preg_match('#^https?://#i', $url_safe)) {
        $url_safe = ''; // Fallback si jamais le protocole a été altéré
    }

    // Vérification CRITIQUE anti-traversal
    if (strpos($actionFrom, '..') !== false) {
        die("Chemin invalide : tentative de traversal détectée");
    }

    // Si votre header.php utilise $_REQUEST ou $_GET directement, mettez-les à jour :
    $_REQUEST['actionFrom'] = $actionFrom;
    $_REQUEST['cchoice'] = $cchoice;
    $_REQUEST['short_histo_name'] = $short_histo_name;
    $_REQUEST['redirect'] = $url_safe;

    $url = secureURL();

    // 2. Préparez la version échappée pour le HTML si nécessaire
    $url_html = htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    // 1. Récupération et nettoyage de base (suppression des caractères de contrôle)
    /*
        $raw_referer = $_SERVER['HTTP_REFERER'] ?? '';
        $clean_referer = preg_replace('/[\r\n\t\x00]/', '', $raw_referer);

        // 2. Validation stricte via votre fonction cleanInput (mode 'url' ajouté précédemment)
        $url_from_safe = cleanInput($clean_referer, 'url');
    */
    $url_safe = cleanReferer();
    simPrintC('referer', $url_safe);

    // 3. Vérification CRITIQUE du domaine (Whitelist)
    // Empêche la redirection vers google.com, evil.com, etc.
    $host = parse_url($url_from_safe, PHP_URL_HOST);

    if ($url_from_safe && !in_array($host, $allowed_hosts)) {
        // Si le domaine n'est pas le vôtre, on ignore le referer
        $url_from_safe = ''; 
        // Ou redirigez vers une page par défaut sûre : $url_from_safe = '/indexNG.php';
    }

// --- END SECURITY ---

?>
<main>
<?php
    $base_dir = __DIR__;
    $web_roots = getRootPath($base_dir);
    $chemin = $web_roots;

    $url_tmp = explode('?', $url_from_safe)[0];
    $url_tmp = end(explode('/', $url_tmp));
 
    if ($url == '//cms-egamma.web.cern.ch/validation/Electrons/Releases/indexNG.php') {
        session_unset(); // back to beginning & free $_SESSION
    }
    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos = str_replace($racine_html, $racine_eos, $fileName);
    $classical_roots = htmlspecialchars( $web_roots, ENT_QUOTES, 'UTF-8' );
    $classical_roots = str_replace("/indexNG.php?actionFrom=/", "/", $classical_roots);
    $classical_path = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $classical_path = str_replace("/indexNG.php?actionFrom=/", "/", $classical_path);
    $previous_url = dirname($url);
    
    if ( !file_exists($fileName_eos) ) {
        echo $file . " does not exist. Create it<br>\n";
        fopen($fileName_eos, "w");
    }

    $url_http = 'https:' . $url;
    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );

    $_SESSION['url'] = $url_http;

    $chemin = $chemin . '/' . $actionFrom;
    $chemin_eos = str_replace($racine_html, $racine_eos, $chemin);
    
    $files = array_slice(scandir($chemin_eos), 2);
    // Fill arrays with dirs & files
    $allList = extractAllFolders($files);
    $allKeys = array_keys($allList);
    $dirsList_date = array_map(fn($t) => $chemin_eos . $t, $allKeys);
    //prePrint('dirsList_date', $dirsList_date); // TEMP
    //prePrint('all folders', $allList);
    //prePrint('all Keys', $allKeys);
    
    $l_actionFrom = count(explode('/', $actionFrom));
    simPrint('l actionFrom', $l_actionFrom);
    if ($l_actionFrom == 4){
        foreach ($dirsList as $key => $value)
        {
            if ( $value == "gifs" )
            {
                $pictsDir = True;
                $pictsValue="gifs";
                $pictsExt=".gif";
                $allFormat+=1;
            }
            elseif ( $value == "pngs" ) // pbm : si le dernier repertoire est un png, ça zappe les gifs
            {
                $pictsDir = True;
                $pictsValue="pngs";
                $pictsExt=".png";
                $allFormat+=1;
            }
        }
    }
    $allFormat = count($dirsList);
    $boldFormat = '';
    if (isset($_SESSION['pictFormat'])) {
        if ($allFormat < 2) {
            $_SESSION['pictFormat'] = 'gif';
        }
        $pictsValue=$_SESSION['pictFormat'] . "s";
        $pictsExt="." . $_SESSION['pictFormat'];
        $boldFormat = $_SESSION['pictFormat'][0];
    }

    foreach ($filesList as $key => $value)
    {
        if ( $value == "index.html" )
        {
            $indexHtml = True;
        }
        elseif ( $value == "definitions.txt" )
        {
            $indexHtml = True;
        }
        // test sur histosFile
        elseif ((stristr($value, "ElectronMcFakeHistos") !== FALSE) and (stristr($value, ".txt") !== FALSE))
        {
            // ElectronMcFakeHistos.txt,
            $histosFile = True;
            $histosFileName = 'ElectronMcFakeHistos.txt';
        }
        elseif ((stristr($value, 'ElectronMcSignalHistos') !== FALSE) and (stristr($value, '.txt') !== FALSE))
        {
            // ElectronMcSignalHistosMiniAOD.txt,
            // ElectronMcSignalHistos.txt,
            // ElectronMcSignalHistosPt1000.txt
            $histosFile = True;
            $histosFileName = $value;
        }
        elseif ((stristr($value, 'config_target') !== FALSE) and (stristr($value, '.txt') !== FALSE))
        {
            // config_target.txt
            $histosFile = True;
            $histosFileName = $value;
        }
        elseif ((stristr($value, "HistosConfigFiles") !== FALSE) and (stristr($value, ".json") !== FALSE))
        {
            // HistosConfigFiles.json,
            $jsonFile = True;
            $histosFileNameJSON = $chemin_eos . "/HistosConfigFiles.json";
            $data = file_get_contents($histosFileNameJSON);
        }
    }
    
    echo '<div class="sticky">';
    include('headerNG.php');
    echo '</div>';

    // construction of folders list web page
    echo '<div id="part1" class="parent blueBorder1 fl-left CtextAlign w-45pct">';

    echo '<p>Here is the list of the 5 last releases candidates ';
    usort($dirsList_date, function($x, $y) { return filemtime($x) < filemtime($y); });
    echo '( here <b><span class="redClass">' . htmlspecialchars($dirsList_date[0]) .'</span> and <span class="blueClass">' . htmlspecialchars($dirsList_date[1]) .'</span></b> folders).</p>';//

    echo '<table class="tab5 clickable folders">';
    echo '<tr><td class="w-50pct">';
    echo '<b>Last Release Candidates';
    echo '</td><td class="w-50pct">';
    echo '<b>Last Modified On ';
    echo '</td></tr><tr>';

    $i = 0;
    echo '<td>' . "\n";
    foreach($dirsList_date as $filename)
    {
        $firstChar = array_reverse(explode('/', $filename))[0][0];
        if (is_numeric($firstChar)) {
            if ( $i < 5 ) {
                $link1 = $_SERVER["PHP_SELF"] . '?actionFrom=' . $actionFrom . '/' . getPathPiece($filename) . '&cchoice=diff';
                if ( $i == 0 ) {
                    echo '<b><a href="' . $link1 . '"><span class="redClass">' . getPathPiece($filename) . '</span></a></b><br>';//
                    }
                elseif ( $i == 1 ) {
                    echo '<b><a href="' . $link1 . '"><span class="blueClass">' . getPathPiece($filename) . '</span></a></b><br>';//
                }
                else {
                    echo '<b><a href="' . $link1 . '">' . getPathPiece($filename) . '</a></b><br>';//
                }
            }
            $i++;
        }
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
echo '</div>'; // part1

$tab_General = extractFolders4Accordion($allList);
$tab_Keys = array_keys($tab_General);

echo '<div id="part3" class="parent blackBorder2 fl-right CtextAlign w-54pct">';
    echo '<p>List of all releases <br>';
    echo 'here the <b>General case</b> release is a CMSSSW and <b>Others cases</b> not.</p>';

    echo '<div id="accordion">';
        if ( count($tab_Keys) > 0 ) {
            echo '<h3> General case level 0</h3>';
            echo '<div>';
            foreach($tab_General as $key => $value)
            {
                echo '<div class="cAccordion lv1">';//
                    echo '<h3><b> ' . htmlspecialchars($key) . '</b> - Last : <span class="greenClass">' . $tab_General[$key][0] . ' level 1</span></h3>';
                    echo '<div>';
                    //displayReleaseDateTitle();
                    foreach($tab_General[$key] as $key2 => $value2) {
                        echo '<div class="cAccordion lv2">';
                            //displayReleaseDate($key2, $chemin_eos);
                            echo '<span class="ex2"><b>' . htmlspecialchars($key2) . ' level 2</b></span>'; // 
                            echo '<div>';
                            echo '<div class="cAccordion lv3">';
                                foreach ($tab_General[$key][$key2] as $key3 => $value3) {
                                    echo '<span class="ex2"><b>' . htmlspecialchars($key3) . ' level 3</b></span>'; //
                                    echo '<div>';
                                        echo '<table class="greenBorder1 tab5">';
                                        foreach ($tab_General[$key][$key2][$key3] as $key4 => $value4) {
                                            echo '<tr><td class="blueBorder1 p-5px">';
                                            echo '<span class="ex2">' . htmlspecialchars($value4) . '</span>';
                                            echo '</td></tr>';
                                        }
                                        echo '</table>';
                                    echo '</div>';
                                }
                            echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        }
                    echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    echo '</div>';
echo '</div>'; // fint div part3

echo '<br><br><br>'. "\n";

$action_tmp = substr($actionFrom,1);
$action_list = explode("/", $action_tmp);
if ( count($action_list) == 2) {
    echo '<h2><center><b>' . $action_list[1] . '</b></center></h2><br>';
}
if (!(strpos($url, 'index') !== false)) {
    echo ' <br><b><a href="'.$web_roots.'/indexNG.php">Roots</a></b>';
    echo ' <br><br>';
}
if ( count($action_list) == 2) {
    echo '<b>Up to release folder : </b>' . $_fDL;
    echo '<b> ' . '<a href="' . $web_roots.'/indexNG.php?actionFrom=/' . $action_list[0] . '&cchoice=diff">' . $action_list[0] . '</a></b>' . '<br>';
}

/*echo '<div id="part2" class=" greenBorder1 fl-right CtextAlign w-54pct">';
if ($l_actionFrom == 1){
    echo '<p>List of all releases <br>';
    echo 'here the <b>General case</b> release is a CMSSSW and <b>Others cases</b> not.</p>';//
}
if ( $actionFrom == '') {
    //prePrint('others', $tab_Others); // TEMP
    //prePrint('CMSSW', $tab_CMSSW); // TEMP
    //prePrint('general', $tab_General); // TEMP

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
            }
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
            }
            echo '</div>';
        }
    echo '</div>';
}
echo '</div>'; // part2*/

// end of folders list web page construction


if (array_key_exists('fileForHistos_eos', $_SESSION)) {
    $file = $_SESSION['fileForHistos_eos'];
    //simPrint("fileForHistos_eos", $file); 
    if ( file_exists($file) ) {
        $handleBasket = fopen($file, "r");
        if ($handleBasket)
        {
            while(!feof($handleBasket))
            {
                $tmp = fgets($handleBasket);
                $tmp = str_replace(array("\r", "\n"), '', $tmp);
                $lineHisto1[] = $tmp;
            }
            fclose($handleBasket);
        }
        else {
            simPrint("can not open", $file);
        }
    }
    else {
        echo $file . " does not exist. Create it<br>\n";
        fopen($file, "w");
    }
}

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
        echo ' <br>';
    }

    /* Write the table with all histos */
    if ( $url_flag ) {
        echo '<div id="tableHistos" class="parent blackBorder1 hidden">';
    }
    else {
        echo '<div id="tableHistos" class="parent blackBorder1 d-block">';
    }
    echo '<table class="tab6">';
    for ($ic = 0; $ic < count($clefs); $ic++) {
        $aaa = $ic % 5;
        if ( $aaa == 0 ) {
            echo '<tr>';
        }
        $textToWrite = "";
        echo '<td class="b2"><b> ' . $clefs[$ic] . '</b>';
        $titleShortName = titleShortName($clefs[$ic]);
        echo '&nbsp;&nbsp;' . "\n" . '<a href="#' . $ic . '" onclick="goToHisto()">' ; // write group title $titleShortName
        echo '<img width="18" height="15" src=' . $image_point . ' alt="Top">' . ' <br><br>';
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

            if ( $elem == "endLine" ) {
                $otherTextToWrite .= " <br>";
                $jc += 1;
                $kc = 0;
        }
            elseif ( $histo_positions[3] == "0" ) {
                if ($numLine == 0) {
                    $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $short_histo_name . '</a>' . " &nbsp;";
                    $common = $short_histo_name;
                    $numLine += 1;
                }
                else { // $numLine > 0
                    if ( $after == "" ) {
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $before . '</a>' . " &nbsp;";
                    }
                    else{ // $after != ""
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $after . '</a>' . " &nbsp;";
                    }
                    $common = $before;
                }
                $kc += 1;
            }
            else { //$histo_positions[3] == "1"
                if ($numLine == 0) {
                    $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $short_histo_name . '</a>' . " &nbsp;";
                    $common = $short_histo_name;
                }
                else { // $numLine > 0
                    if ( $after == "" ) {
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $before . '</a>' . " &nbsp;";
                    }
                    else { // $after != ''
                        $otherTextToWrite .= ' &nbsp;<a href="#' . $short_histo_name . '" class="' . $classColor . '" onclick="goToHisto(this.id)" id="' . $short_histo_name . '_2">' . $after . '</a>' . " &nbsp;";
                }
                }
                $numLine = 0;
                $kc += 1;
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
        echo '</td>';
        if ( $aaa == 4 ) {
            echo '</tr>';
        }
    }

    echo '</table>';
    echo '</div>';
    

    $lineFlag = True;
        /* Write the HISTOS pictures */
        echo '<div id="listeHistos" class="parent greenBorder0 d-block">';
        echo '<br><br><br><br><br><br><br><br><br><br><br>';
        echo '<div class="line">';
    for ($i = 0; $i < count($clefs); $i++) {
        echo '<a href="#" onclick="goToTable()"><img class="s18" src=' . $image_up . ' alt="Top"></a>';
        $titleShortName = titleShortName($clefs[$i]);
        
        /* RELEASES */
        echo '<div class="cell"><b>';
        echo '<a id="' . $i . '" class="anchor0"></a>';
        echo $clefs[$i] . '</b></div>';
        echo '</div><div class="line">';
        echo '<tableclass="clickable addLink pinkBorder0">';
        echo '<tr>';

        //echo '$lineHisto1 : ' . prePrint('lineHisto1', $lineHisto1) . '<br>';
        $j = 0;
        $k = 0;
        foreach ($histoArray[$clefs[$i]] as $elem) {
            if ( $elem != "endLine" ) {
                list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem);
                $pict_name = $escaped_url . "/" . $pictsValue ."/" . $short_histo_names[0] . $pictsExt;
                /* Test if url_http exist into the $lineHisto array */
                $testExistUrl = false;
                foreach ($lineHisto1 as $key => $value) {
                        if ( $value == 'https:' . $pict_name ) {
                        $testExistUrl = true;
                    }
                }

                if ( $lineFlag ) {
                    echo '<td>';
                    echo '<div class="cellUp"><a href="#" onclick="goToTable()"><img class="s18" src=' . $image_up . ' alt="Top"></a></div>' . "\n";
                    echo '</td>';
                }
                    // New new options
                $urlOptions = 'url=' . $pict_name . '&basket=view&addLink=KO' . '"';
                $web_path = $web_roots . '/basket.php?' . $urlOptions;
                if (  $histo_positions[3] == "0" ) {
                    echo '<td>';
                    echo '<div class="cell anchor0" id="' . $short_histo_name . '">';//border: 1px blue solid;
                    echo '<img class="image img blueBorder2 " width="440" src="' . $pict_name . '" alt="" id="' . $short_histo_name . '_1">';//</a>
                    echo '</div>';
                    echo "\n";

                    echo '</td>';
                    if ( $testExistUrl) {
                        echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" src="' . $image_remove . '" alt="Add"/>'; // </td>
                    }
                    else {
                        echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/>'; // </td>
                    }
                    echo '</td>';
                    $lineFlag = False;
                }
                else { // line_sp[3]=="1"
                    echo '<td>';
                    echo '<div class="cell anchor0" id="' . $short_histo_name . '">';
                    echo '<img class="image img blueBorder2" width="440" src="' . $pict_name . '" alt="" id="' . $short_histo_name . '_1">' ;//</a>

                    echo '</div>';
                    echo '</td>';
                    if ( $testExistUrl) {
                        echo '<td align="center" addlink-choice="remove from basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" class="selected" src="' . $image_remove . '" alt="Add"/>'; // </td>
                    }
                    else {
                        echo '<td align="center" addlink-choice="add to basket" addlink-id="' . $short_histo_name . '" addlink-url="' . $pict_name . '" width="60"><img width="32" height="32" src="' . $image_add . '" alt="Add"/>'; // </td>
                    }
                echo '</td>';

                    echo '</tr>';
                    echo '</table>';
                    echo '<div class="line">';
                    echo '<table class="clickable addLink pinkBorder0">';
                    echo '<tr>';
                    $lineFlag = True;
                    $k += 1;
                }
            }
            else {
                $j += 1;
                $k = 0;
            }
        }
    }
    echo  '</div>';
    echo '</table>';
    echo '</div>';

} // end of web page construction of histos

?>

<script nonce="<?php echo $nonce; ?>"> // transfert
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

<script nonce="<?php echo $nonce; ?>"> // accordéon
// --- Niveau 0 : ferme tout avant de se fermer ---
const h0 = document.querySelector('#accordion > h3');
if (h0) {
    h0.addEventListener('click', (e) => {
        e.stopPropagation();
        const content = h0.nextElementSibling;
        const isOpen = content.classList.contains('open');

        if (isOpen) {
            content.querySelectorAll('div.open').forEach(c => {
                c.classList.remove('open');
                c.previousElementSibling.classList.remove('active');
            });
        }

        content.classList.toggle('open');
        h0.classList.toggle('active');
    });
}

// --- Niveaux 1-3 : toggle + fermeture des frères ---
document.querySelectorAll('.cAccordion span.ex2, .cAccordion h3').forEach(el => {
    // Skip le niveau 0 (géré séparément)
    if (el.parentElement && el.parentElement.id === 'accordion') return;

    const content = el.nextElementSibling;
    if (!content || content.tagName !== 'DIV') return;

    el.style.cursor = 'pointer';

    el.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = content.classList.contains('open');
        const cAccordion = el.closest('.cAccordion');
        const container = cAccordion.parentElement;

        container.querySelectorAll(':scope > .cAccordion > span.ex2 + div.open, :scope > .cAccordion > h3 + div.open')
            .forEach(c => {
                c.classList.remove('open');
                c.previousElementSibling.classList.remove('active');
            });

        if (!isOpen) {
            content.classList.add('open');
            el.classList.add('active');
        }
    });
});
</script>

<script nonce="<?php echo $nonce; ?>"> // addLink
    $(document).ready(function(){
        // la class clickable est appliquée à tous les table qui auront des "boutons"
        $('table.clickable td').on('click', checkAddLink );
        $('table.clickable td').on('click', checkCurveChoice );
        console.log('general nous voilà !');
        var nb = lineHisto1.length;
        //console.log('nb : ' + nb);
        if ((nb == 1) && (lineHisto1[0] == '')) {
            $('[soCol="bleu"]').html('<span class="blueClass">Unselect All</span>');
        }
        else { // if ( nb >= 1 ) 
            var nb2 = nb - 0;
            $('[soCol="bleu"]').html('<span class="redClass"><b>Unselect All (' + nb2 + ')</b></span>');
            $('[soCol="visio"]').removeClass("hidden");
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
        //console.log('url2 : ' + url2);
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
            lineHisto1 = lineHisto2;
            TableData = JSON.stringify(lineHisto1);
            //console.log('tableData' + TableData);
            $.post(
                url0, 
                {pTableData: TableData},
                ).done(function(returnResult){
                //console.log('OK from url2 !');
                //console.log(returnResult);
                }
                ).fail(function(){
                    console.log('ERROR from index::ee !');
                });
            //someText += nb + '<br>';
            //$('[soDiv="Arghhhhh"]').html(someText);
            $('[soCol="bleu"]').html('<span class="blueClass">Unselect All</span>');
            $('[soCol="visio"]').addClass("hidden");
        }
        else if (typeof cc !== "undefined") {
            if (cc.indexOf("add") >= 0) {
                obj.attr('addlink-choice', "remove from basket");
                obj.html('<img width="32" height="32" src="' + img_remove + '" alt="Rem">');
                $('[addlink-id="'+id+'"] img').css('border', "solid 3px blue");
                $('[soCol="visio"]').removeClass("hidden");
                lineHisto1.push(imageName);
                lineHisto1 = clearArray(lineHisto1);
                var nb = lineHisto1.length;
                //console.log('nb : ' + nb);
                var someText = nb + '<br>';
                for (let j=0; j<nb; j++) {
                    someText += ' - ' + lineHisto1[j] + '<br>';
                }
                TableData = JSON.stringify(lineHisto1);
                //console.log(TableData);
                $.post(
                    url0, 
                    {pTableData: TableData},
                    ).done(function(returnResult){
            		//console.log('OK from url2 !');
                    //console.log(returnResult);
                	}
                  	).fail(function(){
                    	console.log('ERROR from index::cc::add !');
                    });
                if ((nb == 1) && (lineHisto1[0] == '')) {
                    $('[soCol="bleu"]').html('<span class="blueClass">Unselect All</span>');
                }
                else { // if ( nb >= 1 ) 
                    var nb2 = nb - 0;
                    //console.log('nb2 : ' + nb2);
                    $('[soCol="bleu"]').html('<span class="redClass"><b>Unselect All (' + nb2 + ')</b></span>');
                }

            }
            else if (cc.indexOf("remove") >= 0) {
                //console.log("remove");
                obj.attr('addlink-choice', "add to basket");
                obj.html('<img width="32" height="32" src="' + img_add + '" alt="Add">');
                $('[addlink-id="'+id+'"] img').css('border', "solid 0px blue");
                $('[soCol="visio"]').addClass("hidden");
                
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
                    $('[soCol="bleu"]').html('<span class="blueClass">Unselect All</span>');
                }
                else { // if ( nb >= 1 ) 
                    var nb2 = nb - 0;
                    //console.log('nb2 : ' + nb2);
                    $('[soCol="bleu"]').html('<span class="redClass"><b>Unselect All (' + nb2 + ')</b></span>');
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

    function checkCurveChoice() {
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('curveChoice')) {
            $('table.curveChoice td').removeClass('Gras');//
            curveChoice($(this));
            //console.log("curveChoice");
        }
    }
    function curveChoice(obj){
        var cc = obj.attr('curve-choice');
        var affiche = 'cc : ' + cc ;
        console.log(affiche)
        if (typeof cc !== "undefined") {
            if (cc == '') {
                cc = 'histos';
                $('[curve-choice="histos"]').addClass('Gras');
            }
            else if (cc == 'histos') {
                $('[curve-choice="histos"]').addClass('Gras');
            }
            else if (cc == 'diffMax') {
                $('[curve-choice="diffMax"]').addClass('Gras');
            }
            $('div.cell img.image.img').each(function(index, elt) {
                var dd = $(this).attr('src').split("/");
                var lastItem = dd.pop();
                var beforeLastItem = dd.join("/");
                var ext = lastItem.split(".")[1];
                lastItem = lastItem.split(".")[0];
                //console.log(lastItem);
                //console.log(beforeLastItem + '//' + lastItem)
                var firstChars = lastItem.substring(0,2);
                //console.log(firstChars + ' - ' + cc);
                if ((firstChars == 'h_') && (cc == 'diffMax')) {
                    p_name = beforeLastItem + '/maxDiff_comparison_' + lastItem + '_3.' + ext
                    //console.log(p_name + firstChars)
                    $(this).data('src', p_name)
                    $(this).attr('src', p_name)
                }
                else if ((firstChars == 'ma') && (cc == 'histos')) {
                    //console.log('==' + lastItem.replace('maxDiff_comparison_', ''))
                    //console.log('==' + lastItem.replace('maxDiff_comparison_', '').replace('_3', ''))
                    p_name = beforeLastItem + '/' + lastItem.replace('maxDiff_comparison_', '').replace('_3', '') + '.' + ext
                    //console.log(p_name)
                    $(this).data('src', p_name)
                    $(this).attr('src', p_name)
                }
            })
        }
    // https://cms-egamma.web.cern.ch/validation/Electrons/Releases/15_1_0_pre6_2025_DQM_std/FullvsFull_CMSSW_15_1_0_pre5/RECO-RECO_ZpToEE_m6000_14TeV/pngs/h_ele_charge.png
    // https://cms-egamma.web.cern.ch/validation/Electrons/Releases/15_1_0_pre6_2025_DQM_std/FullvsFull_CMSSW_15_1_0_pre5/RECO-RECO_ZpToEE_m6000_14TeV/pngs/maxDiff_comparison_h_ele_charge_3.png
    }
</script>

<script nonce="<?php echo $nonce; ?>"> // gotoHisto
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
            $('#tableHistos').removeClass("hidden")
        }
        else {
            $('#tableHistos').addClass("hidden")
        }
    }
</script>

<script nonce="<?php echo $nonce; ?>"> // gotoTable
    function goToTable(valeur) {
        console.log('goToTable ' )
        $('div.cell img.image.img').each(function(index, elt) {
            var cc = $(this).attr('id').slice(0,-2) ;
            $('#'+cc+'_1').css('border', "solid 2px blue");
        })
        $('#tableHistos').removeClass("hidden")
    }
</script>

<script nonce="<?php echo $nonce; ?>"> // KS click
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
        $(location).attr('href', web_roots_KS);
    }
</script>

</main>

<?php include('footer.php'); ?>


</body>
</html>
