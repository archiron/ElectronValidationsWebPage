<header>
    
    <?php
    session_start();
    
    $actionFrom = (isset($_REQUEST['actionFrom']) ? $_REQUEST['actionFrom'] : '');
    $url = (isset($_REQUEST['url']) ? $_REQUEST['url'] : '');
    $curveChoice = (isset($_REQUEST['curveChoice']) ? $_REQUEST['curveChoice'] : '');
    $cchoice = (isset($_REQUEST['cchoice']) ? $_REQUEST['cchoice'] : '');
    $short_histo_name = (isset($_REQUEST['short_histo_name']) ? $_REQUEST['short_histo_name'] : '');
    //echo 'url : ' . $url . "<br>";
    $histoName = explode('.', end(explode('/', $url)))[0];
    //echo 'action : ' . $action . "<br>";
    //echo 'actionFrom : ' . $actionFrom . "<br>";
    //echo 'curveChoice : ' . $curveChoice . "<br>";
    //echo 'cchoice : ' . $cchoice . "<br>";
    //echo 'histoName : ' . $histoName . "<br>";
    
    $base_dir = __DIR__;
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';
    $web_roots = getRootPath($base_dir);

    $chemin0 = $web_roots;
    $chemin0 = str_replace("/Dev", "/Store/KS_Curves", $chemin0);
    $chemin_eos=str_replace($racine_html, $racine_eos, $url);
    //echo 'nom histo : ' . $short_histo_name . "<br>";

    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);
    $_SESSION['localFileForHistos_eos'] = $fileName_eos;
    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $escaped_url = str_replace("/checkKS.php?action=/", "/", $escaped_url);
    //echo "escap : ".$escaped_url . "<br>";
    $classical_roots = htmlspecialchars( $web_roots, ENT_QUOTES, 'UTF-8' );
    $classical_roots = str_replace("/checkKS.php?action=/", "/", $classical_roots);
    $classical_path = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    //echo "classical_path : ".$classical_path . "<br>";
    $classical_path = str_replace("/checkKS.php?action=/", "/", $classical_path);
    $previous_url = dirname($url);
    
    $dirsList = array();
    $dirsList3 = array();
    $dirsList_date = array(); // AC
    $filesList = array();
    $lineHisto = array();
    $pictsDir = False;
    $indexHtml = False;
    $DBoxflag = False;

    $cumulativeFlag = False; // cumulative_curve_
    $KS_ttlDiffFlag = False; // KS-ttlDiff_3
    $KSCompFlag = False; // KSComp
    $cumul0Flag = False; // cum0
    $n0Flag = False; // n0
    $gifFlag = False; // gif pictures
    $cumulativeList = array();
    $KS_ttlDiffList = array();
    $KSCompList = array();
    $ChekList = array();
    $cumul0List = array();
    $n0List = array();
    $gifList = array();
    $ChekList = array();
    
    $rels = explode('/', $actionFrom );
    $rel_princ = $rels[1];
    $rel_secon = $rels[2];
    $rel_num = str_replace('CMSSW_', '', $rel_princ);
    $rel_num = str_replace('_', '', $rel_num);
    $rel_num = substr($rel_num, 0, 4);

    echo "<table class=\"tab0\">";
    echo '<tr><td class="b0">';
    writeHeaderLinks($base_dir, $url);
    echo "</td>";

    echo '<td><th><span class="redClass">';
    echo "<b>electron validation: Kolmogorov - Smirnov</b>";
    echo "</span>";
    echo '<span class="darkBlueClass">';
    echo " / ";
    echo "<b>" . $short_histo_name  . "</b>";
    echo "</span></th></td>";
    echo "<td class=\"RtextAlign\">";

    $returnAddr = $web_roots .  "/checkKS.php?actionFrom=" . $actionFrom   . "&curveChoice=" . $curveChoice . "&cchoice=" . $cchoice . '#' . $short_histo_name;
    echo "<a href=\"" . $returnAddr . "\">BACK</a>";
    
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";
    
    $_SESSION['url'] = $url;

    $chemin = $chemin0 . '/' . $actionFrom ;
    $chemin3 = $chemin0 . '/' . explode('/', $actionFrom)[1];
    $chemin_eos2=str_replace($racine_html, $racine_eos, $chemin);
    $chemin_eos3=str_replace($racine_html, $racine_eos, $chemin3);
    //echo 'chemin 1 : ' . $chemin . '<br>';
    //echo 'chemin 3 : ' . $chemin3 . '<br>';
    //echo 'chemin 2 : ' . $chemin_eos2 . '<br>';
    //echo 'chemin 3 : ' . $chemin_eos3 . '<br>';
    
    $files = array_slice(scandir($chemin_eos2), 2);
    $files3 = array_slice(scandir($chemin_eos3), 2);
    
    // Fill arrays with dirs & files
    foreach ($files as $key => $value)
    {
        //echo $value . '<br>';
        if (is_dir($chemin_eos2 . DIRECTORY_SEPARATOR . $value))
        {
            //echo $value . ' is dir<br>';
            $dirsList[] = $value;
            if ( substr($value, 0, 5) !== '.sys.') {
                $dirsList_date[] = $value;
            }
        }
        elseif (is_file($chemin_eos2 . DIRECTORY_SEPARATOR . $value))
        {
            $filesList[] = $value;
            //echo $value . ' is file<br>';
        }
        else
        {
            echo "unknown type : $value<br />";
        }
    }
    //prePrint('dirsList', $dirsList);

    foreach ($files3 as $key3 => $value3)
    {
        //echo $value3 . '<br>';
        if (is_dir($chemin_eos3 . DIRECTORY_SEPARATOR . $value3))
        {
            //echo substr($value3, 0, 5) . '<br>';
            if ( substr($value3, 0, 5) == 'CMSSW') {
                $dirsList3[] = $value3;
            }
        }
        else
        {
            echo "unknown type : $value<br />";
        }
    }
    //prePrint('dirsList', $dirsList3);

    foreach ($dirsList as $key => $value)
    {
        //echo $value . '<br>';
        if ( $value == "definitions.txt" )
        {
            $definitionFlag = True;
            $defsFileName = $value;
        }
        if ( $value == "ElectronMcSignalHistos.txt" )
        {
            $histosFlag = True;
            $histosFileName = $value;
        }
        if ((strpos($value, 'cumulative_curve_') !== false)) {
            $cumulativeList[] = $value;
            //echo $value . ' is cumulative<br>';
        }
        if ((strpos($value, 'KS-ttlDiff_') !== false)) {
            $KS_ttlDiffList[] = $value;
            //echo $value . ' is KS-ttlDiff<br>';
        }
        if ((strpos($value, 'KSCompHisto_') !== false)) {
            $KSCompList[] = $value;
            //echo $value . ' is KS-ttlDiff<br>';
        }
        if ((strpos($value, '_cum0.png') !== false)) {
            $cumul0List[] = $value;
            //echo $value . ' is cum0.png<br>';
        }
        if ((strpos($value, '_n0.png') !== false)) {
            $n0List[] = $value;
            //echo $value . ' is n0.png<br>';
        }
        if ((strpos($value, '.gif') !== false)) {
            $gifList[] = $value;
            //echo $value . ' is gif file<br>';
        }
        if ($checkFlag) {
            if ((strpos($value, 'line-ttlDiff_') !== false)) {
                $CheckList[] = $value;
                //echo $value . ' is KS-ttlDiff<br>';
            }
        }
    }
    
    $nb_cumulative = count($cumulativeList);
    $nb_KS_ttlDiff = count($KS_ttlDiffList);
    $nb_KSComp = count($KSCompList);
    $nb_cumul0 = count($cumul0List);
    $nb_n0 = count($n0List);
    $nb_gif = count($gifList);
    if ($checkFlag) {
        $nb_Check = count($CheckList);
    }
    if ( $nb_cumulative > 0 ) {
        $cumulativeFlag = True;
    }
    if ( $nb_KS_ttlDiff > 0 ) {
        $KS_ttlDiffFlag = True;
    }
    if ( $nb_KSComp > 0 ) {
        $KSCompFlag = True;
    }
    if ( $nb_cumul0 > 0 ) {
        $cumul0Flag = True;
    }
    if ( $nb_n0 > 0 ) {
        $n0Flag = True;
    }
    if ( $nb_gif > 0 ) {
        $gifFlag = True;
    }

    if ( intval($rel_num) < 1250 ) {
        $KSCompFlag = True;
    }

    echo "<table class=\"tab0\" border=\"0\">";
    echo '<tr><td>';// class="b3"
    filter($url, $image_loupe);
    echo "</td>";

    echo "<td class=\"CtextAlign\">";
    echo "<b><span> " . "Release  : " . " </span></b>" . " \n";
    echo "<b><span class=\"darkBlueClass\"> " . $rel_secon . " </span></b>" . " \n";
    echo '&nbsp;-&nbsp;';
    echo "<b><span> " . "Release used for KS comparison : " . " </span></b>" . " \n";
    echo "<b><span class=\"blueClass\"> " . $rel_princ . " </span></b>" . " <br>\n";
    echo "</td>";

    echo "</tr>";
    echo "</table>";

    $choiceValue = $_REQUEST['choiceValue'];
    if ($choiceValue != '')
    {
        echo "value  : " . $choiceValue . " <br>";
    }
    
    echo "<table class=\"tab0\" border=\"0\">";
    echo '<tr><td>';// class="b3"
    $textValues = displayVariablesValues2($url, $actionFrom, $cchoice, $basket, $createF, $fileForHistos, $curveChoice, $sharedF, $short_histo_name);
    echo '<p>+ Variables values</p>';
    echo '<span valInfo="t12"></span>';
    echo "</td>";

    echo "<td class=\"CtextAlign\">";
    echo '<table border="1" class="clickable displayChoice" style="margin-left:auto;margin-right:auto" width="700">' . "\n";

    echo '<tr><td class="CtextAlign blueClass" display-choice="mono" title="classique" " width = "50%" style="font-size:16px;">' . "\n";
    echo 'Comparaison ' . substr($rel_secon,6) . ' - ' . substr($rel_princ,6) . "\n";
    echo '</td><td class="CtextAlign blueClass" display-choice="comp" title="comparaison de toutes les releases" >' . "\n";
    echo 'Comparaison pour toutes les releases' . "\n";
    echo '</td>' . "\n"; 
    echo '</tr>' . "\n";
    echo '</table>' . "\n";
    echo '<br>' . "\n";

    echo '<table border="0" style="margin-left:auto;margin-right:auto;"><tr><td style="font-weight: normal; font-size:16px;">';
    echo 'the reference is in <span class="greenClass">green</span> and the choosen release in <span class="blueClass">blue</span>';
    echo '</td></tr></table>';

    echo "</td>";

    echo "</tr>";
    echo "</table>";

    
?>
</header>
