<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Iceberg&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
        <main class="area-logo">
            <img src="../assets/logo-vertical.png" alt="logo">
        </main>
        <section class="area-form">
            <h1>Login</h1>

            <?php if (!empty($_SESSION['erro_login'])): ?>
                <p class="erro"><?= $_SESSION['erro_login'] ?></p>
                <?php unset($_SESSION['erro_login']); ?>
            <?php endif; ?>

            <form action="logar.php" method="post">
                <div class="inputs">
                    <input type="text" name="CNPJ" id="CNPJ" placeholder="Digite seu CNPJ">
                </div>
                <div class="inputs" >
                    <input type="password" name="senha" id="senha" placeholder="Digite sua senha">
                </div>
                <div class="botao-form">
                    <input type="submit" value="Entrar">
                </div>
            </form>
            <p>Não possui conta? <strong><a href="index.php">Cadastrar</a></strong></p>
        </section>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>