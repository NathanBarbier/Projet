<?php 

// $request    = $_GET['request'] ?? false;
$on  = $_GET['on'] ?? false;
$type    = $_GET['type'] ?? false;
$title    = $_GET['title'] ?? false;
$msg    = $_GET['msg'] ?? false;

if(!empty($errors)) {
    foreach($errors as $error) {
        $on = true;
        $type = 'error';
        $title = 'Erreur';
        $message = $error;
    }
} else if(!empty($success)) {
    $on = true;
    $type = 'success';
    $title = 'Succès';
    $message = $success;
}

// put messages on a lang file
if($msg == 'connexion') {
    $message = 'Content de vous revoir connecté !';
}

// clear the url



if(!empty($type) && !empty($on)) 
{
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
    }

    ?>
    <script>        
    var url = <?php echo json_encode($url); ?>;
    var type = <?php echo json_encode($type); ?>;
    var title = <?php echo json_encode($title); ?>;
    var message = <?php echo json_encode($message); ?>;
    var icon = <?php echo json_encode($icon); ?>;

    // url rewrite (remove get params)
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", url);
    }
    </script>
    <?php
}
?>

