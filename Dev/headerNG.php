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
    echo '</tr>';
    echo '</table>';

    echo '<table class="tab0">'; // filter tab
    echo '<tr><td>';
    {
        filter($url, $image_loupe);
    }
    echo '</td>';
    echo '<td class="RtextAlign">';

        echo '<table border="1" class="clickable addLink w-200px">'; // Unselect All table
        echo '<tr>';
        echo '<td select-choice="remove ALL to basket" class="w-100px blueClass CtextAlign" soCol="bleu">Unselect All</td>';
        echo '<td class="w-60px blueClass CtextAlign hidden" visio="goVisio">View selected histos</td>';
        echo '<td class="MtextAlign blueClass CtextAlign">';
            echo 'Basket' . "\n";
        echo '</td>';
        echo '</tr>';
        echo '</table>'; // Unselect All table
    
    echo '</td>';
    echo '</tr>';
    echo '</table>'; // filter tab

    echo '<table class="w-100pct blueBorder1">';
    echo '<tr>';
    echo '<td>';

        echo '<table class="tab0">'; // affichage lignes de la comparaison choisie
        echo '<tr><td id="line1">';
        echo '</td></tr>';
        echo '<tr><td id="line2">';
        echo '</td>';
        echo '</tr>';
        echo '<tr><td id="line3">';
        echo '</td></tr>';
        echo '</table>';

    echo '</td>';
    echo '<td class="CtextAlign" id="maxDiff">'; // affichage lignes de la comparaison choisie
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
