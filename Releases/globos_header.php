<header>
    
    <?php
    session_start();

    
    $short_histo_name  = (isset($_REQUEST['short_histo_name']) ? $_REQUEST['short_histo_name'] : '');
    $url = (isset($_REQUEST['url']) ? $_REQUEST['url'] : '');
    $url = 'https:' . $url;
    //echo 'url : ' . $url . "<br>";

    $base_dir = __DIR__;
    include '../php_inc/defaults.inc.php';
    include '../php_inc/fonctions.inc.php';
    $web_roots = getRootPath($base_dir);
    
    $chemin = $web_roots;
    $chemin = str_replace("/Dev", "/Store/KS_Curves", $chemin);
    $chemin_eos=str_replace($racine_html, $racine_eos, $url);
    //echo "chemin_eos : " . $chemin_eos . "<br>";
    
    $fileName_0 = getFileName(session_id());
    $fileName = $web_roots . "/" . $fileName_0;
    $fileName_eos=str_replace($racine_html, $racine_eos, $fileName);
    $_SESSION['localFileForHistos_eos'] = $fileName_eos;
    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $escaped_url = str_replace("/checkKS.php?actionFrom=/", "/", $escaped_url);
    //echo "escap : ".$escaped_url . "<br>";
    $classical_roots = htmlspecialchars( $web_roots, ENT_QUOTES, 'UTF-8' );
    $classical_roots = str_replace("/checkKS.php?actionFrom=/", "/", $classical_roots);
    $classical_path = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    //echo "classical_path : ".$classical_path . "<br>";
    $classical_path = str_replace("/checkKS.php?actionFrom=/", "/", $classical_path);
    $previous_url = dirname($url);
    
    $dirsList = array();
    $dirsList_date = array(); // AC
    $filesList = array();
    $lineHisto = array();
    $pictsDir = False;
    $indexHtml = False;
    $DBoxflag = False;
    
    $parties = explode("/", $url);
    $Np = count($parties);
    
    echo "<table class=\"tab0\">";
    echo '<tr><td class="b0">';
    writeHeaderLinks($base_dir, $url);
    echo "</td>";

    echo '<td><th><span class="redClass">';
    echo "<b>electron validation: Kolmogorov - Smirnov</b>";
    echo "</span>";
    echo '<span class="darkBlueClass">';
    echo " / ";
    echo "<b>" . $short_histo_name  . "</b>";
    echo "</span></th></td>";
    echo "<td class=\"RtextAlign\">";
    //$web_rootsD2 = str_replace('http', 'https', $web_rootsD);
    //$actionFrom = str_replace($web_rootsD2, '', $url);
    $returnAddr = $web_roots .  "/index.php?actionFrom=" . $actionFrom  . "&cchoice=diff#" . $short_histo_name ;
    //echo htmlspecialchars( $returnAddr, ENT_QUOTES, 'UTF-8' ) . "<br>";
    echo "<a href=\"" . $returnAddr . "\">BACK</a>";
    
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";
    
    $_SESSION['url'] = $url;

    $chemin = $chemin . '/' . $actionFrom;
    $chemin_eos2=str_replace($racine_html, $racine_eos, $chemin);
    
    $files = array_slice(scandir($chemin_eos2), 2);
    
    // Fill arrays with dirs & files
    foreach ($files as $key => $value)
    {
        //echo $value . '<br>';
        if (is_dir($chemin_eos2 . DIRECTORY_SEPARATOR . $value))
        {
            //echo $value . ' is dir<br>';
            $dirsList[] = $value;
            if ( substr($value, 0, 5) !== '.sys.') {
                $dirsList_date[] = $value;
            }
        }
        elseif (is_file($chemin_eos2 . DIRECTORY_SEPARATOR . $value))
        {
            $filesList[] = $value;
            //echo $value . ' is file<br>';
        }
        else
        {
            echo "unknown type : $value<br />";
        }
    }
    
    foreach ($dirsList as $key => $value)
    {
        if ( $value == "gifs" )
        {
            $pictsDir = True;
        }
        elseif ( $value == "pngs" ) // pbm : si le dernier repertoire est un png, ça zappe les gifs
        {
            $pictsDir = True;
        }
        elseif ( $value == "DBox" ) // pbm : si le dernier repertoire est un png, ça zappe les gifs
        {
            $DBoxflag = True;
        }
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
    }

    echo "<table class=\"tab0\">";
    echo '<tr><td>';// class="b3"
    {
        filter($url);
    }

    echo "</td>";
    echo "</tr>";
    echo "</table>";

    $choiceValue = $_REQUEST['choiceValue'];
    if ($choiceValue != '')
    {
        echo "value  : " . $choiceValue . " <br>";
    }

    echo "<br>";
    echo '<table border="">';
    echo "<tr><td>url = $url</td></tr>";
    echo "<tr><td>actionFrom = $actionFrom</td></tr>";
    echo "<tr><td>cchoice = $cchoice</td></tr>";
    echo "<tr><td>basket = $basket</td></tr>";
    echo "<tr><td>createF = $createF</td></tr>";
    echo "<tr><td>fileForHistos = $fileForHistos</td></tr>";
    echo "<tr><td>curveChoice = $curveChoice</td></tr>";
    echo "<tr><td>sharedF = $sharedF</td></tr>";
    echo "<tr><td>short_histo_name = $short_histo_name</td></tr>";
    echo '</table>';
    echo "<br>";
    
?>
</header>
