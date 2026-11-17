<?php
session_start();
require_once '../includes/conexao.php';
require_once '../includes/permissoes.php'; // para saber se o cargo do funcionário tem alguma tela liberada

// Só processa se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Validação básica de entrada
if ($email === '' || $senha === '') {
    $_SESSION['erro_login'] = 'Preencha e-mail e senha.';
    header('Location: login.php');
    exit;
}

try {
    // 1) Login do ESTABELECIMENTO (dono): acesso total
    $stmt = $conexao->prepare(
        "SELECT idestabelecimento, senha, nome_fantasia, foto
         FROM estabelecimento
         WHERE email = :email"
    );
    $stmt->execute([':email' => $email]);
    $estabelecimento = $stmt->fetch();

    if ($estabelecimento && password_verify($senha, $estabelecimento['senha'])) {
        session_regenerate_id(true);

        $_SESSION['logado'] = true;
        $_SESSION['estabelecimento_id'] = $estabelecimento['idestabelecimento'];
        $_SESSION['nome_fantasia'] = $estabelecimento['nome_fantasia'];
        $_SESSION['foto'] = $estabelecimento['foto'];
        unset($_SESSION['funcionario_id']); // sem funcionario_id = dono

        header('Location: ../dashboard.php?pagina=home');
        exit;
    }

    // 2) Login de FUNCIONÁRIO: o mesmo e-mail pode existir em mais de um estabelecimento,
    //    então confere a senha de cada um que tiver esse e-mail
    $stmt = $conexao->prepare(
        "SELECT f.idfuncionarios, f.senha, f.cargos_idcargos, f.estabelecimento_idestabelecimento,
                e.nome_fantasia, e.foto
         FROM funcionarios f
         JOIN estabelecimento e ON e.idestabelecimento = f.estabelecimento_idestabelecimento
         WHERE f.email = :email"
    );
    $stmt->execute([':email' => $email]);

    foreach ($stmt->fetchAll() as $func) {
        if (!password_verify($senha, (string) $func['senha'])) {
            continue;
        }

        // Senha certa, mas o cargo não tem nenhuma tela liberada (ex.: "Outros")
        if (empty(PERMISSOES_POR_CARGO[(int) $func['cargos_idcargos']])) {
            $_SESSION['erro_login'] = 'Seu cargo não tem acesso ao sistema.';
            header('Location: login.php');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['logado'] = true;
        $_SESSION['funcionario_id'] = $func['idfuncionarios']; // é isso que faz o sistema aplicar as permissões do cargo
        $_SESSION['estabelecimento_id'] = $func['estabelecimento_idestabelecimento'];
        $_SESSION['nome_fantasia'] = $func['nome_fantasia'];
        $_SESSION['foto'] = $func['foto'];

        // O dashboard.php manda cada cargo para a primeira tela que ele pode acessar
        header('Location: ../dashboard.php?pagina=home');
        exit;
    }

    $_SESSION['erro_login'] = 'E-mail ou senha incorretos';
    header('Location: login.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['erro_login'] = 'Erro ao logar';
    header('Location: login.php');
    exit;
}
