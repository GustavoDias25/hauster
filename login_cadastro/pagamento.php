<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {

header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/pagamento.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Iceberg&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <header>
        <nav class="menu flex-padrao">
            <div class="counteiner-logo">
                <img src="../assets/Logo-hauster.png" alt="logo" class="logo">
            </div>
            </div>
            <div class="botoes flex-padrao">
                <a href="login.php" class="botao-header">Login</a>
            </div>    
            </ul>
        </nav>
    </header>
    <div class="big-div">
        <section class="pagamento">
            <div class="container mt-5">
                <div class="pagamento-container">
                    <button class="d-flex justify-content-between align-items-center p-3 credit_card select" type="button"
                            data-bs-toggle="collapse" data-bs-target="#Pix" aria-expanded="false" aria-controls="Pix">
                        <span>Pix</span>
                        <span>↓</span>
                    </button>
                    <div class="collapse" id="Pix">
                        <div class="border border-top-0 rounded-bottom p-3 credit_card text-center">
                            <img src="../assets/qrcode.jpeg" alt="QR Code Pix" class="qrcode-pix">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <div class="pagamento-container">
                    <button class="d-flex justify-content-between align-items-start p-3 credit_card select" type="button" data-bs-toggle="collapse" data-bs-target="#formCartao" aria-expanded="false" aria-controls="formCartao">
                        <span>Cartão</span>
                        <span>↓</span>
                    </button>
                    <div class="collapse" id="formCartao">
                        <div class="border border-top-0 rounded-bottom p-3 credit_card">
                            <input type="text" class="form-control mb-3" placeholder="Número do cartão" require>
                            <input type="text" class="form-control mb-3" placeholder="Nome impresso no cartão" require>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" class="form-control" placeholder="Validade" require>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" placeholder="CVV" require>
                                </div>
                                <div class="col-6">
                                    <input type="submit" class="form-control btn btn-success submit" value="Salvar">
                                </div>
                                <div class="col-6">
                                    <input type="submit" class="form-control btn btn-secondary submit" value="Cancelar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <aside class="aside">
                <h1>Resumo do pedido</h1>
                <div class="aside-conteudo">
                    <p>Item: Plano empresarial</p>
                    <p>Valor total: R$599,88</p>
                    <p>Valor mensal: R$49,99</p>
                </div>
                <div class="botoes-aside">
                    <a href="../dashboard.php?pagina=home" id="concluir">Concluir</a>
                    <a href="planos.php" id="cancelar">Cancelar</a>
                </div>
        </aside>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>