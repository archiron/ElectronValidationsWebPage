<?php  
    session_start();
    if (isset($_POST['pictFormat'])) {
        echo 'pictFormat OK in pictFormat.php' . '<br>' . "\n";
        echo $_POST['pictFormat'];
        //$pictFormat = stripcslashes($_POST['pictFormat']);
        $_SESSION['pictFormat'] = str_replace('"', '', $_POST['pictFormat']);
    }
?>
