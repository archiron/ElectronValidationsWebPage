<?php
    $histoSize = 440; // 200 440
    $allList = array();
    $allKeys = array();
    $dirsList_date = array();
    $tab_General = array();
    $tab_Keys = array();
    $tabPaths1 = array();
    $tabPaths2 = array();
    $tabHistos = array();
    $tabConfigs = array();
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

    tab histos : Array
    (
        [0] => Array
            (
                [0] => 
                [1] => Collections sizes
                [2] => 
                [3] => ElectronMcSignalValidator/h_recEleNum                       1 1 1 0
                [4] => ElectronMcSignalValidator/h_recCoreNum                      1 1 1 0
                [5] => ElectronMcSignalValidator/h_recTrackNum                     1 1 1 0
                [6] => ElectronMcSignalValidator/h_recOfflineVertices              1 1 1 0
                [7] => ElectronMcSignalValidator/h_recSeedNum                      1 1 1 1
                [8] => 
                [9] => Basic electron quantities
                [10] => 
                [11] => ElectronMcSignalValidator/h_ele_charge                      1 1 1 0

    tab configs : Array
    (
        [0] => Array
            (
                [0] => gedGsfElectronsZEE_14
                [1] => RECO
                [2] => 12_0_0_pre1
                [3] => DQM_V0001_R000000001__RelValZEE_14__CMSSW_12_0_0_pre1-113X_mcRun3_2021_realistic_v10-v1__DQMIO.root
                [4] => RECO
                [5] => 11_3_0_pre6
                [6] => DQM_V0001_R000000001__RelValZEE_14__CMSSW_11_3_0_pre6-113X_mcRun3_2021_realistic_v9-v1__DQMIO.root
                [7] => CMSSW_12_0_0_pre1 
                [8] => CMSSW_11_3_0_pre6 
                [9] => 
                [10] => config_target.txt
            )

        [1] => Array
            (
                [0] => gedGsfElectronsZEE_14

    */
?>

 