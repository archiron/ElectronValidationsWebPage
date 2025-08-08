<header>
    <script>
        // introduced to correct the "old" access with action instead of actionFrom.
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
    //echo "session id : " . session_id() . " <br>\n";
    //phpinfo();

    $base_dir = __DIR__;
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';
    $web_roots = getRootPath($base_dir);
    
    $chemin = $web_roots;
    //echo 'chemin : ' . $chemin . '<br>';
    
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; // url of the folder in order to use without index.php
    //simPrint('url', $url);

    $url_graph = explode('&', $url)[0];
    $url_graph = str_replace('/index.php?actionFrom=/', '/', $url_graph);
    
    //prePrint('url', parse_url($url));
    $url_from = $_SERVER['HTTP_REFERER'];
    //simPrint('url from', $url_from);
    $url_tmp = explode('?', $url_from)[0];
    $url_tmp = end(explode('/', $url_tmp));
    $url_flag = false;
    if ($url_tmp == 'basket.php') {
        $url_flag = true;
    }
 
    $histoSize = 440; // 200 440
    //simPrint('histo size', $histoSize);
    if ($url == '//cms-egamma.web.cern.ch/validation/Electrons/Dev/index.php') {
        session_unset(); // back to beginning & free $_SESSION
    }
    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);
    $_SESSION['localFileForHistos_eos'] = $fileName_eos;
    $_SESSION['fileForHistos_eos'] = $_SESSION['localFileForHistos_eos'];
    $classical_roots = htmlspecialchars( $web_roots, ENT_QUOTES, 'UTF-8' );
    $classical_roots = str_replace("/index.php?actionFrom=/", "/", $classical_roots);
    $classical_path = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $classical_path = str_replace("/index.php?actionFrom=/", "/", $classical_path);
    $previous_url = dirname($url);
    
    if ( !file_exists($fileName_eos) ) {
        //echo $file . " does not exist. Create it<br>\n";
        fopen($fileName_eos, "w");
    }/**/

    $dirsList = array();
    $dirsList_date = array(); // AC
    $filesList = array();
    //$lineHisto = array();
    $lineHisto1 = array();
    $pictsDir=False;
    $pictsValue="gifs"; // default
    $pictsExt=".gif"; // default
    $indexHtml=False;
    $histosFile=False;
    $DBoxflag=False;
    $choiceValue='';
    $basket='';
    $fileForHistos='';
    $curveChoice='';
    $sharedF='';
    $short_histo_name='';
    
    $actionFrom = (isset($_REQUEST['actionFrom']) ? $_REQUEST['actionFrom'] : '');
    $cchoice = (isset($_REQUEST['cchoice']) ? $_REQUEST['cchoice'] : '');
    if ($cchoice == '') {
        $cchoice = "diff";
    }

    $url_http = 'https:' . $url;
    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $escaped_url = str_replace("/index.php?actionFrom=/", "/", $escaped_url);
    //echo "escap : ".$escaped_url . "<br>";
    $escaped_url = str_replace("&amp;cchoice=diff", "", $escaped_url);
    $escaped_url = str_replace("&amp;cchoice=pValue", "", $escaped_url);

    $_SESSION['url'] = $url_http;

    $chemin = $chemin . '/' . $actionFrom;
    $chemin_eos=str_replace($racine_html, $racine_eos, $chemin);
    
    $files = array_slice(scandir($chemin_eos), 2);
    
    // Fill arrays with dirs & files
    foreach ($files as $key => $value)
    {
        if (is_dir($chemin_eos . DIRECTORY_SEPARATOR . $value))
        {
            $dirsList[] = $value;
            if ( substr($value, 0, 5) !== '.sys.') {
                $dirsList_date[] = $chemin_eos . DIRECTORY_SEPARATOR . $value;//
            }
        }
        elseif (is_file($chemin_eos . DIRECTORY_SEPARATOR . $value))
        {
            $filesList[] = $value;
        }
        else
        {
            echo "unknown type : $value<br />";
        }
    }
    
    //prePrint('actionFrom', explode('/', $actionFrom));
    $l_actionFrom = count(explode('/', $actionFrom));
    //simPrint('l actionFrom', $l_actionFrom);
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
        elseif ( $value == "DBox" ) // pbm : si le dernier repertoire est un png, ça zappe les gifs
        {
            $DBoxflag = True;
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
    
    echo "<table class=\"tab0\" border=\"0\">";
    echo '<tr>';
    echo '<td width=" 25%">';
    writeHeaderMenu();
    echo '</td>';
    echo '<th class="redClass">';
    echo "<b>electron validation: signal</b>";
    echo "</th>";
    echo '<td width="25%" class="CtextAlign" valign="middle">';
    writeHeaderLinks($base_dir, $url);
    echo "</td>";
    echo "<td class=\"RtextAlign\">";
    if ( $actionFrom !== '' ) // histos web page construction
    {    
        echo "<a href=\"$web_roots/index.php\">Back to roots</a>";
    }
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {
        echo "&nbsp; - &nbsp;\n";
        echo "<a href=\"$web_roots/basket.php?url=" . $url . "&basket=work&site=Releases" . "\">Basket</a>" . "\n";
        //echo "&nbsp; - &nbsp;\n";
        //echo "<a href=\"$web_roots/basket.php?url=" . $url . "&basket=share" . "\">Use a shared file</a>" . "\n";
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";

    if (array_key_exists('choiceValue', $_REQUEST)) {
        $choiceValue = $_REQUEST['choiceValue'];
    }

    echo "<table class=\"tab0\" border=\"0\">"; // filter tab
    echo '<tr><td>';
    {
        if ($l_actionFrom == 4){
            filter($url, $image_loupe);
        }
    }
    echo "</td>";
    echo '<td align="right">';
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {    
        echo '<table border="1" width = "150" class="clickable addLink">'; // Unselect All table
        echo '<tr>';
        echo '<td align="center" select-choice="remove ALL to basket" width="60" soCol="bleu"><font color="blue">Unselect All</font></td>';
        echo "</tr>";
        echo '<tr style="display:none;" soCol="visio">';//
        echo '<td align="center" width="60" visio="goVisio"><font color="blue">View selected histos</font></td>';
        echo "</tr>";
        echo  "</table>"; // Unselect All table
        $viewSelectedPath = $web_roots . '/basket.php?basket=display&actionFrom=' . $actionFrom;
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>"; // filter tab

    echo '<table border="0" width = "100%">';
    echo "<tr>";
    echo "<td>";

    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {    
        if (file_exists($chemin_eos . "/definitions.txt"))
        {
            $handle_0 = fopen($chemin_eos . "/definitions.txt", "r");
            $lineRead7 = fgets($handle_0); // line 7
            echo "<table class=\"tab0\" border=\"0\">";
            echo "<tr><td>";
            
            $lineRead8_1 = fgets($handle_0); // line 8 part 1
            $lineRead8_2 = fgets($handle_0); // line 8 part 2
            $lineRead8_3 = fgets($handle_0); // line 8 part 3
            $tmp_01 = explode("__", $lineRead8_3);
            $tmp_02 = explode("-", $tmp_01[2]);
            $lineRead8_4 = $tmp_01[0] . "__<b><span class=\"greenClass\"> " . $tmp_01[1] . "</span></b>__";
            if (count($tmp_02) == 3) {
                $lineRead8_4 .= $tmp_02[0] . "-" . "<b><span class=\"redClass\">" . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
            }
            else {
                $lineRead8_4 .= $tmp_02[0] . "-" . "<b><span class=\"redClass\">" . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
            }
            $newLine8 = "<b><span class=\"redClass\"> " . $lineRead8_1 . " " . $lineRead8_2 . " </span></b>" . " : " . $lineRead8_4 . " <br>\n";
            echo $newLine8;
    
            $lineRead9_1 = fgets($handle_0); // line 9 part 1
            $lineRead9_2 = fgets($handle_0); // line 9 part 2
            $lineRead9_3 = fgets($handle_0); // line 9 part 3
            $tmp_01 = explode("__", $lineRead9_3);
            $tmp_02 = explode("-", $tmp_01[2]);
            $lineRead9_4 = $tmp_01[0] . "__<b><span class=\"greenClass\"> " . $tmp_01[1] . "</span></b>__";
            if (count($tmp_02) == 3) {
                $lineRead9_4 .= $tmp_02[0] . "-" . "<b><span class=\"blueClass\">" . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
            }
            else {
                $lineRead9_4 .= $tmp_02[0] . "-" . "<b><span class=\"redClass\">" . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
            }
            $newLine9 = "<b><span class='blueClass'> " . $lineRead9_1 . " " . $lineRead9_2 . " </span></b>" . " : " . $lineRead9_4 . " \n";//<br>
            echo $newLine9;
            echo "</td>";
            if ($DBoxflag) {
            echo '<td style="text-align: right; vertical-align: middle;">';
            if ($cchoice == "diff"){
                echo "<a href=\"$web_roots/index.php?actionFrom=" . $actionFrom . '&cchoice=diff"><b><span class="blueClass">Diff</span></b></a>';//
            }
            else {
                echo "<a href=\"$web_roots/index.php?actionFrom=" . $actionFrom . '&cchoice=diff"><span class="blueClass">Diff</span></a>';//
            }
            echo ' &nbsp;: &nbsp;<span class="blueClass">0&le;diff&le;5 %</span> - <span class="greyClass">5&lt;diff&le;10 %</span> - <span class="redClass"> diff&gt;10 %</span><br>';//
            if ($cchoice == "pValue"){
                echo "<a href=\"$web_roots/index.php?actionFrom=" . $actionFrom . '&cchoice=pValue"><b><span class="blueClass">p-Value</span></b></a>';//
            }
            else {
                echo "<a href=\"$web_roots/index.php?actionFrom=" . $actionFrom . '&cchoice=pValue"><span class="blueClass">p-Value</span></a>';//
            }
            echo ' : <span class="blueClass">0.95&lt;pV&le;1.</span> - <span class="greyClass">0.90&lt;pV&le;0.95</span> - <span class="redClass"> pV&lt;0.90</span><br>';
            echo "</td>";/**/
            }
            echo "</tr>";
            echo "</table>";
    
            $lineRead10_1 = fgets($handle_0); // line 10 part 1
            $lineRead10_2 = fgets($handle_0); // line 10 part 2
            $newLine10 = "<p>In all plots below, ";
            if ($lineRead10_1 == $lineRead10_2) {
                $newLine10 .= "there was no reference histograms to compare with";
                $newLine10 .= ", and the " . $lineRead10_1 . " histograms are in red.";
            }
            else {
                $newLine10 .= 'the <b><span class="redClass"> ' . $lineRead10_1 . " </span></b> histograms are in red";
                $newLine10 .= ", and the <b><span class=\"blueClass\"> " . $lineRead10_2 . " </span></b> histograms are in blue.";
            }
            $newLine10 .= "<br>Some more details";
            $lineRead10_3 = fgets($handle_0); // line 10 part 3
            $rest1 = substr($lineRead10_3, 0, 4);
            if (strcmp($rest1, "none") !== 0) {
                $newLine10 .= ", <a href=\"" . $lineRead10_3 . "\">CMS Talk</a> references";
            }
            $lineRead10_4 = fgets($handle_0); // line 10 part 4
            $newLine10 .= ", <a href=\"" . $escaped_url . "/" . $lineRead10_4 . "\">specification</a> of histograms";
            $newLine10 .= ', <a href="' . $escaped_url . '/' . $pictsValue . '/">images</a> of histograms.';
            $newLine10 .= "</p>\n";
            echo "<table class=\"tab0\" border=\"0\">";
            echo "<tr><td>";
            $newLine7 = "<a ID=\"TOP\"></a><a href='" . $previous_url . "'><img width=\"22\" height=\"22\" src=\"" . $image_up . "\" alt=\"Up\"/></a>&nbsp; " ." \n";
            echo $newLine7;
            echo "</td><td>";
            echo $newLine10;
            echo "</td>";/**/
            echo "</tr>";
            echo "</table>";
            
            fclose($handle_0);
        }
        elseif (file_exists($chemin_eos . "/index.html")) // keep the "old" way for the display with the index.html file
        {
            $handle_1 = fopen($chemin_eos . "/index.html", "r");
            $t1 = preg_split("/\//", $actionFrom);
            $t1 = str_replace('CMSSW_', '', $t1[1]);
            $t3 = $t1[0];
            
            for ($i = 0; $i <= 5; $i++) { // write ROOT name file from definitions.txt file
                $lineRead = fgets($handle_1);
            }
            
            if (! ($t3 > 1)) { // write ROOT name file from index.php
                $lineRead7 = fgets($handle_1); // line 7
                echo "<table class=\"tab0\" border=\"0\">";
                echo "<tr><td>";
                $lineRead8 = fgets($handle_1); // line 8
                $tmp_01 = explode("__", $lineRead8);
                $tmp_02 = explode("-", $tmp_01[2]);
                $lineRead8_4 = $tmp_01[0] . "__<b><span class=\"greenClass\"> " . $tmp_01[1] . "</span></b>__";
                if (count($tmp_02) == 3) {
                    $lineRead8_4 .= $tmp_02[0] . "-" . "<b><span class=\"blueClass\">" . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
                }
                else {
                    $lineRead8_4 .= $tmp_02[0] . "-" . "<b><span class=\"redClass\">" . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
                }
                echo $lineRead8_4;
                $lineRead9 = fgets($handle_1); // line 9
                $tmp_01 = explode("__", $lineRead9);
                $tmp_02 = explode("-", $tmp_01[2]);
                $lineRead9_4 = $tmp_01[0] . "__<b><span class=\"greenClass\"> " . $tmp_01[1] . "</span></b>__";
                if (count($tmp_02) == 3) {
                    $lineRead9_4 .= $tmp_02[0] . "-" . "<b><span class=\"blueClass\">" . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
                }
                else {
                    $lineRead9_4 .= $tmp_02[0] . "-" . "<b><span class=\"blueClass\">" . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
                }
                echo $lineRead9_4;
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                $lineRead10 = fgets($handle_1); // line 10
                $lineRead = str_replace("<a href=\"gifs/\">", "<a href='" . $escaped_url . "/". $pictsValue ."/'>", $lineRead10);
                $lineRead = str_replace("<a href=\"electronCompare.C\">", "<a href='" . $escaped_url . "/electronCompare.C'>", $lineRead);
                $lineRead = str_replace("<a href=\"config_target.txt\">", "<a href='" . $escaped_url . "/config_target.txt'>", $lineRead);
                $lineRead = str_replace("<a href=\"ElectronMcSignalHistos.txt\">", "<a href='" . $escaped_url . "/ElectronMcSignalHistos.txt'>", $lineRead);
                echo "<table class=\"tab0\" border=\"0\">";
                echo "<tr><td>";
                $newLine7 = "<a ID=\"TOP\"></a><a href='" . $previous_url . "'><img width=\"22\" height=\"22\" src=\"" . $image_up . "\" alt=\"Up\"/></a>&nbsp; " ." \n";
                echo $newLine7;
                echo "</td><td>";
                echo $lineRead;
                echo "</td>";/**/
                echo "</tr>";
                echo "</table>";
            }
    
            fclose($handle_1);
        }
        else {
            // error opening the file.
            echo "error while trying to open the definitions.txt file";
        }
        }

    echo "</td>";
    echo '<td align="center">';
    $diffMaxTag = true;
    if ((mb_substr($lineRead8_1, 0, -1) === 'RECO') && (mb_substr($lineRead9_1, 0, -1) === 'RECO')) {// && ($tmp_01[1] === 'RelValZEE_14')
        $diffMaxTag = True;
    }
    if ($l_actionFrom >= 4) {
        $pict_name1 = 'https://cms-egamma.web.cern.ch/validation/Electrons/Releases/15_0_0_pre1_2025_DQM_std/FullvsFull_CMSSW_14_2_0_pre4/RECO-RECO_ZEE_14/pngs/comparison_KS_values_total_cum_1000.png';
        $chemin_KS_eos = str_replace($racine_html, $racine_eos, 'https:' . $url_graph);
        $pict_name1 = 'https:' . $url_graph . '/pngs/maxDiff_comparison_values_1.png';
        $pict_name2 = 'https:' . $url_graph . '/pngs/maxDiff_comparison_values_2.png';
        $pict_name3 = 'https:' . $url_graph . '/pngs/maxDiff_comparison_values_3.png';
        if (file_exists($chemin_KS_eos . '/pngs/maxDiff_comparison_values_3.png')) {
            echo '<a href="' . $pict_name3 . '">';
            echo '<img class="image img" width="200" src="' . $pict_name3 . '" alt="" style="border: 2px solid blue;" ></a>';
        }
        else {
            if (file_exists($chemin_KS_eos . '/pngs/maxDiff_comparison_values_1.png')) {
                echo '<a href="' . $pict_name1 . '">';
                echo '<img class="image img" width="150" src="' . $pict_name1 . '" alt="" style="border: 2px solid blue;" ></a>';
            }
            if (file_exists($chemin_KS_eos . '/pngs/maxDiff_comparison_values_2.png')) {
                echo '<a href="' . $pict_name2 . '">';
                echo '<img class="image img" width="150" src="' . $pict_name2 . '" alt="" style="border: 2px solid blue;" ></a>';
            }
        }
    }
    echo "</td>";
    if ($l_actionFrom >= 4) {
        echo '<td align="center" valign="middle" onclick="KS_Evclick()">';
    $runText = '';
    $dataSetText = substr($tmp_01[1], 6);
    if (strpos($tmp_02[1], 'Run3') !== false)
    {
        $runText = "Run3";
    }
    else if (strpos($tmp_02[1], 'Run4') !== false)
    {
        $runText = "Run4";
    }
    if (str_replace("\n", "", $lineRead8_1) == 'PU') {
        $operationText = 'PU';
        $precisionText = 'RECO';
    }
    else {
        $operationText = 'RECO';
        $precisionText = 'RECO';
    }
    if (str_replace("\n", "", $lineRead9_1) == 'miniAOD') {
        $precisionText = 'miniAOD';
    }
    if (strpos($tmp_02[1], 'PURecoOnly') !== false)
    {
        $operationText = 'PU';
        $precisionText = 'RECO';
    }
    $Transf = [$runText, $operationText, $dataSetText, $precisionText];
    //prePrint('transfert', $Transf);
    echo '<b>go to<br>KS Evaluation</b>';
    echo "</td>";
}
echo "<td>";
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {    
        //simPrint('pictsDir', $pictsDir);
        if ($allFormat >= 2) {
            echo '<table border="0" class="clickable selectPictFormat">'; // select picture format table
            echo '<tr>';
            if ($boldFormat == 'g'){
            echo '<td align="center" select-choice="Gif" width="30" pictFormat="gif"><font color="blue"><b>gif</b></font></td>';
            }
            else {
                echo '<td align="center" select-choice="Gif" width="30" pictFormat="gif"><font color="blue">gif</font></td>';
            }
            if ($boldFormat == 'p'){
                echo '<td align="center" select-choice="Png" width="30" pictFormat="png"><font color="blue"><b>png</b></font></td>';
            }
            else {
                echo '<td align="center" select-choice="Png" width="30" pictFormat="png"><font color="blue">png</font></td>';
            }
            echo "</tr>";
            echo  "</table>"; // select picture format table
        }
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>";

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
                    /*if ($tmp == $url) {
                        $corresp = 1;
                        }*/
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
        }/**/
    }

?>
</header>
