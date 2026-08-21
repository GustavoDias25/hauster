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
</head>
<body>
        <main class="area-logo">
            <img src="../assets/logo-vertical.png" alt="logo">
        </main>
        <section class="area-form">
            <h1>Login</h1>

            <?php if (!empty($_SESSION['erro_login'])): ?>
                <p class="erro"><?= htmlspecialchars($_SESSION['erro_login']) ?></p>
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
</body>
</html>