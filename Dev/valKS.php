<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>releases list webpage</title>
<link rel="stylesheet" href="../php_inc/styles.css">
<script src="../js/jQuery-3.6.3/jquery-3.6.3.min.js"></script>
<!-- the modification of img style (img.anchor) is a precious help of M. Mellin ! -->
</head>

<body>
<div class="sticky">    
    <?php include('valKS_header.php'); ?>
</div>
<main>

<?php
/*echo '<table border="1" class="clickable displayChoice" style="margin-left:auto;margin-right:auto" width="700">' . "\n";
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
echo '</td></tr></table>';*/

echo '<div id="mono">';

$dboxName = $chemin_eos2 . '/' . $short_histo_name  . '.txt';
//echo htmlentities($dboxName) . '<br>';
$dbox = file($dboxName);
$dbox[13] = "<td>" . "\n";
unset($dbox[31]);
unset($dbox[32]);
unset($dbox[33]);
unset($dbox[34]);
$tmp = $dbox[15];
$tmp_tmp = explode("<p><b>", $tmp);
$tmp2 = '<p><b>' . $tmp_tmp[2];
$dbox[15] = str_replace($tmp2, '', $tmp);
//echo htmlentities($dbox[15]) . '<br>';
//echo 'rel princ : ' . $rel_princ . '<br>';
//echo 'rel secon : ' . $rel_secon . '<br>';
//echo 'rel num : ' . $rel_num . '<br>';
$rel_secon_num = str_replace('CMSSW_', '', $rel_secon);

# get the reference release
$p1 = explode('">', $dbox[3])[0];
$p2 = explode('">', $dbox[3])[1];
$dbox[3] = $p1 . '">' . $rel_princ . $p2;

$pict = explode('href=', $dbox[12]);
$file1 = explode('">', $pict[1])[0];
$file1 = str_replace('"', '', $file1);
$file1 = str_replace($rel_secon_num . '_', '', $file1);
#echo 'file1 : ' . $file1 . '<br>';
$filePath1 = $chemin_eos2 . '/' . $file1;
$filePath2 = $chemin . '/' . $file1;
#echo 'filePath1 : ' . $filePath1 . '<br>';
if (!file_exists($filePath1)) {
    #echo "Le fichier $file1 n'existe pas.<br>";
    $file1 = str_replace('_n0.png', '.gif', $file1);
    $filePath1 = $chemin_eos2 . '/' . $file1;
    $filePath2 = $chemin . '/' . $file1;
    #echo 'filePath1 : ' . $filePath1 . '<br>';
}
$tmp4 = '';
if (file_exists($filePath1)) { 
    $tmp4 = '<td><div><a href="' . $filePath2;
    $tmp4 .= '"><img border="0" class="image" width="440" src="' . $filePath2 . '"></a></div></td>';
}
$dbox[12] = $tmp4;
$pict = explode('href=', $dbox[17]);
$file1 = explode('">', $pict[1])[0];
$file1 = str_replace('"', '', $file1);
$file1 = str_replace($rel_secon_num . '_', '', $file1);
$tmp4 = '<td><div><a href="' . $chemin . '/' . $file1;
$tmp4 .= '"><img border="0" class="image" width="440" src="' . $chemin . '/' . $file1 . '"></a></div></td>';
$dbox[17] = $tmp4;

$file1 = str_replace('_cum0', '', $file1);
$tmp4 = '<div><a href="' . $chemin . '/KS-ttlDiff_1_' . $file1;
$tmp4 .= '"><img border="0" class="image" width="440" src="' . $chemin . '/KS-ttlDiff_1_' . $file1 . '"></a></div>';
$dbox[29] = '<td>' . $tmp4 . '</td>';
$file1 = str_replace('_1_', '_3_', $file1);
$tmp4 = '<div><a href="' . $chemin . '/KS-ttlDiff_3_' . $file1;
$tmp4 .= '"><img border="0" class="image" width="440" src="' . $chemin . '/KS-ttlDiff_3_' . $file1 . '"></a></div>';
$dbox[37] = '<td>' . $tmp4 . '</td>';

$tmp5 = $chemin . '/';
$dbox[24] = str_replace('href="', 'href="' . $tmp5, $dbox[24]);
$dbox[24] = str_replace('html"', 'html', $dbox[24]);

$newLine = $chemin . '/KSCompHisto_' . $file1;
$dbox[39] = '<tr><th scope="row">Comparison</th>';
$dbox[39] .= '<td><div><a href="' . $newLine . '"><img border="0" class="image" width="440" src="' . $newLine . '"></a></div></td></tr></table>';

foreach ($dbox as $key => $value) {
    $line = str_replace('href="https', 'href=https', $value);
    $line = str_replace('src="https', 'src=https', $line);
    $line = str_replace('href="http', 'href=https', $line);
    $line = str_replace('src="http', 'src=https', $line);
    $line = str_replace('"gifs/', $url.'/'.'gifs/', $line);
    $line = str_replace('.gif"', '.gif', $line);
    $line = str_replace('"pngs/', $url.'/'.'pngs/', $line);
    $line = str_replace('.png"', '.png', $line);/**/
    echo $line;
}

echo '</div>';
echo "<br><br>\n"; // TEMPORAIRE

// COMPARISON between all releases //
echo '<div id="comp">';
//prePrint('dirsList', $dirsList3);
echo '<table border="1" style="margin-left:auto;margin-right:auto">';
echo '<tr><th>Releases</th><th>KS-1</th><th>KS-3</th><th>Comparison</th><th>Classical histo</th><th>Others curves</th></tr>';
foreach ($dirsList3 as $key3 => $value3) {
    $pictName = $chemin3 . '/' . $value3 . '/KS-ttlDiff_1_' . $histoName  . '.png';
    echo '<tr><td style="text-align: middle; vertical-align: middle;">';//
    if ($value3 == $rel_princ) {
        echo '<b><span class="blueClass">' . $value3 . '</span></b>';
    }
    elseif ($value3 == $rel_secon) {
        echo '<b><span class="darkBlueClass">' . $value3 . '</span></b>';
    }
    else {
        echo '<b>' . $value3 . '</b>';
    }
    echo '</td><td>';
    echo '<div><a href="' . $pictName . '"><img border="0" class="image" width="440" src="' . $pictName . '"></a></div>';
    echo '</td><td>';
    $pictName = $chemin3 . '/' . $value3 . '/KS-ttlDiff_3_' . $histoName  . '.png';
    echo '<div><a href="' . $pictName . '"><img border="0" class="image" width="440" src="' . $pictName . '"></a></div>';
    echo '</td><td>';
    $pictName = $chemin3 . '/' . $value3 . '/KSCompHisto_' . $histoName  . '.png';
    echo '<div><a href="' . $pictName . '"><img border="0" class="image" width="440" src="' . $pictName . '"></a></div>';
    echo '</td><td>';
    $pictName = $chemin3 . '/' . $value3 . '/' . $histoName  . '.gif'; // .png
    //$pictName = str_replace('.png', '_n0.png', $pictName);
    echo '<div><a href="' . $pictName . '"><img border="0" class="image" width="440" src="' . $pictName . '"></a></div>';
    echo '</td><td>';
    $pictName = $chemin3 . '/' . $value3 . '/' . $histoName  . '.png';
    $pictName = str_replace('.png', '_cum0.png', $pictName);
    echo '<div><a href="' . $pictName . '"><img border="0" class="image" width="440" src="' . $pictName . '"></a></div>';
    echo '</td></tr>';
}
echo '</table>';
echo '<br>';

echo '</div>';
echo "<br><br><br><br><br><br>\n"; // TEMPORAIRE

?>

<script>
    //var prefix = <?php echo json_encode($new_escaped_url); ?>;
    //var histoNames = <?php echo json_encode($names); ?>;
    var text_values = <?php echo json_encode($textValues); ?>;

</script>

<script>
$(document).ready(function(){
  $("p").click(function(){
    if ( $('[valInfo="t12"]').html() != '' ) {
        $('[valInfo="t12"]').html('');
    }
    else {
        $('[valInfo="t12"]').html(text_values);
    }
  });
});
</script>

<script>
    $(document).ready(function(){
        // la class clickable est appliquée à tous les table qui auront des "boutons"
        $('table.clickable td').on('click', displayChoice );
    });

    function displayChoice() {
        //console.log("filesChoice");
        // si le td a une class ou une autre, on peut le traiter différemment
        if ($(this).parents('table.clickable').hasClass('displayChoice')) {
            $('table.displayChoice td').removeClass('Gras');//
            disChoice($(this));
            //console.log("filesChoice");
        }/**/
    }

    function disChoice(obj) {
        var choice = obj.attr('display-choice');
        console.log(choice);
        if (choice == 'mono') {
            $('[display-choice="mono"]').addClass('Gras');
            $('[id="comp"]').hide();
            $('[id="mono"]').show();
        }
        if (choice == 'comp') {
            $('[display-choice="comp"]').addClass('Gras');
            $('[id="mono"]').hide();
            $('[id="comp"]').show();

        }
    }
    

</script>

</main>

<?php include('valKS_footer.php'); ?>


</body>
</html>
