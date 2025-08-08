<?php  
    session_start();
    include 'defaults.inc.php';
    include 'fonctions.inc.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    if (isset($_POST['something'])) {
        echo 'regenerate OK in regenerate.php' . '<br>' . "\n";
        echo $_POST['something'];
        //$_SESSION['something'] = str_replace('"', '', $_POST['something']);
    }

    $base_dir = __DIR__;
    $web_roots = $web_rootsR; //getRootPath($base_dir);
    $chemin = $web_rootsR . '/';
    $chemin_eos=str_replace($racine_html, $racine_eos, $chemin);
    //simPrint( 'racine_html', $racine_html );
    //simPrint( 'racine_eos', $racine_eos );
    //simPrint( 'chemin_eos', $chemin_eos );
    $files = array_slice(scandir($chemin_eos), 2);
    //prePrint('files', $files);
    
    // Fill arrays with dirs & files
    foreach ($files as $key => $value)
    {
        if (is_dir($chemin_eos . DIRECTORY_SEPARATOR . $value))
        {
            $dirsList[] = $value;
        }
    }
    $Globos1 = $dirsList;
    //prePrint('globos1', $Globos1);

    $Globos0 = []; // list of all paths
    $histoNames = [];

    foreach ($Globos1 as $key => $value) {
        $prem2 = explode('_', $value)[0];
        if (intval($prem2) > 12) {
            //echo $key . ' - ' . $value . '<br>';
            $path1 = $web_roots . '/' . $value;//simPrint("path1", $path1);
            $path_eos1 = str_replace($racine_html, $racine_eos, $path1);//simPrint($value, $path_eos1);
            $tmpList1 = array_slice(scandir($path_eos1), 2);
            //prePrint('dfdd', array_slice(scandir($path_eos1), 2));
            foreach ($tmpList1 as $key2 => $value2) {
                $path_eos2 = $path_eos1 . '/' . $value2;//simPrint("path eos1", $path_eos1);
                $tmpList2 = array_slice(scandir($path_eos2), 2);//echo $tmpList2[0] . $_fDL;
                foreach ($tmpList2 as $key3 => $value3) {
                    //echo $value3 . ' - ' . explode('_', $value3, 2)[1] . $_fDL; // affiche les datasets
                    $path_eos3 = $path_eos2 . '/' . $value3;
                    //echo $path_eos3 . $_fDL;
                    $Globos0[] = $path_eos3;
                    $tmpList3 = array_slice(scandir($path_eos3), 2);//prePrint("tmpList3", $tmpList3);
                    foreach ($tmpList3 as $key4 => $value4) {
                        $path_eos4 = $path_eos3 . '/' . $value4; // $value4 = gifs/pngs
                        $pictExt = substr($value4, 0, -1);//simPrint('extent', $pictExt);
                        if ( is_dir($path_eos4)) {
                            //simPrint("path eos4", $path_eos4);
                            $pictExt = substr($value4, 0, -1);//simPrint('extent', $pictExt);
                            $tmpList4 = array_slice(scandir($path_eos4), 2);//prePrint("tmpList4", $tmpList4);
                            foreach ($tmpList4 as $key5 => $value5) {
                                if (substr(explode('.', $value5)[0], 0, 2) == 'h_') {
                                    $histoNames[] = explode('.', $value5)[0];
                                }
                            }
                        }
                    }
                }
            }/**/
        }
    }
    //prePrint('Globos0', $Globos0);
    $pathGlobos0 = str_replace($racine_html, $racine_eos, $web_rootsR . '/Globos0.txt');//simPrint('pathGlobos0', $pathGlobos0);
    file_put_contents($pathGlobos0, implode(PHP_EOL, $Globos0));
    $pathHistoNames = str_replace($racine_html, $racine_eos, $web_rootsR . '/histoNames.txt');//simPrint('pathHistoNames', $pathHistoNames);
    $histoNames = array_unique($histoNames);
    file_put_contents($pathHistoNames, implode(PHP_EOL, $histoNames));

?>
