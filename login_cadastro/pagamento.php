<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {

header('Location: index.php');
    exit;
}

require_once '../includes/conexao.php';

// Busca o plano no banco pelo nome que veio da tela de planos (o preço vem do banco, não da URL)
$stmt = $conexao->prepare('SELECT idplanos, nome, preco FROM planos WHERE nome = :nome');
$stmt->execute([':nome' => $_GET['plano'] ?? '']);
$dados_plano = $stmt->fetch();

if (!$dados_plano) {
    header('Location: planos.php');
    exit;
}

$plano = $dados_plano['nome'];
$preco = $dados_plano['preco'];
$preco_total = $preco * 12;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/pagamento.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Iceberg&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&icon_names=credit_card&display=block" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Responsividade (carregar por último) -->
    <link rel="stylesheet" href="css/responsivo.css">
</head>
<body>
    <header class="header-pagamento">
        <nav class="menu">
            <div class="menu-logo container">
                <source media="(max-width: 1024px)" srcset="../assets/Logo-hauster.png" class="logo">
                <img src="../assets/Logo-hauster.png" alt="Descrição da imagem" class="logo">
                <source media="(max-width: 600px)" srcset="../assets/favicon.png" class="logo">
            </div>
            <div class="menu-botao container">
                <a href="login.php" class="botao-header">Login</a>
            </div>
        </nav>
    </header>
    <div class="big-div">
        <section class="pagamento">
            <div class="container mt-5">
                <div class="pagamento-container">
                    <button class="d-flex justify-content-between align-items-center p-3 credit_card select" type="button"data-bs-toggle="collapse" data-bs-target="#Pix" aria-expanded="false" aria-controls="Pix">
                        <span class="d-flex align-items-center gap-2"><svg class="flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="#32BCAD" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5.283 18.36a3.505 3.505 0 0 0 2.493-1.032l3.6-3.6a.684.684 0 0 1 .946 0l3.613 3.613a3.504 3.504 0 0 0 2.493 1.032h.71l-4.56 4.56a3.647 3.647 0 0 1-5.156 0L4.85 18.36ZM18.428 5.627a3.505 3.505 0 0 0-2.493 1.032l-3.613 3.614a.67.67 0 0 1-.946 0l-3.6-3.6A3.505 3.505 0 0 0 5.283 5.64h-.434l4.573-4.572a3.646 3.646 0 0 1 5.156 0l4.559 4.559ZM1.068 9.422 3.79 6.699h1.492a2.483 2.483 0 0 1 1.744.722l3.6 3.6a1.73 1.73 0 0 0 2.443 0l3.614-3.613a2.482 2.482 0 0 1 1.744-.723h1.767l2.737 2.737a3.646 3.646 0 0 1 0 5.156l-2.736 2.736h-1.768a2.482 2.482 0 0 1-1.744-.722l-3.613-3.613a1.77 1.77 0 0 0-2.444 0l-3.6 3.6a2.483 2.483 0 0 1-1.744.722H3.791l-2.723-2.723a3.646 3.646 0 0 1 0-5.156"/></svg>Pix</span>
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
                        <span class="d-flex align-items-center gap-2"><span class="material-symbols-outlined" aria-hidden="true">credit_card</span>Cartão</span>
                        <span>↓</span>
                    </button>
                    <div class="collapse" id="formCartao">
                        <div class="border border-top-0 rounded-bottom p-3 credit_card">
                            <input type="text" id="cartao-numero" class="form-control mb-3" placeholder="Número do cartão" inputmode="numeric" autocomplete="cc-number" maxlength="19" require>
                            <input type="text" id="cartao-nome" class="form-control mb-3" placeholder="Nome impresso no cartão" autocomplete="cc-name" require>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" id="cartao-validade" class="form-control" placeholder="Validade" inputmode="numeric" autocomplete="cc-exp" maxlength="5" require>
                                </div>
                                <div class="col-6">
                                    <input type="text" id="cartao-cvv" class="form-control" placeholder="CVV" inputmode="numeric" autocomplete="cc-csc" maxlength="4" require>
                                </div>
                                <div class="col-6">
                                    <input type="submit" class="form-control btn submit" value="Salvar" id="salvar" onclick="return alert('Cartão salvo com sucesso');">
                            </div>
                                <div class="col-6">
                                    <input type="submit" class="form-control btn submit" value="Cancelar" id="cancelar">
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
                <p><span class="rotulo">Item:</span> <span class="valor">Plano <?= htmlspecialchars($plano) ?></span></p>
                <p><span class="rotulo">Valor total:</span> <span class="valor">R$<?= number_format((float)$preco_total, 2, ',', '.') ?></span></p>
                <p><span class="rotulo">Valor mensal:</span> <span class="valor">R$<?= number_format((float)$preco, 2, ',', '.') ?></span></p>
            </div>
            <div class="botoes-aside">
                <a href="concluir_plano.php?plano=<?= (int) $dados_plano['idplanos'] ?>" id="concluir">Concluir</a>
                <a href="planos.php" id="cancelar">Cancelar</a>
            </div>
        </aside>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Máscaras do formulário de cartão
        (function () {
            var numero   = document.getElementById('cartao-numero');
            var validade = document.getElementById('cartao-validade');
            var cvv      = document.getElementById('cartao-cvv');
            var nome     = document.getElementById('cartao-nome');

            // Número: só dígitos, agrupados de 4 em 4
            numero.addEventListener('input', function () {
                var d = this.value.replace(/\D/g, '').slice(0, 16);
                this.value = d.replace(/(.{4})/g, '$1 ').trim();
            });

            // Validade: MM/AA
            validade.addEventListener('input', function () {
                var d = this.value.replace(/\D/g, '').slice(0, 4);
                this.value = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
            });

            // CVV: só dígitos
            cvv.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 4);
            });

            // Nome: tudo em maiúsculas (mantém o cursor onde estava)
            nome.addEventListener('input', function () {
                var ini = this.selectionStart, fim = this.selectionEnd;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(ini, fim);
            });
        })();
    </script>
</body>
</html>