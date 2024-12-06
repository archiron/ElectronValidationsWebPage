<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>releases list webpage</title>
<link rel="stylesheet" href="../php_inc/styles.css">
<script src="../js/jQuery-3.6.3/jquery-3.6.3.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.min.css">
<link rel="stylesheet" href="../js/jquery-ui.theme.min.css">
<link rel="stylesheet" href="../js/jquery-ui.structure.min.css">
<!-- the modification of img style (img.anchor) is a precious help of M. Mellin ! -->
</head>

<body>
<div class="sticky">    
    <?php include('globos_header.php'); ?>
</div>
<main>

<?php

$dboxName = $chemin_eos . "/DBox/" . $short_histo_name  . ".txt";
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

# get the reference release
$ref = $dbox[3];
$ref = explode('">', $ref)[1];
$ref = trim(str_replace('</font></b>', '', $ref));

$tmp1 = explode('KS_Curves/', $dbox[29]); # 
$rep1 = explode('/', $tmp1[1])[0]; # KS release
$CMS = explode('-', $rep1);
if ( count($CMS) > 1 ) {
    //$tmp2 = explode('-', $rep); # 
    $tmp = explode('FullvsFull_', $dbox[24]); # 29
    $rep = explode('/', $tmp[1])[0];
    $tmp3 = explode('/', $rep); # -
    //$nb = count($tmp3);
    $chem = $ref . '/' . $tmp3[0]; # $nb-1 CMSSW_

    $dbox[29] = str_replace($rep1, $chem, $dbox[29]);
    $dbox[37] = str_replace($rep1, $chem, $dbox[37]);
    //echo $dbox[37] . "<br>";
}

foreach ($dbox as $key => $value) {
    $line = str_replace('href="https', 'href=https', $value);
    $line = str_replace('src="https', 'src=https', $line);
    $line = str_replace('href="http', 'href=https', $line);
    $line = str_replace('src="http', 'src=https', $line);
    $line = str_replace('"gifs/', $url.'/'.'gifs/', $line);
    $line = str_replace('.gif"', '.gif', $line);
    $line = str_replace('"pngs/', $url.'/'.'pngs/', $line);
    $line = str_replace('.png"', '.png', $line);
    echo $line;
}
echo '</table>'; # because it is missing into the html file

?>

<script>
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


</main>

<?php include('globos_footer.php'); ?>


</body>
</html>
