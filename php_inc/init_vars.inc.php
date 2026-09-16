<?php
    $histoSize = 440; // 200 440
    $allList = array();
    $allKeys = array();
    $dirsList_date = array();
    $tab_General = array();
    $tab_Keys = array();
    $tabPaths1 = array();
    $tabPaths2 = array();
    $choiceValue = '';

    $filesList = array();
    $tab_Others = array();
    $tab_CMSSW = array();
    $dirsList = array();
    $lineHisto1 = array();
    $pictsDir = False;
    $pictsValue = "gifs"; // default
    $pictsExt = ".gif"; // default
    $indexHtml = False;
    $histosFile = False;
    $basket = '';
    $fileForHistos = '';
    $curveChoice = '';
    $sharedF = '';
    $short_histo_name = '';

    /*
    tab general : Array
    (
        [10] => Array
            (
                [10_2_0_pre6_DQM_std] => Array
                    (
                        [FullvsFull_10_2_0_pre5] => Array
                            (
                                [0] => PU25-PU25_TTbar_13
                                [1] => PU25-PU25_ZEE_13
                            )
                    )

                [10_2_0_pre6_jonastest_DQM_std] => Array
                    (
                        [FullvsFull_10_2_0_pre5_jonastest] => Array
                            (
                                [0] => RECO-RECO_QCD_Pt_80_120_13
                                [1] => RECO-RECO_SingleElectronPt10
                                [2] => RECO-RECO_SingleElectronPt1000
                                [3] => RECO-RECO_SingleElectronPt35
                                [4] => RECO-RECO_TTbar_13
                                [5] => RECO-RECO_ZEE_13
                            )
                    )

    all folders : Array
    (
        [10_2_0_pre6_DQM_std] => Array
            (
                [FullvsFull_10_2_0_pre5] => Array
                    (
                        [0] => PU25-PU25_TTbar_13
                        [1] => PU25-PU25_ZEE_13
                    )
            )

        [10_2_0_pre6_jonastest_DQM_std] => Array
            (
                [FullvsFull_10_2_0_pre5_jonastest] => Array
                    (
                        [0] => RECO-RECO_QCD_Pt_80_120_13
                        [1] => RECO-RECO_SingleElectronPt10
                        [2] => RECO-RECO_SingleElectronPt1000
                        [3] => RECO-RECO_SingleElectronPt35
                        [4] => RECO-RECO_TTbar_13
                        [5] => RECO-RECO_ZEE_13
                    )
            )
    )

    dirsList_date : Array
    (
        [0] => /eos/project/c/cmsweb/www/egamma/validation/Electrons/Dev/10_2_0_pre6_DQM_std
        [1] => /eos/project/c/cmsweb/www/egamma/validation/Electrons/Dev/10_2_0_pre6_jonastest_DQM_std
        [2] => /eos/project/c/cmsweb/www/egamma/validation/Electrons/Dev/10_6_1_UL_test_DQM_std
        [3] => /eos/project/c/cmsweb/www/egamma/validation/Electrons/Dev/10_6_1_patch1_2021_14TeV_DQM_std
        [4] => /eos/project/c/cmsweb/www/egamma/validation/Electrons/Dev/10_6_1_patch1_2024_14TeV_DQM_std
    )
    
        tab paths : Array
    (
        [0] => Array
            (
                [0] => 10_2_0_pre6_DQM_std/FullvsFull_10_2_0_pre5/PU25-PU25_TTbar_13
                [1] => 10_2_0_pre6_DQM_std/FullvsFull_10_2_0_pre5/PU25-PU25_ZEE_13
                [2] => 10_2_0_pre6_jonastest_DQM_std/FullvsFull_10_2_0_pre5_jonastest/RECO-RECO_QCD_Pt_80_120_13
                [3] => 10_2_0_pre6_jonastest_DQM_std/FullvsFull_10_2_0_pre5_jonastest/RECO-RECO_SingleElectronPt10
                [4] => 10_2_0_pre6_jonastest_DQM_std/FullvsFull_10_2_0_pre5_jonastest/RECO-RECO_SingleElectronPt1000
                [5] => 10_2_0_pre6_jonastest_DQM_std/FullvsFull_10_2_0_pre5_jonastest/RECO-RECO_SingleElectronPt35
                [6] => 10_2_0_pre6_jonastest_DQM_std/FullvsFull_10_2_0_pre5_jonastest/RECO-RECO_TTbar_13
    )

    */
?>

 