<!DOCTYPE html>
<html lang="en">

<?php
// On génère un jeton aléatoire sécurisé (si ce n'est pas déjà fait)
if (!isset($nonce)) {
    $nonce = base64_encode(random_bytes(16));
}
?>

<head>
<meta charset="UTF-8" >
<!--meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'nonce-<?php echo $nonce; ?>' https://jquery.com; style-src 'self' 'nonce-<?php echo $nonce; ?>';" -->
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'nonce-<?php echo $nonce; ?>' https://jquery.com; style-src 'self' 'unsafe-inline';">
<title>releases list webpage</title>
<link rel="stylesheet" href="../css/styles.css">
<!-- the modification of img style (img.anchor) is a precious help of M. Mellin ! -->
</head>

<body>
<?php
// --- SECURITY (Haut de page) ---
    include '../php_inc/security.inc.php';

    // 1. Sanitization de l'URL avant toute utilisation
    $url    = cleanInput_V2($_REQUEST['url'] ?? '', true);    // false bloque les slashes
    $short_histo_name    = cleanInput_V2($_REQUEST['short_histo_name'] ?? '', true);    // false bloque les slashes

    $url_safe = cleanInput($_GET['redirect'] ?? '', 'url');
    if (!empty($url_safe) && !preg_match('#^https?://#i', $url_safe)) {
        $url_safe = ''; // Fallback si jamais le protocole a été altéré
    }

    // Vérification CRITIQUE anti-traversal
    if (strpos($actionFrom, '..') !== false) {
        die("Chemin invalide : tentative de traversal détectée");
    }

    // Si votre header.php utilise $_REQUEST ou $_GET directement, mettez-les à jour :
    $_REQUEST['url'] = $cchoice;
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
$dbox[13] = '<td>' . "\n";
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

</main>

<?php include('globos_footer.php'); ?>


</body>
</html>
