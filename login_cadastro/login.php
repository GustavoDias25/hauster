<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Iceberg&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Responsividade (carregar por último) -->
    <link rel="stylesheet" href="css/responsivo.css">
</head>
<body>
        <main class="area-logo">
              <picture>
                     <source media="(max-width: 1024px)" srcset="../assets/Logo-hauster.png">
                     <source media="(max-width: 600px)" srcset="../assets/favicon.png">
                     <img src="../assets/logo-vertical.png" alt="Descrição da imagem">
                </picture>
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

        <script>
            const cnpjInput = document.getElementById("CNPJ");
            const form = document.querySelector("form");
            cnpjInput.addEventListener("input", function () {
    let v = this.value.replace(/\D/g, "").substring(0, 14);

    v = v.replace(/^(\d{2})(\d)/, "$1.$2");
    v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
    v = v.replace(/(\d{4})(\d)/, "$1-$2");

    this.value = v;
});
        </script>
</body>
</html>