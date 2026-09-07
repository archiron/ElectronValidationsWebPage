<header>
    
    <?php
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        //'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,      // Nécessite HTTPS
        'httponly' => true,    // Bloque l'accès JS
        'samesite' => 'Strict' // Protection CSRF
    ]);
    session_start();
    //echo 'session id : ' . session_id() . "<br><br>\n";
    if (isset($_POST['pTableData'])) {
        echo 'OK in basket.php' . '<br>';
        echo $_POST['pTableData']; 
    }

    $base_dir = __DIR__;
    include '../php_inc/defaults.inc.php';
    include '../php_inc/sorties.inc.php';
    include '../php_inc/fonctions.inc.php';
    $web_roots = getRootPath($base_dir);

    $pictsValue="gifs"; // default
    $pictsExt=".gif"; // default
    if (isset($_SESSION['pictFormat'])) {
        $pictsValue=$_SESSION['pictFormat'] . "s"; // gifs/pngs
        $pictsExt="." . $_SESSION['pictFormat']; // .gif/.png
        //simPrintC('pictsValue', $pictsValue);
        //simPrintC('pictsExt', $pictsExt);
    }

    $chemin = $web_roots;
    
    // 1. SÉCURISATION DE L'URL COURANTE (À placer ici)
    $raw_host = $_SERVER['HTTP_HOST'] ?? '';
    $raw_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Suppression des caractères de contrôle (Header Injection)
    $clean_host = preg_replace('/[\r\n\t\x00]/', '', $raw_host);
    $clean_uri = preg_replace('/[\r\n\t\x00]/', '', $raw_uri);

    // Reconstruction
    $url = "//{$clean_host}{$clean_uri}";

    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);
    $_SESSION['localFileForHistos_eos'] = $fileName_eos;
    if (empty($_SESSION['fileForHistos_eos'])){
        $_SESSION['fileForHistos_eos'] = $_SESSION['localFileForHistos_eos'];
    }
    
    if (!empty($url)) {
        $tmp_lhn = end(explode('/', $url));
        $long_histo_name = explode('.', $tmp_lhn)[0]; //simPrintC('long histo name', $long_histo_name);
        $short_histo_name = shorterHistoName($long_histo_name); //simPrintC('short histo name', $short_histo_name);
        if ( substr($short_histo_name, 0, 2) != 'h_' ) {
            $short_histo_name = '';
        }
        //simPrintC('short histo name', $short_histo_name);
    }
    $fileForHistos = (isset($_REQUEST['sharedF']) ? $_REQUEST['sharedF'] : '');
    if (!empty($fileForHistos)) {
        $fileForHistos = "sharedList." . $fileForHistos . ".txt";
        $fileForHistos_eos=str_replace($racine_html, $racine_eos, $fileForHistos);
        $_SESSION['fileForHistos_eos'] = $fileForHistos_eos;
    }

    if (($_SESSION['url'] !== '') && ($actionFrom == '')) {
        //simPrintC('racine html', $racine_html);simPrintC('racine html', $_SESSION['url']);
        $tmp = str_replace($racine_html, '', 'https:' . $_SESSION['url']);
        $tmp = str_replace('validation/Electrons/Releases/', '', $tmp);
        $tmp = str_replace('index.php?actionFrom=', '', $tmp);
        $tmp = str_replace('index2.php?actionFrom=', '', $tmp);
        $tmp = str_replace('https:/', '', $tmp);
        $tmp = str_replace('&cchoice=diff', '', $tmp);
        if (str_contains($tmp, 'gifs')){
            $tmp = explode('/gifs/', $tmp)[0];
        }
        else {
            $tmp = explode('/pngs/', $tmp)[0];
        }
        if ($tmp[0] != '/') {
            $actionFrom = '/' . $tmp;
        }
        else {
            $actionFrom = $tmp;
            }
    }
    $actionFrom = str_replace('//', '/', $actionFrom);
    //simPrintC('actionFrom after ', $actionFrom);
    if (empty($url)) {
        $url = $_SESSION['url'];
    }
    else {
        $_SESSION['url'] = $url;
    }
    
    $url_http = 'https:' . $url;
    $histoName = end(explode('/', $url));
    
    $chemin = $chemin . '/' . $actionFrom;
    $chemin_eos=str_replace($racine_html, $racine_eos, $chemin);
    $chemin_eos_base = str_replace($racine_html, $racine_eos, $web_roots);simPrintC("chemin_eos_base", $chemin_eos_base);
    
    $filesList = array();
    $sharedFilesList = array();
    $files = array_slice(scandir($chemin_eos_base . '/BasketList/'), 2);
    //prePrint("shared Files List", $files);
    
    $filesList = array();
    $lineHisto = array();
    $checked = array();
    $choiceValue='';
    $text_0 = '';
    $text = '';
    $indexHtml=False;
    $histosFile=False;
    
    $lineHisto = [];
    if (array_key_exists('fileForHistos_eos', $_SESSION)) {
        $file = $_SESSION['fileForHistos_eos'];
        if ( file_exists($file) ) {
            $handleBasket = fopen($file, "r");
            if ($handleBasket)
            {
                while(!feof($handleBasket))
                {
                    $tmp = fgets($handleBasket);
                    $tmp = str_replace(array("\r", "\n"), '', $tmp);
                    $lineHisto[] = $tmp;
                }
                fclose($handleBasket);
            }
            else {
                echo 'can not open ' . $file . "<br>\n";
            }
        }
    }
    $Nlinks = count($lineHisto);
    if (($Nlinks >= 1) && ($lineHisto[0] !== '')) {
        // recompute actionFrom
        $tmp_aF1 = str_replace($web_roots, '', $lineHisto[0]);
        $tmp_aF2 = explode('/',$tmp_aF1);
        $actionFrom = '/' . $tmp_aF2[1] . '/' . $tmp_aF2[2] . '/' . $tmp_aF2[3];
        simPrintC('aF1', $actionFrom);
    }
    
    if ($short_histo_name != '') {
        $option_B = "action=" . $short_histo_name   . "&url=" . $url . "&basket=view";
    }
    else {
        $option_B = "url=" . $url . "&basket=view";
    }

    echo '<table class="tab0 blackBorder0 p-1px">';
    echo '<tr class="ValidationsMenu">';
    echo '<td class="w-25pct b0">';
    writeHeaderMenu();
    echo '</td>';

    echo '<th><span class="redClass">';
    echo '<b>electron validation: signal</b>';
    echo '</span>';
    if ($short_histo_name != '') {
        echo '<span class="darkBlueClass">';
        echo ' / ';
        echo '<b>' . $short_histo_name  . '</b>';
        echo '</span></th>';
    }
    echo '<td class="MtextAlign RtextAlign">';
    if ($basket == 'display') {
        echo '<a href="' . $web_roots . "/basket.php?short_histo_name=" . $short_histo_name . "&basket=work&actionFrom=" . $actionFrom . '#000">BACK</a>';
        echo '&nbsp; - &nbsp;' . $_fDL;
    }
    elseif ($basket == 'view') {
        ;
        echo '<a href="' . $web_roots . '/basket.php?url=' . $url . '&basket=work">Basket</a>' . "\n";
        echo '&nbsp; &nbsp;' . $_fDL;
    }
    else { // work
        if ( $short_histo_name != '' ) {
            $returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom . '#000"' ;
            echo '<a href="' . $returnAddr . '">basket view</a>';
        }
        else {
            $returnAddr = $web_roots .  "/index.php?actionFrom=" . $actionFrom . "&cchoice=diff#000" ;
            echo '<a href="' . $returnAddr . '" target="_blank" rel="noopener noreferrer">BACK to histos</a>';
        }
        echo '&nbsp; &nbsp;' . $_fDL;
        echo '<a href="' . $web_roots . '/basket.php?' . $option_B . '">Basket view</a>' . "\n";
        echo '&nbsp; &nbsp;' . $_fDL;
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>' . $_fDL;
    
    foreach ($files as $key => $value)
    {
        if (is_file($chemin_eos_base . DIRECTORY_SEPARATOR . "BasketList" . DIRECTORY_SEPARATOR . $value))
        {
            $filesList[] = $value;
            if (stristr($value, 'sharedList') !== FALSE)
            {
                $sharedFilesList[] = $value;
            }
        }
    }
    
    echo '<table class="tab0 blackBorder0">';
    echo '<tr>';

    if ($basket == "view") {
        echo '<td class="CtextAlign">';
        echo '<table class="clickable Releases w-500px blackBorder1">';
        echo '<tr>';
        echo '<td class="CTextAlign" id="Histos"><span class="blueClass"><b>Press here to display Releases array</b></span></td>';
        echo '<td>&nbsp;</td>';
        $tag1 = explode('/', $actionFrom)[3];
        $tag2 = explode('_', $tag1, 2)[0];
        $tag3 = explode('_', $tag1, 2)[1];
        $newUrl = 'https://cms-egamma.web.cern.ch/validation/Electrons/Comparisons/main_display_comparison.php?';
        $newUrl .= '&tag=' . $tag2;
        $newUrl .= '&file4histos=ElectronMcSignalHistos.txt&release=&dataset=' . $tag3;
        $newUrl .= '&reference=&long_histo_name='.$long_histo_name.'&compFullFast=';
        echo '<td class="CtextAlign" id="displayHistosLink"><span class="blueClass">';
        echo '<b>Display histos comparison (Releases)</b></span></td>';
        echo '</tr>';
        echo '</table>';
        echo '</td>';
        echo '<td class="CtextAlign">';
            $pict_name1 = $web_roots . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_1.png';
            $pict_name2 = $web_roots . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_2.png';
            $pict_name3 = $web_roots . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_3.png';
            $chemin_KS_eos = str_replace($racine_html, $racine_eos, $web_roots);
            if (file_exists($chemin_KS_eos . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_3.png')) {
                echo '<a href="' . $pict_name3 . '" target="_blank" rel="noopener noreferrer">';
                echo '<img class="image img blueBorder2 w-200px" src="' . $pict_name3 . '" alt="" ></a>';
            }
            else {
                if (file_exists($chemin_KS_eos . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_1.png')) {
                    echo '<a href="' . $pict_name1 . '" target="_blank" rel="noopener noreferrer">';
                    echo '<img class="image img blueBorder2 w-200px" src="' . $pict_name1 . '" alt="" ></a>';
                }
                if (file_exists($chemin_KS_eos . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_2.png')) {
                    echo '<a href="' . $pict_name2 . '" target="_blank" rel="noopener noreferrer">';
                    echo '<img class="image img blueBorder2 w-200px" src="' . $pict_name2 . '" alt="" ></a>';
                }
            }
        echo '</td>';
        echo '<td>';
        $returnAddr = $web_roots .  "/index.php?actionFrom=" . $actionFrom . "#" . $short_histo_name ;
        imageSize($returnAddr);
        echo '<script type="text/javascript" nonce="<?php echo $nonce; ?>">' . "\n";
        echo '$(\'[size-choice="480"]\').addClass("Gras")';
        echo '</script>';
        echo '</td>';
    }

    echo '</tr>';
    echo '</table>';

    if (array_key_exists('choiceValue', $_REQUEST)) {
        $choiceValue = $_REQUEST['choiceValue'];
        if ($choiceValue != '')
        {
            simPrint("value", $choiceValue);
        }
    }
//prePrint('files', $filesList);

?>
</header>
