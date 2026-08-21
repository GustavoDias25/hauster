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
            <form action="cadastrar.php" method="post">
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
                <div class="botao-form">    
                    <input type="submit" value="Cadastrar">
                </div>
            </form>
            <p>Já possui conta? <strong><a href="login.php">Logar</a></strong></p>
        </section>
        <script>
const cnpj = cnpjInput.value;
cnpjInput.addEventListener("input", function () {

    let valor = this.value.replace(/\D/g, "");

    if (valor.length > 14) {
        valor = valor.substring(0, 14);
    }

    // 12.345.678/0001-95
    if (valor.length > 12) {
        valor = valor.replace(
            /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,
            "$1.$2.$3/$4-$5"
        );
    } 
    else if (valor.length > 8) {
        valor = valor.replace(
            /^(\d{2})(\d{3})(\d{3})(\d{0,4})$/,
            "$1.$2.$3/$4"
        );
    } 
    else if (valor.length > 5) {
        valor = valor.replace(
            /^(\d{2})(\d{3})(\d{0,3})$/,
            "$1.$2.$3"
        );
    } 
    else if (valor.length > 2) {
        valor = valor.replace(
            /^(\d{2})(\d{0,3})$/,
            "$1.$2"
        );
    }

    this.value = valor;

    function validarCNPJ(cnpj) {

    cnpj = cnpj.replace(/\D/g, "");

    // CNPJ precisa ter 14 números
    if (cnpj.length !== 14) {
        return false;
    }

    // Impede CNPJs como 00.000.000/0000-00
    if (/^(\d)\1+$/.test(cnpj)) {
        return false;
    }

    let tamanho = 12;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);

    let soma = 0;
    let pos = tamanho - 5;

    for (let i = 0; i < tamanho; i++) {
        soma += parseInt(numeros.charAt(i)) * pos;

        pos--;

        if (pos < 2) {
            pos = 9;
        }
    }

    let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);

    if (resultado != parseInt(digitos.charAt(0))) {
        return false;
    }

    tamanho = 13;
    numeros = cnpj.substring(0, tamanho);

    soma = 0;
    pos = tamanho - 5;

    for (let i = 0; i < tamanho; i++) {
        soma += parseInt(numeros.charAt(i)) * pos;

        pos--;

        if (pos < 2) {
            pos = 9;
        }
    }

    resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);

    if (resultado != parseInt(digitos.charAt(1))) {
        return false;
    }

    return true;
}

// Valida quando enviar o formulário
document.querySelector("form").addEventListener("submit", function(event) {

    const cnpj = campoCNPJ.value;

    if (!validarCNPJ(cnpj)) {
        event.preventDefault();

        alert("CNPJ inválido! Digite um CNPJ válido.");

        campoCNPJ.focus();
    }
});
});
</script>
</body>
</html>