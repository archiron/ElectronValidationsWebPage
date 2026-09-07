<?php  
    session_start();

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
