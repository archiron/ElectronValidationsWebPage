<header>
    <script>
        // introduced to correct tha "old" access with action instead of actionFrom.
        var url = window.location.href;
        if (url.includes('/index.php?action=/')) {
            //alert(url);
            var url = url.replace('/index.php?action=/', '/index.php?actionFrom=/');
            //alert(url);
            window.location.href = url;
        }
    </script>

    
    <?php
    session_start();
    if (array_key_exists('hiName', $_COOKIE)) {
        $hiName = $_COOKIE['hiName'];
    }
    else {
        $hiName = '';
    }
    $_SESSION["hiName"] = $hiName;
    //echo 'hiName : ' . $hiName . '<br>';
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; // url of the folder in order to use without checkKSNG.php
    
    $base_dir = __DIR__;
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';
    $web_roots = getRootPath($base_dir);

    $chemin = $web_roots;
    $chemin = str_replace("/Dev", "/Store/KS_Curves", $chemin);
    
    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    //echo $fileName . '<br>';
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);

    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $escaped_url = str_replace("/checkKSNG.php?action=/", "/", $escaped_url);
    //echo $escaped_url . '<br>';
    $classical_roots = htmlspecialchars( $web_roots, ENT_QUOTES, 'UTF-8' );
    $classical_roots = str_replace("/checkKSNG.php?action=/", "/", $classical_roots);
    $classical_path = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $classical_path = str_replace("/checkKSNG.php?action=/", "/", $classical_path);
    $previous_url = dirname($url);
    
    $dirsList_date = array(); // AC
    $dirsDevList_date = array(); // AC
    $filesList = array();
    $dirsDevList1 = array();
    $dirsDevList2 = array();
    $dirsDevList3 = array();

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
    $choiceValue='';
    $createF='';
    $basket='';
    $fileForHistos='';
    $curveChoice='';
    $sharedF='';
    $short_histo_name='';
    $histosFileName = '';
    $definitionFlag = false;
    $rel_num = '';
    $rel_princ = '';
    $rel_secon = '';
    
    $actionFrom = (isset($_REQUEST['actionFrom']) ? $_REQUEST['actionFrom'] : '');
    $curveChoice = (isset($_REQUEST['curveChoice']) ? $_REQUEST['curveChoice'] : '');
    $cchoice = (isset($_REQUEST['cchoice']) ? $_REQUEST['cchoice'] : '');
    if ($cchoice == '') {
        $cchoice = "diff";
    }
    if ( $curveChoice == '' ) // histos web page construction
    {
        $curveChoice = 'classic';
    }/**/
    
    //echo 'action : ' . $actionFrom . '<br>';
    //echo 'curve choice : ' . $curveChoice . '<br>';
    //echo 'cchoice : ' . $cchoice . '<br>';
    $old_escaped_url = $web_roots . "/" . $actionFrom;
    //echo $old_escaped_url . '<br>';
    if ($actionFrom != '') {
        $rels = explode('/', $actionFrom);
        $rel_princ = $rels[1];
        $rel_secon = $rels[2];
        //echo 'rel princ : ' . $rel_princ . ' - rel secon : ' . $rel_secon . '<br>';
        $rel_num = str_replace('CMSSW_', '', $rel_princ);
        $rel_num = str_replace('_', '', $rel_num);
        $rel_num = substr($rel_num, 0, 4);
    }
    // test for rel_secon
    $checkFlag = False;
    if ($rel_secon == 'Check') {
        $checkFlag = True;
    }

    echo "<table class=\"tab0\">";
    echo '<tr><td class="b0">';
    writeHeaderLinks($base_dir, $url);
    echo "</td>";

    echo "<td class=\"CtextAlign\">";
    echo "<b><span> " . "Release  : " . " </span></b>" . " \n";
    echo "<b><span class=\"darkBlueClass\"> " . $rel_secon . " </span></b>" . " \n";
    echo '&nbsp;-&nbsp;';
    echo "<b><span> " . "Release used for KS comparison : " . " </span></b>" . " \n";
    echo "<b><span class=\"blueClass\"> " . $rel_princ . " </span></b>" . " <br>\n";
    echo "</td>";
    echo "<td class=\"RtextAlign\">";
    $tmp_add = explode("/", $actionFrom);
    if (count($tmp_add) == 3) {
        $return_address = $web_roots . "/checkKSNG.php?actionFrom=/" . $tmp_add[1];
        echo '<a href=' . $return_address . '>BACK</a>' . "\n";
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";
    
    $_SESSION['url'] = $url;

    $chemin = $chemin . '/' . $actionFrom;
    $chemin_eos=str_replace($racine_html, $racine_eos, $chemin);
    //echo 'chemin : ' . $chemin . '<br>';
    //echo 'chemin_eos : ' . $chemin_eos . '<br>';

    // get the list of folders in Dev
    $devPath = str_replace($racine_html, $racine_eos, $web_rootsD);
    $filesDev1 = array_slice(scandir($devPath), 2);
    foreach ($filesDev1 as $key => $value)
    {
        if (is_dir($devPath . DIRECTORY_SEPARATOR . $value))
        {
            if ( substr($value, 0, 7) != '.sys.v#' ) {
                //echo $value . '<br>' . "\n";
                //echo substr($value, 0, 7) . '<br>' . "\n";
                $dirsDevList1[] = $value;
            }
        }
    }
    foreach ($dirsDevList1 as $key => $value2)
    {
        $devPath2 = $devPath . '/' . $value2;
        //echo $devPath2 . '<br>' . "\n";
        $filesDev2 = array_slice(scandir($devPath2), 2);
        foreach ($filesDev2 as $key => $value2)
        {
            if (is_dir($devPath2 . DIRECTORY_SEPARATOR . $value2))
            {
                $dirsDevList2[] = $devPath2 . DIRECTORY_SEPARATOR . $value2;
            }
        }
    }
    foreach ($dirsDevList2 as $key => $value3)
    {
        $devPath3 = $value3 . '/RECO-RECO_ZEE_14/DBox';
        if (is_dir($devPath3))
        {
            $dirsDevList3[] = $devPath3;
        }
    }
    foreach ($dirsDevList3 as $key => $value4)
    {
        
        //echo $value4 . ' / ' . @date('F d, Y, H:i:s', filemtime($value4)) . '<br>';
        $tmp_03 = str_replace($devPath . '/', '', $value4);
        $tmp_03 = str_replace('/RECO-RECO_ZEE_14/DBox', '', $tmp_03);
        $xx = explode('/', $tmp_03);
        $dirsDevList_date[] = ['CMSSW_' . $xx[0], $xx[1], filemtime($value4)];
    }/**/
    // end of the list of folders in Dev
    //prePrint('filesDev1',$filesDev1);
    //prePrint('filesDev2',$filesDev2);
    //prePrint('filesDev3',$filesDev3);

    $flag_CMSSW_12_1_0_pre5 = False;
    if ( $rel_princ == 'CMSSW_12_1_0_pre5' ) {
        $flag_CMSSW_12_1_0_pre5 = True;
        //echo $flag_CMSSW_12_1_0_pre5 . '<br>';
    }
    // looking into Store/KS_Curves
    $files = array_slice(scandir($chemin_eos), 2);
    // Fill arrays with dirs & files
    foreach ($files as $key => $value)
    {
        //echo $value . '<br>';
        if (is_dir($chemin_eos . DIRECTORY_SEPARATOR . $value))
        {
            //echo $value . ' is dir<br>';
            $val_num = str_replace('CMSSW_', '', $value);
            $val_num = str_replace('_', '', $val_num);
            $val_num = substr($val_num, 0, 4);
            //echo $val_num . ' is dir<br>';
            if ( (intval($val_num) >= 1250) || ( $value == 'CMSSW_12_1_0_pre5' ) || $flag_CMSSW_12_1_0_pre5 ) { // je sais, ce n'est pas beau, mais c'est comme ça !
                if ( substr($value, 0, 5) !== '.sys.') {
                    $dirsList_date[] = $value;
                }
            }
        }
        elseif (is_file($chemin_eos . DIRECTORY_SEPARATOR . $value))
        {
            $filesList[] = $value;
            //echo $value . ' is file<br>';
        }
        else
        {
            echo "unknown type : $value<br />";
        }
    }
    foreach ($filesList as $key => $value)
    {
        //echo $value . '<br>';
        if ( $value == "definitions.txt" )
        {
            $definitionFlag = True;
            $defsFileName = $value;
            $definitions = file($chemin_eos . "/" . $defsFileName);

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
    //echo 'cumul list : ' . $nb_cumulative . ' - KS ttlDiff list : ' . $nb_KS_ttlDiff . '<br>';
    //echo 'KSComp list : ' . $nb_KSComp . '<br>';
    //echo 'cumul0 list : ' . $nb_cumul0 . ' - n0 list : ' . $nb_n0 . '<br>';
    //echo 'gif list : ' . $nb_gif . '<br>';
    //echo 'Check list : ' . $nb_Check . '<br>';
    if ( intval($rel_num) < 1250 ) {
        $KSCompFlag = True;
    }
    $DBoxflag = True;

    //prePrint('dirsList_date', $dirsList_date);

    echo '<table class="tab0">'; //  border="2"
    echo '<tr>';
    echo'<td>';
    $textValues = displayVariablesValues2($url, $actionFrom, $cchoice, $basket, $createF, $fileForHistos, $curveChoice, $sharedF, $short_histo_name);
    echo '<p>+ Variables values</p>';
    echo '<span valInfo="t12"></span>';
    echo'</td>';
    
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
    }
    $histoArray = createHistoArray($lineHisto);
    $clefs = array_keys($histoArray);
    if ( $choiceValue != '' ) {
        $histoArray = cleanHistoArray($histoArray, $clefs, $choiceValue);
    }
    $nb_bgr = array();
    for ($ic = 0; $ic < count($clefs); $ic++) {
        foreach ($histoArray[$clefs[$ic]] as $elem) {
            list ($short_histo_name, $short_histo_names, $histo_positions) = shortHistoName($elem); 
            //list ($after, $before, $common) = testExtension($short_histo_name, $histoPrevious);
            $classColor = "lightGrey";
            $filehistoName = $chemin_eos . "/" . $short_histo_name . '.txt'; // . 'DBox/' 
            //echo $filehistoName . "<br>";
            //$display_dataset = FALSE;
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
                $classColor = getClassColor_cchoice($cchoice, $lRead1, $lRead2);
                if ($classColor == 'blueClass') {
                    $nb_bgr[0] +=1;
                }
                elseif ($classColor == 'greyClass') {
                    $nb_bgr[1] += 1;
                }
                else { # red
                    $nb_bgr[2] += 1;
                }
            }
            /*else {
                echo 'no ' . $filehistoname . "<br>";
            }*/
        }
    }
    echo'<td class="redClass LtextAlign">';
    echo "<b><font style=\"font-size:30px\">electron validation: check KS NG</font></b>";
    echo '<br>';
    echo'</td>';
    echo "</tr>";
    echo "</table>";

    echo '<table class="tab0">'; //  border="2" border=\"1\"
    /*echo '<tr>';
    echo'<td>';
    filter($url, $image_loupe);
    echo "</td>";
    echo '<td>';
    if ($definitionFlag) {
        if ($nb_bgr[0] > 0) {
            echo '<span class="blueClass">nb blue : </span>' . $nb_bgr[0];
        }
        else {
            echo '<span class="blueClass">nb blue : </span>0';
        }
        if ($nb_bgr[1] > 0) {
            echo ', <span class="greyClass">nb grey : </span>' . $nb_bgr[1];
            } 
            else {
            echo ', <span class="greyClass">nb grey : </span>0';
        }
            if ($nb_bgr[2] > 0) {
            echo  ', <span class="redClass">nb red : </span>' . $nb_bgr[2];
            }
            else {
            echo '<span class="redClass">, nb red : </span>0';
        }
        echo "</td>";
        echo "<td>";
        $resume = file($chemin_eos . '/histo_resume.txt');
        $lineEnd = $resume[count($resume)-1];
        $tmp_04 = explode(" ttl ", $lineEnd);
        $tmp_05 = explode(" : ", $tmp_04[0]);
        $line = $tmp_05[0] . " curves : " . "<b><span class=\"redClass\">" . $tmp_05[1] . " </span></b>";
        $tmp_05 = explode(" - ", $tmp_04[1]);
        $line .= $tmp_05[0] . " - " . "<b><span class=\"greenClass\">" . $tmp_05[1] . " </span></b>";
        $line .= 'green';
        echo $line . '<br>';
    }
    echo "</td>";
    echo "</tr>";*/

    if ($definitionFlag) {
        echo "<tr>";
        echo "<td>";
        filter($url, $image_loupe);
        echo "<br>";
        $rootFile1 = $definitions[3]; 
        $tmp_01 = explode("__", $rootFile1);
        $tmp_02 = explode("-", $tmp_01[2]);
        $rootFile2 = $tmp_01[0] . "__<b><span class=\"greenClass\">" . $tmp_01[1] . "</span></b>__";
        if (count($tmp_02) == 3) {
            $rootFile2 .= $tmp_02[0] . "-" . "<b><span class=\"redClass\">" . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
        }
        else {
            $rootFile2 .= $tmp_02[0] . "-" . "<b><span class=\"redClass\">" . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
        }
        $rootFile3 = "<b><span class=\"redClass\"> " . $definitions[1] . " " . $definitions[2] . " </span></b>" . " : " . $rootFile2 . " <br>\n";
        echo $rootFile3;
        $rootFile1 = $definitions[6]; 
        $tmp_01 = explode("__", $rootFile1);
        $tmp_02 = explode("-", $tmp_01[2]);
        $rootFile2 = $tmp_01[0] . "__<b><span class=\"greenClass\">" . $tmp_01[1] . "</span></b>__";
        if (count($tmp_02) == 3) {
            $rootFile2 .= $tmp_02[0] . "-" . "<b><span class=\"blueClass\">" . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
        }
        else {
            $rootFile2 .= $tmp_02[0] . "-" . "<b><span class=\"blueClass\">" . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
        }
        $rootFile3 = "<b><span class=\"redClass\"> " . $definitions[4] . " " . $definitions[5] . " </span></b>" . " : " . $rootFile2 . " <br>\n";
        echo $rootFile3;

        echo "</td>";
        echo '<td style="text-align: middle;">';// vertical-align: middle;
        if ($nb_bgr[0] > 0) {
            echo '<span class="blueClass"><b>nb blue : </b></span>' . $nb_bgr[0];
        }
        else {
            echo '<span class="blueClass"><b>nb blue : </b></span>0';
        }
        if ($nb_bgr[1] > 0) {
            echo ', <span class="greyClass"><b>nb grey : </b></span>' . $nb_bgr[1];
            } 
            else {
            echo ', <span class="greyClass"><b>nb grey : </b></span>0';
        }
            if ($nb_bgr[2] > 0) {
            echo  ', <span class="redClass"><b>nb red : </b></span>' . $nb_bgr[2];
            }
            else {
            echo '<span class="redClass"><b>, nb red : </b></span>0';
        }
        echo "<br>";
        $resume = file($chemin_eos . '/histo_resume.txt');
        $lineEnd = $resume[count($resume)-1];
        $tmp_04 = explode(" ttl ", $lineEnd);
        $tmp_05 = explode(" : ", $tmp_04[0]);
        $line = "<b>" . $tmp_05[0] . " curves : </b>" . "<b><span class=\"redClass\">" . $tmp_05[1] . " </span></b>";
        $tmp_05 = explode(" - ", $tmp_04[1]);
        $line .= $tmp_05[0] . " - " . "<b><span class=\"greenClass\">" . $tmp_05[1] . " </span></b>";
        $line .= 'green';
        echo $line . '<br><br>';

        echo "<table border=\"0\" class=\"CtextAlign\">";
        echo "<tr>";
        $hiName = $_COOKIE['hiName'];
        $_SESSION["hiName"] = $hiName;
        if ($cchoice == "diff"){
            echo "<td><a href=\"$web_roots/checkKSNG.php?actionFrom=" . $actionFrom . '&cchoice=diff&curveChoice=' . $curveChoice . '#' . '"><b><span class="blueClass">Diff</span></b></a></td>';//. $hiName
        }
        else {
            echo "<td><a href=\"$web_roots/checkKSNG.php?actionFrom=" . $actionFrom . '&cchoice=diff&curveChoice=' . $curveChoice . '#' . '"><span class="blueClass">Diff</span></a></td>';//. $hiName
        }
        echo '<td>&nbsp;:&nbsp;</td><td> <span class="blueClass">0&le;diff&le;5 %</span> - <span class="greyClass">5&lt;diff&le;10 %</span> - <span class="redClass"> diff&gt;10 %</span><br></td>';/**/
        echo "</tr>";
        echo "<tr>";
        if ($cchoice == "pValue"){
            echo "<td><a href=\"$web_roots/checkKSNG.php?actionFrom=" . $actionFrom . '&cchoice=pValue&curveChoice=' . $curveChoice . '#' . '"><b><span class="blueClass">p-Value</span></b></a></td>';//. $hiName
        }
        else {
            echo "<td><a href=\"$web_roots/checkKSNG.php?actionFrom=" . $actionFrom . '&cchoice=pValue&curveChoice=' . $curveChoice . '#' . '"><span class="blueClass">p-Value</span></a></td>';//. $hiName
        }
        echo '<td>&nbsp;:&nbsp;</td><td> <span class="redClass"> pV&lt;0.05</span> - <span class="greyClass">0.05&lt;pV&le;0.95</span> - <span class="blueClass">0.95&lt;pV&le;1.</span><br></td>';/**/
        echo "</tr>";
        echo "</table>";
        echo "</td>" . "\n";
        
        echo "<td>";// class=\"RtextAlign\"
        /*$resume = file($chemin_eos . '/histo_resume.txt');
        $lineEnd = $resume[count($resume)-1];
        $tmp_04 = explode(" ttl ", $lineEnd);
        $tmp_05 = explode(" : ", $tmp_04[0]);
        $line = $tmp_05[0] . " curves : " . "<b><span class=\"redClass\">" . $tmp_05[1] . " </span></b>";
        $tmp_05 = explode(" - ", $tmp_04[1]);
        $line .= $tmp_05[0] . " - " . "<b><span class=\"greenClass\">" . $tmp_05[1] . " </span></b>";
        $line .= 'green';
        echo $line . '<br>';*/

        if ( $rel_princ != '' && $rel_secon != '' && $rel_secon != 'Check'){
            echo '<table border="1" class="clickable curveChoice"><tr>';
            echo '<td colspan="4" id="book" class="CtextAlign" title="Click on text to change one picture, Click on all to change all pictures">';
            echo "<b>Curves</b>";
            echo "</td></tr><tr>";
            if ( $gifFlag ) {
                echo '<td class="CtextAlign" curve-choice="classic">classic</td>';
                echo '<td curve-choice-all="clAll">all</td>' . "\n";
            }
            if ($KS_ttlDiffFlag) {
                echo "</tr><tr>";
                echo '<td class="CtextAlign" curve-choice="ttlDiff">KS-ttlDiff</td>';
                echo '<td curve-choice-all="ttAll">all</td>' . "\n";
            }
            echo "</tr><tr>";
            /*if ($cumulativeFlag){
                echo '<td class="CtextAlign" curve-choice="cumul">cumulatives</td>';
                echo '<td  curve-choice-all="cuAll">all</td>' . "\n";
            }*/
            if ( intval($rel_num) >= 1250 || $flag_CMSSW_12_1_0_pre5 ) {
                echo "</tr><tr>";
                echo '<td class="CtextAlign" curve-choice="comp">KSCompHisto</td>';
                echo '<td  curve-choice-all="coAll">all</td>' . "\n";
            }
            echo "</tr></table>";
        }
        echo "</td>";
        echo "</tr>";
    }
    else {
        echo "<tr>";
        echo "<td width=50%></td><td width=30%></td><td class=\"RtextAlign\">";
        if ( $rel_princ != '' && $rel_secon != '' && $rel_secon != 'Check'){
            echo "<table border=\"0\"><tr>";
            echo "<td colspan=\"4\">";
            echo "<b>Curves : &nbsp;</b>";
            if ( $gifFlag ) {
                if (($curveChoice == 'classic') || ($curveChoice == '')){
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=classic&cchoice=' . $cchoice . '#' . $hiName . '"><b>classic</b></a>' . "\n";
                }
                else {
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=classic&cchoice=' . $cchoice . '#' . $hiName . '">classic</a>' . "\n";
                }
            }
            if ($KS_ttlDiffFlag) {
                if ($curveChoice == 'ttlDiff') {
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=ttlDiff&cchoice=' . $cchoice . '#' . $hiName . '"><b>KS-ttlDiff</b></a>' . "\n";
                }
                else {
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=ttlDiff&cchoice=' . $cchoice . '#' . $hiName . '">KS-ttlDiff</a>' . "\n";
                }
            }
            /*if ($cumulativeFlag){
                if ($curveChoice == 'cumul'){
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=cumul&cchoice=' . $cchoice . '#' . $hiName . '"><b>cumulatives</b></a>' . "\n";
                }
                else {
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=cumul&cchoice=' . $cchoice . '#' . $hiName . '">cumulatives</a>' . "\n";
                }
            }*/
            if ( intval($rel_num) >= 1250 || $flag_CMSSW_12_1_0_pre5 ) {
                if ($curveChoice == 'comp'){
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=comp&cchoice=' . $cchoice . '#' . $hiName . '"><b>KSCompHisto</b></a>' . "\n";
                }
                else {
                    echo '<a href="' . $web_roots . '/checkKSNG.php?&actionFrom =' . $actionFrom  . '&curveChoice=comp&cchoice=' . $cchoice . '#' . $hiName . '">KSCompHisto</a>' . "\n";
                }
            }
            echo "</tr></table>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

    if (array_key_exists('choiceValue', $_REQUEST)) {
        $choiceValue = $_REQUEST['choiceValue'];
    }
    if ($choiceValue != '')
    {
        echo "value  : " . $choiceValue . " <br>";
    }
    
?>
</header>
