<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" >
<title>releases list webpage</title>
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/all.min.css">
<link rel="stylesheet" href="../js/jQuery-4.0.0/jquery-ui-1.14.2/jquery-ui.min.css">
<script src="../js/jQuery-4.0.0/jquery.min.js"></script>
<script src="../js/jQuery-4.0.0/jquery-ui-1.14.2/jquery-ui.min.js"></script>
<!-- the modification of img style (img.anchor) is a precious help of M. Mellin ! -->
</head>

<body>
<?php
// --- SECURITY (Haut de page) ---
    include '../php_inc/security.inc.php';

    // 1. Sanitization de l'URL avant toute utilisation
    $actionFrom = cleanInput_V2($_REQUEST['actionFrom'] ?? '', true);  // true autorise les slashes
    $url = cleanInput_V2($_REQUEST['url'] ?? '', true);    // false bloque les slashes
    $short_histo_name = cleanInput_V2($_REQUEST['short_histo_name'] ?? '', false);    // false bloque les slashes

    $url_safe = cleanInput($_GET['redirect'] ?? '', 'url');
    if (!empty($url_safe) && !preg_match('#^https?://#i', $url_safe)) {
        $url_safe = ''; // Fallback si jamais le protocole a été altéré
    }

    // Vérification CRITIQUE anti-traversal
    if (strpos($actionFrom, '..') !== false) {
        die("Chemin invalide : tentative de traversal détectée");
    }

    // Si votre header.php utilise $_REQUEST ou $_GET directement, mettez-les à jour :
    $_REQUEST['actionFrom'] = $actionFrom;
    $_REQUEST['url'] = $url;
    $_REQUEST['short_histo_name'] = $short_histo_name;
    $_REQUEST['redirect'] = $url_safe;

// --- END SECURITY ---

?>
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
    $tmp = explode('FullvsFull_', $dbox[24]); # 29
    $rep = explode('/', $tmp[1])[0];
    $tmp3 = explode('/', $rep); # -
    $chem = $ref . '/' . $tmp3[0]; # $nb-1 CMSSW_

    $dbox[29] = str_replace($rep1, $chem, $dbox[29]);
    $dbox[37] = str_replace($rep1, $chem, $dbox[37]);
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
