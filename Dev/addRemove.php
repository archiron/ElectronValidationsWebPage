<?php  
    session_start();
    if (isset($_POST['pTableData'])) {
        echo 'pTableData OK in addRemove.php' . '<br>' . "\n";
        echo $_POST['pTableData']; 
        $tableData = stripcslashes($_POST['pTableData']);
        $tableData = json_decode($tableData,TRUE);
        foreach ($tableData as $key => $value) {
            echo $value . '<br>' . "\n";
        }
        file_put_contents($_SESSION['fileForHistos_eos'], implode(PHP_EOL, $tableData));
        $corresp = 1;
    }
    if (isset($_POST['selLink2'])) {
        echo 'selLink2 OK in addRemove.php' . '<br>' . "\n";
        echo $_POST['selLink2']; 
    }/**/
?>
