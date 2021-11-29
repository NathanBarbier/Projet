<?php
/**
 * Return the $_POST or $_GET value matching with the $dataname parameter
 */
function GETPOST(string $dataName)
{
    if(!empty($_POST[$dataName]))
    {
        return $_POST[$dataName];
    }
    else if(!empty($_GET[$dataName]))
    {
        return $_GET[$dataName];
    }
    else
    {
        return false;
    }

}

function generateRandomString($length = 10) 
{
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

?>