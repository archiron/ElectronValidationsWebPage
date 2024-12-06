<?php

$_fDL = '<br>' . "\n"; // fin de ligne

function histoCheck($tablo, $histoName) {
    //echo "histoCheck : " . $histoName . "<br>\n";
    $Globos4 = [];
    if ($histoName != '') {
        foreach ($tablo as $value0) {
            foreach (array("gifs", "pngs") as $value) {
                $ext = substr($value, 0, 3); //simPrint('ext', $ext);
                $path_1 = $value0 . '/' . $value . '/' . $histoName . '.' . $ext;
                if (file_exists($path_1)) {
                    //echo 'picture : ' . $path_1 . "<br>\n";
                    $Globos4[] = $path_1;
                }
            }
        }
        //$tablo = $Globos4;
        return $Globos4;
    }
    /*else {
        return $tablo;
    }*/
}

function tagCheck($tablo, $chemin_eos, $tag) {
    //echo "tagCheck : " . $tag . "<br>\n";
    $Globos4 = [];
    if ($tag != '') {
        foreach ($tablo as $value0) {
            $tmp0 = str_replace($chemin_eos, '', $value0);
            $tmp1 = explode('/', $tmp0);
            //echo explode('_', $tmp1[2], 2)[0] . "<br>\n";
            if (explode('_', $tmp1[2], 2)[0] == $tag) {
                $Globos4[] = $value0;
            }
        }
        //$tablo = $Globos4;
        return $Globos4;
    }
    else {
        return $tablo;
    }
}

function fullFastCheck($tablo, $chemin_eos, $compFF) {
    //echo "compFFCheck : " . $compFF . "<br>\n";
    $Globos4 = [];
    if ($compFF != '') {
        foreach ($tablo as $value0) {
            $tmp0 = str_replace($chemin_eos, '', $value0);
            $tmp1 = explode('/', $tmp0);
            if (explode('_', $tmp1[1], 2)[0] == $compFF) {
                $Globos4[] = $value0;
            }
        }
        //$tablo = $Globos4;
        return $Globos4;
    }
    else {
        return $tablo;
    }
}

function releaseCheck($tablo, $chemin_eos, $release) {
    //echo "releaseCheck : " . $release . "<br>\n";
    $Globos4 = [];
    if ($release != '') {
        foreach ($tablo as $value0) {
            $tmp0 = str_replace($chemin_eos, '', $value0);
            $tmp1 = explode('/', $tmp0);
            if ($tmp1[0] == $release . '_DQM_std') {
                $Globos4[] = $value0;
            }
        }
        //$tablo = $Globos4;
        return $Globos4;
    }
    else {
        return $tablo;
    }
}

function referenceCheck($tablo, $chemin_eos, $reference) {
    //echo "referenceCheck : " . $reference . "<br>\n";
    $Globos4 = [];
    if ($reference != '') {
        foreach ($tablo as $value0) {
            $tmp0 = str_replace($chemin_eos, '', $value0);
            $tmp1 = explode('/', $tmp0);
            $tmp2 = explode('_', $tmp1[1], 2);
            //echo "referenceCheck : " . $tmp2[1] . "<br>\n";
            if ($tmp2[1] == $reference) {
                $Globos4[] = $value0;
            }/**/
        }
        //$tablo = $Globos4;
        return $Globos4;
    }
    else {
        return $tablo;
    }
}

function datasetCheck($tablo, $chemin_eos, $dataset) {
    //echo "datasetCheck : " . $dataset . "<br>\n";
    $Globos4 = [];
    if ($dataset != '') {
        foreach ($tablo as $value0) {
            $tmp0 = str_replace($chemin_eos, '', $value0);
            $tmp1 = explode('/', $tmp0);
            if (explode('_', $tmp1[2], 2)[1] == $dataset) {
                $Globos4[] = $value0;
            }
        }
        //$tablo = $Globos4;
        return $Globos4;
    }
    else {
        return $tablo;
    }
}

function getPictFolderName($path, $histoName) {
    //echo $path . "<br>\n";
    foreach (array("gifs", "pngs") as $value) {
        $ext = substr($value, 0, 3); //simPrint('ext', $ext);
        $path_1 = $path . '/' . $value . '/' . $histoName . '.' . $ext;
        if (file_exists($path_1)) {
            //simPrint('picture.'.$value, $path_1);
            return $path_1;
        }
    }
    return ''; // case without picture
}
?>
