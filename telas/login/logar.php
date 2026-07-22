<?php

$cnpj = $_POST['CNPJ'];
$senha = $_POST['senha'];



if($cnpj == 1111 && $senha == 1234){
    header('Location: ../dashboard/index.php');
} else{
    header('Location: login.php');
    echo '<script> alert("CNPJ ou senha erradas") </script>';
}
?>