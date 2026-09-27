<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
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
            <h1>Cadastrar</h1>
            <?php if (!empty($_SESSION['erro_cad'])): ?>
                <p class="erro"><?= htmlspecialchars($_SESSION['erro_cad']) ?></p>
                <?php unset($_SESSION['erro_cad']); ?>
            <?php endif; ?>
           <form action="cadastrar.php" method="post" enctype="multipart/form-data">
    <div class="inputs">
        <input type="text" name="nome" id="nome" placeholder="Nome do Petshop" require>
    </div>
    <div class="inputs">
        <input type="text" name="CNPJ" id="CNPJ" placeholder="CNPJ do petshop" maxlength="18" required>
    </div>
    <div class="inputs">
        <input type="email" name="email" id="email" placeholder="Email do petshop" require>
    </div>
    <div class="inputs">
        <input type="password" name="senha" id="senha" placeholder="Crie uma senha" require>
    </div>
    <div class="inputs">
        <input type="password" name="conf_senha" id="conf_senha" placeholder="Confirmar senha" require>
    </div>
    <div class="inputs">
        <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/webp">
    </div>
    <div class="botao-form">    
        <input type="submit" value="Cadastrar">
    </div>
</form>
            <p>Já possui conta? <strong><a href="login.php">Logar</a></strong></p>
        </section>
       <!-- <script>
const cnpjInput = document.getElementById("CNPJ");
const form = document.querySelector("form");

// Máscara: 12.345.678/0001-95
cnpjInput.addEventListener("input", function () {
    let v = this.value.replace(/\D/g, "").substring(0, 14);

    v = v.replace(/^(\d{2})(\d)/, "$1.$2");
    v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
    v = v.replace(/(\d{4})(\d)/, "$1-$2");

    this.value = v;
});

function calcDigito(base) {
    let pos = base.length - 7;
    let soma = 0;

    for (let i = 0; i < base.length; i++) {
        soma += parseInt(base[i]) * pos--;
        if (pos < 2) pos = 9;
    }

    const resto = soma % 11;
    return resto < 2 ? 0 : 11 - resto;
}

function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, "");

    if (cnpj.length !== 14 || /^(\d)\1+$/.test(cnpj)) {
        return false;
    }

    const d1 = calcDigito(cnpj.substring(0, 12));
    const d2 = calcDigito(cnpj.substring(0, 12) + d1);

    return cnpj.endsWith("" + d1 + d2);
}

// Valida ao enviar o formulário
form.addEventListener("submit", function (event) {
    if (!validarCNPJ(cnpjInput.value)) {
        event.preventDefault();
        alert("CNPJ inválido! Digite um CNPJ válido.");
        cnpjInput.focus();
    }
});
</script>-->
</body>
</html>
