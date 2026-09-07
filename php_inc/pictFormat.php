<?php  
    session_start();
    /*if (isset($_POST['pictFormat'])) {
        echo 'pictFormat OK in pictFormat.php<br>' . "\n";
        //echo $_POST['pictFormat'];
        echo htmlspecialchars($_POST['pictFormat'], ENT_QUOTES, 'UTF-8') . '<br>' . "\n";
        $string_to_check = htmlspecialchars($_POST['pictFormat'], ENT_QUOTES, 'UTF-8');
        if (preg_match('/<\?php|<\?/i', $string_to_check)) {
            http_response_code(403);
            die("Erreur : Tentative d'injection de code détectée.");
        }

        $_SESSION['pictFormat'] = str_replace('"', '', $string_to_check);
    }*/

    if (isset($_POST['pictFormat'])) {
        $allowed = ['png', 'gif', 'jpg', 'jpeg', 'webp'];

        $value = strtolower(trim($_POST['pictFormat']));

        if (in_array($value, $allowed, true)) {
            $_SESSION['pictFormat'] = $value;
        } else {
            http_response_code(400);
            die("Format non autorisé.");
        }
    }
?>
