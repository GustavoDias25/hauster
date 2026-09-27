<?php
session_start();
if (!empty($_SESSION['logado'])){
    session_destroy();
    header("Location: index.html");
    exit();
}
else{
    header("Location: index.php");
    exit();
}


?>