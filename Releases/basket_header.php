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
        //echo 'pictFormat OK in index.php' . '<br>';
        //echo $_SESSION['pictFormat'];
        $pictsValue=$_SESSION['pictFormat'] . "s"; // gifs/pngs
        $pictsExt="." . $_SESSION['pictFormat']; // .gif/.png
        simPrint('pictsValue', $pictsValue);
        simPrint('pictsExt', $pictsExt);
    }

    $chemin = $web_roots;
    
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; # url of the folder in order to use without index.php
    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);
    //simPrint('fileName_eos', $fileName_eos);
    $_SESSION['localFileForHistos_eos'] = $fileName_eos;
    if (empty($_SESSION['fileForHistos_eos'])){
        $_SESSION['fileForHistos_eos'] = $_SESSION['localFileForHistos_eos'];
    }
    
    $corresp = 0;
    //$url_0 = $web_roots . "/addRemove.php";
    $url = (isset($_REQUEST['url']) ? $_REQUEST['url'] : '');
    $actionFrom = (isset($_REQUEST['actionFrom']) ? $_REQUEST['actionFrom'] : '');
    $basket = (isset($_REQUEST['basket']) ? $_REQUEST['basket'] : '');
    $createF = (isset($_REQUEST['createF']) ? $_REQUEST['createF'] : '');
    $short_histo_name  = (isset($_REQUEST['short_histo_name']) ? $_REQUEST['short_histo_name'] : '');
    if (!empty($createF)) {
        echo $web_roots . "/" . getSharedFileName(session_id());
        $fSharedName = $web_roots . "/" . getSharedFileName(session_id());
        $fSharedName_eos=str_replace($racine_html, $racine_eos, $fSharedName);
        echo $fSharedName_eos;
        $fShared = fopen($fSharedName_eos, "w");
        fclose($fShared);
    }
    $fileForHistos = (isset($_REQUEST['sharedF']) ? $_REQUEST['sharedF'] : '');
    if (!empty($fileForHistos)) {
        echo "non empty fileForHistos<br>\n";
        $fileForHistos_eos=str_replace($racine_html, $racine_eos, $fileForHistos);
        $_SESSION['fileForHistos_eos'] = $fileForHistos_eos;
        echo "You are using a <font color=\"red\"><b>shared</b></font> file : " . getReducedName($fileForHistos_eos) . "<br>\n";
    }
    if (empty($actionFrom)) {
        $actionFrom = explode('actionFrom=', $url)[1];
    }
    $aFrom = explode("/", $actionFrom);

    if (empty($url)) {
        $url = $_SESSION['url'];
    }
    else {
        $_SESSION['url'] = $url;
    }
    
    $url_http = 'https:' . $url;
    $histoName = end(explode('/', $url));
    //simPrint('histo name', $histoName);
    
    $chemin = $chemin . '/' . $actionFrom;
    //simPrint('$chemin', $chemin);
    $chemin_eos=str_replace($racine_html, $racine_eos, $chemin);
    //simPrint('$chemin_eos', $chemin_eos);
    
    $chemin_eos_base = str_replace($racine_html, $racine_eos, $web_roots);
    //simPrint('$chemin_eos', $chemin_eos_base);
    
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
    
    echo "<table class=\"tab0\">";
    echo '<tr><td class="b0">';
    writeHeaderLinks($base_dir, $url);
    echo "</td>";

    echo '<th class="redClass">';
    echo "<b>electron validation: signal</b>";
    echo "</th>";
    echo "<td class=\"RtextAlign\">";
    //echo "<a href=\"$web_roots/index.php\">Back to roots</a>";
    if ($basket == 'display') {
        echo "<a href=\"" . $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=work&actionFrom=" . $actionFrom . "\">BACK</a>";
        echo "&nbsp; - &nbsp;\n";
    }
    elseif ($basket == 'view') {
        ;
    }
    elseif ($basket == 'share') {
        echo "<a href=\"$web_roots/index.php\?actionFrom=\"" . $actionFrom . ">Back to roots</a>";
        echo "&nbsp; - &nbsp;\n";
    }
    else { // work
        if ( $short_histo_name != '' ) {
            echo "<a href=\"" . $web_roots . "/basket.php?short_histo_name=" . $short_histo_name   . "&basket=view&actionFrom=" . $actionFrom . "\">BACK</a>";
        echo "&nbsp; - &nbsp;\n";
        }
        else {
            echo "<a href=\"" . $web_roots . "/index.php?actionFrom=" . $actionFrom . "&cchoice=diff\">BACK</a>";
            echo "&nbsp; - &nbsp;\n";
        }
    }
    //echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"$web_roots/basket.php?action=" . $short_histo_name   . "&url=" . $url . "&basket=work&actionFrom=" . $actionFrom . "\">Basket</a>" . "\n";
    echo "&nbsp; - &nbsp;\n";
    echo "<a href=\"$web_roots/basket.php?action=" . $short_histo_name   . "&url=" . $url . "&basket=share&actionFrom=" . $actionFrom . "\">Use a shared file</a>" . "\n";
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
    
    $lineHisto = [];
    if (array_key_exists('fileForHistos_eos', $_SESSION)) {
        //echo "array key fileForHistos_eos exist !<br>\n";
        $file = $_SESSION['fileForHistos_eos'];
        simPrint("file", $file); // "$_SESSION['fileForHistos_eos']", $_SESSION['fileForHistos_eos']
        if ( file_exists($file) ) {
            //echo $file . " exist !<br>\n";
            $handleBasket = fopen($file, "r");
            if ($handleBasket)
            {
                while(!feof($handleBasket))
                {
                    $tmp = fgets($handleBasket);
                    $tmp = str_replace(array("\r", "\n"), '', $tmp);
                    $lineHisto[] = $tmp;
                    if ($tmp == $url) {
                        $corresp = 1;
                    }
                }
                fclose($handleBasket);
            }
            else {
                echo "can not open " . $file . "<br>\n";
            }
        }
        else {
            echo $file . " does not exist. Create it<br>\n";
            fopen($file, "w");
            //fclose($file);
        }/**/
    }
    $Nlinks = count($lineHisto);
    
    echo "<table class=\"tab0\">";
    echo '<tr>';// class="b3"

    if ($basket == "view") {
        echo '<td class="CtextAlign">';
        echo '<table class="clickable Releases" width="270px" border="1">';
        echo '<tr>';
        echo '<td align="center" id="Histos"><span class="blueClass"><b>Press here to display Releases array</b></span></td>';
        echo "</tr>";
        echo "</table>";/**/
        /*echo '<table width="250px" border="1">';
        echo '<tr>';
        echo '<td class="CtextAlign">';
        echo '<input type="text" id="filter">';
        echo '</td>';
        echo "</tr>";
        echo "</table>";*/
        echo '</td>';
        echo "<td>";
        $returnAddr = $web_roots .  "/index.php?actionFrom=" . $actionFrom . "#" . $short_histo_name ;
        imageSize($returnAddr);
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
