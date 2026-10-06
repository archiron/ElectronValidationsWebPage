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
    /*echo '<td class="RtextAlign MtextAlign">';
    if ( $actionFrom !== '' ) // histos web page construction
    {    
        echo '<a href="' . $web_roots . '/indexNG.php">Back to roots</a>';
    }
    if ( $pictsDir and $indexHtml and $histosFile ) // histos web page construction
    {
        echo '&nbsp; - &nbsp;';
        echo '<a href="' . $web_roots . '/basket.php?url=' . $url . '&basket=work&site=Releases' . '">Basket</a>' . "\n";
    }
    echo '</td>';*/
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

    echo '<table class="w-100pct blackBorder1">';
    echo '<tr>';
    echo '<td>';

    echo '<table class="tab0">';
    echo '<tr><td id="line1">';
    echo '</td></tr>';
    echo '<tr><td id="line2">';
    echo '</td>';
    echo '</tr>';
    echo '<tr><td id="line3">';
    echo '</td></tr>';
    echo '</table>';

    echo '</td>';
    echo '<td class="CtextAlign">';
    if ($l_actionFrom >= 4) {
        //$pict_name1 = 'https://cms-egamma.web.cern.ch/validation/Electrons/Releases/15_0_0_pre1_2025_DQM_std/FullvsFull_CMSSW_14_2_0_pre4/RECO-RECO_ZEE_14/pngs/comparison_KS_values_total_cum_1000.png';
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
    echo '</tr>';
    echo '</table>';

?>
</header>
