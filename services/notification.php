<?php

$on     = $_GET['on'] ?? false;
$type   = $_GET['type'] ?? false;
$title  = $_GET['title'] ?? false;
$message    = $_GET['msg'] ?? false;

if(!empty($errors)) {
    $on = true;
    $type = 'error';
    $title = 'Erreur';
    $message = '';
    foreach($errors as $error) {
        $message .= $error . "<br>";
    }
} else if(!empty($success)) {
    $on = true;
    $type = 'success';
    $title = 'Succès';
    $message = $success;
}


// put messages on a lang file
if($message == 'connexion') {
    if(!empty($User)) {
        $username = $User ? ($User->getFirstname() ?? $User->getEmail()) : '';
        $username .= ' ';
    } else {
        $username = '';
    }
    $message = 'Content de vous revoir connecté ' . $username . '!';
}

    switch ($type) {
        case 'info':
            $class  = 'toast-info';
            $icon   = 'bi-info-circle';
            break;
        case 'success':
            $class  = 'toast-success';
            $icon   = 'bi-check-circle';
            break;
        case 'error':
            $class  = 'toast-error';
            $icon   = 'bi-exclamation-octagon';
            break;
        case 'warning':
            $class  = 'toast-warning';
            $icon   = 'bi-exclamation-triangle';
            break;
        default:
            $class  = false;
            $icon   = false;
            break;
    }
?>

    <script>    
    var url = <?php echo json_encode($url); ?>;
    var type = <?php echo json_encode($type); ?>;
    var title = <?php echo json_encode($title); ?>;
    var message = <?php echo json_encode($message); ?>;
    var icon = <?php echo json_encode($icon); ?>;

    // url rewrite (remove get params)
    if(window.location.href.includes('/index.php') || window.location.href.includes('/tableauDeBord.php')) {
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", url);
        }
    }
    </script>
    <?php
?>

