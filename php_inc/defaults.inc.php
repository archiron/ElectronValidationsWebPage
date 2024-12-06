<?php
$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; 
//echo $url . "<br>\n";
$tmp2 = str_replace('//cms-egamma', '', $url);
$tmp2 = substr($tmp2, 0, 4);
if ($tmp2 =='.web') {
    $racine_html="https://cms-egamma.web.cern.ch/";
}
else { // ($tmp == '-el9')
    $racine_html="https://cms-egamma-el9-preview.webtest.cern.ch/cms-egamma/";
}
//echo $tmp . ' - ' . $racine_html . "<br>\n";

//$web_rootsD="https://cms-egamma.web.cern.ch/validation/Electrons/Dev";
//$web_rootsT="https://cms-egamma.web.cern.ch/validation/Electrons/Test";
//$web_rootsR="https://cms-egamma.web.cern.ch/validation/Electrons/Releases";
//$web_roots="https://cms-egamma.web.cern.ch/validation/Electrons";
$web_rootsD=$racine_html . "validation/Electrons/Dev";
$web_rootsT=$racine_html . "validation/Electrons/Test";
$web_rootsR=$racine_html . "validation/Electrons/Releases";
$web_roots=$racine_html . "validation/Electrons";
$web_roots_comparison = $racine_html . "validation/Electrons/Comparisons";

$racine_eos="/eos/project/c/cmsweb/www/egamma/";
$racine_KS = '/eos/project/c/cmsweb/www/egamma/validation/Electrons/Store/KS_Curves';

$web_KS = $web_roots . '/Store/KS_Curves';
$image_up=$web_roots . "/img/up.gif";
$image_point=$web_roots . "/img/point.gif";
$image_add=$web_roots . "/img/buy.png";
$image_remove=$web_roots . "/img/remove.png";
$image_loupe=$web_roots . "/img/loupe.png";

$url_0 = $web_roots . "/php_inc/addRemove.php";
$url_1 = $web_roots . "/php_inc/pictFormat.php"; // picture format (gif/png)

?>

