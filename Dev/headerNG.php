<header>

    <?php
    echo '<table class="tab0">';
    echo '<tr class="ValidationsMenu">';
    echo '<td class="w-25pct">';
    writeHeaderMenu();
    echo '</td>';
    echo '<th class="redClass">';
    echo '<b>electron validation: signal NG</b>';
    echo '</th>';
    echo '<td class="CtextAlign w-25pct MtextAlign" >';
    writeHeaderLinks($base_dir, $url);
    echo '</td>';
    echo '<td class="RtextAlign MtextAlign">';
    if ( $actionFrom !== '' ) // histos web page construction
    {    
        echo '<a href="$web_roots/indexNG.php">Back to roots</a>';
    }
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {
        echo '&nbsp; - &nbsp;';
        echo '<a href="' . $web_roots . '/basket.php?url=' . $url . '&basket=work&site=Releases' . '">Basket</a>' . "\n";
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>';

    if (array_key_exists('choiceValue', $_REQUEST)) {
        $choiceValue = $_REQUEST['choiceValue'];
    }

    echo '<table class="tab0">'; // filter tab
    echo '<tr><td>';
    {
        if ($l_actionFrom == 4){
            filter($url, $image_loupe);
        }
    }
    echo '</td>';
    echo '<td class="RtextAlign">';
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {    
        echo '<table border="1" class="clickable addLink w-150px">'; // Unselect All table
        echo '<tr>';
        echo '<td align="center" select-choice="remove ALL to basket" class="w-60px blueClass" soCol="bleu">Unselect All</td>';
        echo '</tr>';
        echo '<tr class="hidden" soCol="visio">';//
        echo '<td align="center" class="w-60px blueClass" visio="goVisio">View selected histos</td>';
        echo '</tr>';
        echo '</table>'; // Unselect All table
        $viewSelectedPath = $web_roots . '/basket.php?basket=display&actionFrom=' . $actionFrom;
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>'; // filter tab

    echo '<table class="w-100pct">';
    echo '<tr>';
    echo '<td>';

    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {    
        if (file_exists($chemin_eos . "/definitions.txt"))
        {
            $handle_0 = fopen($chemin_eos . "/definitions.txt", "r");
            $lineRead7 = fgets($handle_0); // line 7
            echo '<table class="tab0">';
            echo '<tr><td>';
            
            $lineRead8_1 = fgets($handle_0); // line 8 part 1
            $lineRead8_2 = fgets($handle_0); // line 8 part 2
            $lineRead8_3 = fgets($handle_0); // line 8 part 3
            $tmp_01 = explode("__", $lineRead8_3);
            $tmp_02 = explode("-", $tmp_01[2]);
            $lineRead8_4 = $tmp_01[0] . '__<b><span class="greenClass"> ' . $tmp_01[1] . "</span></b>__";
            if (count($tmp_02) == 3) {
                $lineRead8_4 .= $tmp_02[0] . "-" . '<b><span class="redClass">' . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
            }
            else {
                $lineRead8_4 .= $tmp_02[0] . "-" . '<b><span class="redClass">' . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
            }
            $newLine8 = '<b><span class="redClass"> ' . $lineRead8_1 . " " . $lineRead8_2 . " </span></b>" . " : " . $lineRead8_4 . " <br>\n";
            echo $newLine8;
    
            $lineRead9_1 = fgets($handle_0); // line 9 part 1
            $lineRead9_2 = fgets($handle_0); // line 9 part 2
            $lineRead9_3 = fgets($handle_0); // line 9 part 3
            $tmp_01 = explode("__", $lineRead9_3);
            $tmp_02 = explode("-", $tmp_01[2]);
            $lineRead9_4 = $tmp_01[0] . '__<b><span class="greenClass"> ' . $tmp_01[1] . "</span></b>__";
            if (count($tmp_02) == 3) {
                $lineRead9_4 .= $tmp_02[0] . "-" . '<b><span class="blueClass">' . $tmp_02[1] . "-" . $tmp_02[2] . "</span></b>__" . $tmp_01[3];
            }
            else {
                $lineRead9_4 .= $tmp_02[0] . "-" . '<b><span class="redClass">' . $tmp_02[1] . "</span></b>__" . $tmp_01[3];
            }
            $newLine9 = "<b><span class='blueClass'> " . $lineRead9_1 . " " . $lineRead9_2 . " </span></b>" . " : " . $lineRead9_4 . " \n";//<br>
            echo $newLine9;
            echo '</td>';
            echo '</tr>';
            echo '</table>';
    
            $lineRead10_1 = fgets($handle_0); // line 10 part 1
            $lineRead10_2 = fgets($handle_0); // line 10 part 2
            $newLine10 = "<p>In all plots below, ";
            if ( ($lineRead10_1 == $lineRead10_2) && ($lineRead8_1 == $lineRead9_1) ) {
                $newLine10 .= "there was no reference histograms to compare with";
                $newLine10 .= ", and the " . $lineRead10_1 . " histograms are in red.";
            }
            else {
                $newLine10 .= 'the <b><span class="redClass"> ' . $lineRead10_1 . " " . $lineRead8_1 . " </span></b> histograms are in red";
                $newLine10 .= ', and the <b><span class="blueClass"> ' . $lineRead10_2 . " " . $lineRead9_1 . " </span></b> histograms are in blue.";
            }
            $newLine10 .= "<br>Some more details";
            $lineRead10_3 = fgets($handle_0); // line 10 part 3
            $rest1 = substr($lineRead10_3, 0, 4);
            if (strcmp($rest1, "none") !== 0) {
                $newLine10 .= ', <a href="' . $lineRead10_3 . '">CMS Talk</a> references';
            }
            $lineRead10_4 = fgets($handle_0); // line 10 part 4
            $newLine10 .= ', <a href="' . $escaped_url . "/" . $lineRead10_4 . '">specification</a> of histograms';
            $newLine10 .= ', <a href="' . $escaped_url . '/' . $pictsValue . '/">images</a> of histograms.';
            $newLine10 .= '</p>';
            echo '<table class="tab0">';
            echo '<tr><td>';
            $newLine7 = '<a ID="TOP"></a><a href="' . $previous_url . '"><img width="22" height="22" src="' . $image_up . '" alt="Up"/></a>&nbsp; ' ." \n";
            echo $newLine7;
            echo '</td><td>';
            echo $newLine10;
            echo '</td>';
            echo '</tr>';
            echo '</table>';
            
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
            
            if (! ($t3 > 1)) { // write ROOT name file from indexNG.php
                $lineRead7 = fgets($handle_1); // line 7
                echo '<table class="tab0">';
                echo '<tr><td>';
                $lineRead8 = fgets($handle_1); // line 8
                $tmp_01 = explode("__", $lineRead8);
                $tmp_02 = explode("-", $tmp_01[2]);
                $lineRead8_4 = $tmp_01[0] . '__<b><span class="greenClass"> ' . $tmp_01[1] . '</span></b>__';
                if (count($tmp_02) == 3) {
                    $lineRead8_4 .= $tmp_02[0] . "-" . '<b><span class="blueClass">' . $tmp_02[1] . "-" . $tmp_02[2] . '</span></b>__' . $tmp_01[3];
                }
                else {
                    $lineRead8_4 .= $tmp_02[0] . "-" . '<b><span class="redClass">' . $tmp_02[1] . '</span></b>__' . $tmp_01[3];
                }
                echo $lineRead8_4;
                $lineRead9 = fgets($handle_1); // line 9
                $tmp_01 = explode("__", $lineRead9);
                $tmp_02 = explode("-", $tmp_01[2]);
                $lineRead9_4 = $tmp_01[0] . '__<b><span class="greenClass"> ' . $tmp_01[1] . '</span></b>__';
                if (count($tmp_02) == 3) {
                    $lineRead9_4 .= $tmp_02[0] . "-" . '<b><span class="blueClass">' . $tmp_02[1] . "-" . $tmp_02[2] . '</span></b>__' . $tmp_01[3];
                }
                else {
                    $lineRead9_4 .= $tmp_02[0] . "-" . '<b><span class="blueClass">' . $tmp_02[1] . '</span></b>__' . $tmp_01[3];
                }
                echo $lineRead9_4;
                echo '</td>';
                echo '</tr>';
                echo '</table>';
                $lineRead10 = fgets($handle_1); // line 10
                $lineRead = str_replace('<a href="gifs/">', '<a href="' . $escaped_url . "/". $pictsValue ."/'>", $lineRead10);
                $lineRead = str_replace('<a href="electronCompare.C">', "<a href='" . $escaped_url . "/electronCompare.C'>", $lineRead);
                $lineRead = str_replace('<a href="config_target.txt">', "<a href='" . $escaped_url . "/config_target.txt'>", $lineRead);
                $lineRead = str_replace('<a href="ElectronMcSignalHistos.txt">', "<a href='" . $escaped_url . "/ElectronMcSignalHistos.txt'>", $lineRead);
                echo '<table class="tab0">';
                echo '<tr><td>';
                $newLine7 = '<a ID="TOP"></a><a href="' . $previous_url . '"><img width="22" height="22" src="' . $image_up . '" alt="Up"/></a>&nbsp; ' ." \n";
                echo $newLine7;
                echo '</td><td>';
                echo $lineRead;
                echo '</td>';
                echo '</tr>';
                echo '</table>';
            }
    
            fclose($handle_1);
        }
        else {
            // error opening the file.
            echo "error while trying to open the definitions.txt file";
        }
        }

    echo '</td>';
    echo '<td class="CtextAlign">';
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
            echo '<img class="image img blueBorder2 w-200px" src="' . $pict_name3 . '" alt="" ></a>';
        }
        else {
            if (file_exists($chemin_KS_eos . '/pngs/maxDiff_comparison_values_1.png')) {
                echo '<a href="' . $pict_name1 . '">';
                echo '<img class="image img blueBorder2 w-150px" src="' . $pict_name1 . '" alt="" ></a>';
            }
            if (file_exists($chemin_KS_eos . '/pngs/maxDiff_comparison_values_2.png')) {
                echo '<a href="' . $pict_name2 . '">';
                echo '<img class="image img blueBorder2 w-150px" src="' . $pict_name2 . '" alt="" ></a>';
            }
        }
    }
    echo '</td>';
    if ($l_actionFrom >= 4) {
        if (file_exists($chemin_KS_eos . '/pngs/maxDiff_comparison_values_3.png')) {
            echo '<td>';//
            echo '<table class="clickable curveChoice blueBorder1" ><tr>'; 
            echo '<td class="CtextAlign Gras blueBorder1 p-5px" curve-choice="histos" title="Click on text to change the pictures">Histos</td>';
            echo '<td class="CtextAlign blueBorder1 p-5px" curve-choice="diffMax" title="Click on text to change the pictures">Differences</td>';
            echo '</tr></table>';
            echo '</td>';
        }
    }
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
    echo '</td>';
}
echo '<td>';
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {    
        //simPrint('pictsDir', $pictsDir);
        if ($allFormat >= 2) {
            echo '<table class="clickable selectPictFormat">'; // select picture format table
            echo '<tr>';
            if ($boldFormat == 'g'){
            echo '<td align="center" select-choice="Gif" class="w-30px" pictFormat="gif"><span class="blueClass"><b>gif</b></span></td>';
            }
            else {
                echo '<td align="center" select-choice="Gif" class="w-30px" pictFormat="gif"><span class="blueClass">gif</span></td>';
            }
            if ($boldFormat == 'p'){
                echo '<td align="center" select-choice="Png" class="w-30px" pictFormat="png"><span class="blueClass"><b>png</b></span></td>';
            }
            else {
                echo '<td align="center" select-choice="Png" class="w-30px" pictFormat="png"><span class="blueClass">png</span></td>';
            }
            echo '</tr>';
            echo '</table>'; // select picture format table
        }
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>';

?>
</header>
