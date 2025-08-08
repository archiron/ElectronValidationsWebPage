<header>
    
    <?php
    session_start();
    //echo "session id : " . session_id() . "<br><br>\n";
    if (isset($_POST['pTableData'])) {
        echo 'OK in basket.php' . '<br>';
        echo $_POST['pTableData']; 
    }

    $base_dir = __DIR__;
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';
    $web_roots = getRootPath($base_dir);

    $pictsValue="gifs"; // default
    $pictsExt=".gif"; // default
    if (isset($_SESSION['pictFormat'])) {
        $pictsValue=$_SESSION['pictFormat'] . "s"; // gifs/pngs
        $pictsExt="." . $_SESSION['pictFormat']; // .gif/.png
        //simPrint('pictsValue', $pictsValue);
        //simPrint('pictsExt', $pictsExt);
    }

    $chemin = $web_roots;
    
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; # url of the folder in order to use without index.php
    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);
    $_SESSION['localFileForHistos_eos'] = $fileName_eos;
    if (empty($_SESSION['fileForHistos_eos'])){
        $_SESSION['fileForHistos_eos'] = $_SESSION['localFileForHistos_eos'];
    }
    
    //$corresp = 0;
    $url = (isset($_REQUEST['url']) ? $_REQUEST['url'] : '');

    $actionFrom = (isset($_REQUEST['actionFrom']) ? $_REQUEST['actionFrom'] : '');simPrintC('actionFrom ', $actionFrom);
    $basket = (isset($_REQUEST['basket']) ? $_REQUEST['basket'] : '');
    $site  = (isset($_REQUEST['site']) ? $_REQUEST['site'] : '');
    //$short_histo_name = (isset($_REQUEST['short_histo_name']) ? $_REQUEST['short_histo_name'] : '');simPrintC('short histo name', $short_histo_name);
    //$long_histo_name = (isset($_REQUEST['long_histo_name']) ? $_REQUEST['long_histo_name'] : '');simPrintC('long histo name', $long_histo_name);
    if (!empty($url)) {
        $tmp_lhn = end(explode('/', $url));
        $long_histo_name = explode('.', $tmp_lhn)[0]; //simPrintC('long histo name', $long_histo_name);
        $short_histo_name = shorterHistoName($long_histo_name); //simPrintC('short histo name', $short_histo_name);
    }
    $fileForHistos = (isset($_REQUEST['sharedF']) ? $_REQUEST['sharedF'] : '');
    if (!empty($fileForHistos)) {
        //echo "non empty fileForHistos<br>\n";
        $fileForHistos = "sharedList." . $fileForHistos . ".txt";
        $fileForHistos_eos=str_replace($racine_html, $racine_eos, $fileForHistos);
        $_SESSION['fileForHistos_eos'] = $fileForHistos_eos;
        //echo "You are using a <font color=\"red\"><b>shared</b></font> file : " . getReducedName($fileForHistos_eos) . "<br>\n";
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
    simPrintC('actionFrom after ', $actionFrom);
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
    $chemin_eos_base = str_replace($racine_html, $racine_eos, $web_roots);//simPrint("chemin_eos_base", $chemin_eos_base);
    
    $filesList = array();
    $sharedFilesList = array();
    $files = array_slice(scandir($chemin_eos_base), 2);
    
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
                    /*if ($tmp == $url) {
                        //$corresp = 1;
                    }*/
                }
                fclose($handleBasket);
            }
            else {
                echo "can not open " . $file . "<br>\n";
            }
        }
        /*else {
            echo $file . " does not exist. Create it<br>\n";
            fopen($file, "w");
        }*/
    }
    $Nlinks = count($lineHisto);
    if (($Nlinks >= 1) && ($lineHisto[0] !== '')) {
        // recompute actionFrom
        $tmp_aF1 = str_replace($web_roots, '', $lineHisto[0]);
        $tmp_aF2 = explode('/',$tmp_aF1);
        $actionFrom = '/' . $tmp_aF2[1] . '/' . $tmp_aF2[2] . '/' . $tmp_aF2[3];
        simPrint('aF1', $actionFrom);
    }
    
    if ($short_histo_name != '') {
        $option_B = "action=" . $short_histo_name   . "&url=" . $url . "&basket=view";
    }
    else {
        $option_B = "url=" . $url . "&basket=view";
    }

    echo '<table class="tab0" border="0" cellpadding="1">';
    echo '<tr>';
    echo '<td width=" 25%" class="b0">';
    writeHeaderMenu();
    echo '</td>';

    echo '<th><span class="redClass">';
    echo "<b>electron validation: signal</b>";
    echo "</span>";
    if ($short_histo_name != '') {
        echo '<span class="darkBlueClass">';
        echo " / ";
        echo "<b>" . $short_histo_name  . "</b>";
        echo "</span></th>";
    }
    echo "<td class=\"RtextAlign\">";
    if ($basket == 'display') {
        echo "<a href=\"" . $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=work&actionFrom=" . $actionFrom . "#000\">BACK</a>";
        echo "&nbsp; - &nbsp;\n";
    }
    elseif ($basket == 'view') {
        ;
        echo "<a href=\"$web_roots/basket.php?url=" . $url . "&basket=work\">Basket</a>" . "\n";
        echo "&nbsp; &nbsp;\n";
    }
    else { // work
        if ( $short_histo_name != '' ) {
            $returnAddr = $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom . "#000" ;
            echo "<a href=\"" . $returnAddr . "\">basket view</a>";
            //echo "&nbsp; - &nbsp;\n";
        }
        else {
            $returnAddr = $web_roots .  "/index.php?actionFrom=" . $actionFrom . "&cchoice=diff#000" ;
            simPrint('return Adress work', $returnAddr);
            echo '<a href="' . $returnAddr . '">BACK to histos</a>';
            //echo "&nbsp; - &nbsp;\n";
        }
        echo "&nbsp; - &nbsp;\n";
        echo "<a href=\"$web_roots/basket.php?" . $option_B . "\">Basket view</a>" . "\n";
        echo "&nbsp; &nbsp;\n";
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";
    
    foreach ($files as $key => $value)
    {
        if (is_file($chemin_eos_base . DIRECTORY_SEPARATOR . $value))
        {
            $filesList[] = $value;
            if (stristr($value, 'sharedList') !== FALSE)
            {
                $sharedFilesList[] = $value;
            }
        }
    }
    
    echo "<table class=\"tab0\" border=\"0\">";
    echo '<tr>';// class="b3"

    if ($basket == "view") {
        echo '<td class="CtextAlign">';
        echo '<table class="clickable Releases" width="500px" border="1">';
        echo '<tr>';
        echo '<td align="center" id="Histos"><span class="blueClass"><b>Press here to display Releases array</b></span></td>';
        echo '<td>&nbsp;</td>';
        $tag1 = explode('/', $actionFrom)[3];
        $tag2 = explode('_', $tag1, 2)[0];
        $tag3 = explode('_', $tag1, 2)[1];
        $newUrl = 'https://cms-egamma.web.cern.ch/validation/Electrons/Comparisons/main_display_comparison.php?';
        $newUrl .= '&tag=' . $tag2;
        $newUrl .= '&file4histos=ElectronMcSignalHistos.txt&release=&dataset=' . $tag3;
        $newUrl .= '&reference=&long_histo_name='.$long_histo_name.'&compFullFast=';
        echo '<td align="center" id="displayHistosLink"><span class="blueClass">';
        echo '<b>Display histos comparison (Releases)</b></span></td>';
        echo "</tr>";
        echo "</table>";/**/
        echo '</td>';
        echo '<td align="center">';
            $pict_name1 = $web_roots . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_1.png';
            $pict_name2 = $web_roots . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_2.png';
            $pict_name3 = $web_roots . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_3.png';
            $chemin_KS_eos = str_replace($racine_html, $racine_eos, $web_roots);
            if (file_exists($chemin_KS_eos . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_3.png')) {
                echo '<a href="' . $pict_name3 . '">';
                echo '<img class="image img" width="200" src="' . $pict_name3 . '" alt="" style="border: 2px solid blue;" ></a>';
            }
            else {
                if (file_exists($chemin_KS_eos . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_1.png')) {
                    echo '<a href="' . $pict_name1 . '">';
                    echo '<img class="image img" width="200" src="' . $pict_name1 . '" alt="" style="border: 2px solid blue;" ></a>';
                }
                if (file_exists($chemin_KS_eos . $actionFrom . '/pngs/maxDiff_comparison_' . $long_histo_name . '_2.png')) {
                    echo '<a href="' . $pict_name2 . '">';
                    echo '<img class="image img" width="200" src="' . $pict_name2 . '" alt="" style="border: 2px solid blue;" ></a>';
                }
            }
        echo '</td>';
        echo "<td>";
        //simPrintC('actionFrom', $actionFrom);
        $returnAddr = $web_roots .  "/index.php?actionFrom=" . $actionFrom . "#" . $short_histo_name ;
        imageSize($returnAddr);
        echo "<script type='text/javascript'>\n";
        echo '$(\'[size-choice="480"]\').addClass("Gras")';
        echo "</script>";
        echo "</td>";
    }

    echo "</tr>";
    echo "</table>";

    if (array_key_exists('choiceValue', $_REQUEST)) {
        $choiceValue = $_REQUEST['choiceValue'];
        if ($choiceValue != '')
        {
            echo "value  : " . $choiceValue . " <br>";
        }
    }

?>
</header>
