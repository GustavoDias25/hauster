<?php
include_once "includes/helpers_funcionarios.php";

$estabelecimento_id = $_SESSION['estabelecimento_id'];
$erro = '';
$id = isset($_GET['id_func']) ? (int) $_GET['id_func'] : 0;

if ($id <= 0) {
    header('Location: dashboard.php?pagina=funcionarios');
    exit;
}

$cargos = $conexao->query("SELECT * FROM cargos ORDER BY idcargos")->fetchAll();

// Busca o funcionário (só se for do estabelecimento logado)
$stmt = $conexao->prepare(
    "SELECT * FROM funcionarios
     WHERE idfuncionarios = :id AND estabelecimento_idestabelecimento = :estab"
);
$stmt->execute([':id' => $id, ':estab' => $estabelecimento_id]);
$func = $stmt->fetch();

if (!$func) {
    header('Location: dashboard.php?pagina=funcionarios');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atenção: $func passa a ser o que foi digitado (CPF/telefone só com números). A senha atual
    // continua no banco e NÃO é lida aqui: senha em branco = não mexe na coluna senha.
    $func = ler_dados_funcionario();
    $erro = validar_funcionario($func, $cargos, false);

    if ($erro === '') {
        $stmt = $conexao->prepare(
            "SELECT COUNT(*) FROM funcionarios
             WHERE cpf = :cpf AND estabelecimento_idestabelecimento = :estab AND idfuncionarios <> :id"
        );
        $stmt->execute([':cpf' => $func['cpf'], ':estab' => $estabelecimento_id, ':id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            $erro = 'Já existe outro funcionário com este CPF.';
        }
    }

    if ($erro === '') {
        try {
            // A senha só entra no UPDATE se foi digitada
            $troca_senha = $func['senha'] !== '';

            $stmt = $conexao->prepare(
                "UPDATE funcionarios
                 SET nome = :nome, dt_nasc = :dt_nasc, salario = :salario,
                     cpf = :cpf, email = :email, telefone = :telefone, cargos_idcargos = :cargo"
                 . ($troca_senha ? ", senha = :senha" : "") . "
                 WHERE idfuncionarios = :id AND estabelecimento_idestabelecimento = :estab"
            );
            $dados = [
                ':nome'     => $func['nome'],
                ':dt_nasc'  => $func['dt_nasc'],
                ':salario'  => $func['salario'],
                ':cpf'      => $func['cpf'],
                ':email'    => $func['email'],
                ':telefone' => $func['telefone'],
                ':cargo'    => $func['cargos_idcargos'],
                ':id'       => $id,
                ':estab'    => $estabelecimento_id,
            ];
            if ($troca_senha) {
                $dados[':senha'] = password_hash($func['senha'], PASSWORD_DEFAULT);
            }
            $stmt->execute($dados);

            header('Location: dashboard.php?pagina=funcionarios');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar funcionário: ' . $e->getMessage();
        }
    }
}

$acao_form   = 'dashboard.php?pagina=funcionarios_editar&id_func=' . $id;
$texto_botao = 'Salvar alterações';
?>
<main class="tabela content fundo">
    <h1 class="titulo">Editar funcionário</h1>

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
<form method="POST" action="<?= $acao_form ?>" class="form-produto">
    <div class="campos">
        <div class="campo">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" maxlength="45" placeholder="Nome do funcionário"
                   value="<?= htmlspecialchars($func['nome']) ?>" required>
        </div>
        <div class="campo">
            <label for="dt_nasc">Data de nascimento</label>
            <input type="date" id="dt_nasc" name="dt_nasc" max="<?= date('Y-m-d') ?>"
                   value="<?= htmlspecialchars($func['dt_nasc']) ?>" required>
        </div>
        <div class="campo">
            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00"
                   value="<?= strlen($func['cpf']) === 11 ? formatar_cpf($func['cpf']) : htmlspecialchars($func['cpf']) ?>" required>
        </div>
        <div class="campo">
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" maxlength="15" placeholder="(00) 00000-0000"
                   value="<?= htmlspecialchars(formatar_telefone($func['telefone'])) ?>" required>
        </div>
        <div class="campo">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" maxlength="45" placeholder="E-mail do funcionário"
                   value="<?= htmlspecialchars($func['email']) ?>" required>
        </div>
        <div class="campo">
            <label for="senha">Senha</label>
            <!-- Nunca vem preenchida (o banco guarda só o hash). Em branco = mantém a senha atual -->
            <input type="password" id="senha" name="senha" autocomplete="new-password" placeholder="Em branco = manter a atual">
        </div>
        <div class="campo">
            <label for="salario">Salário</label>
            <input type="number" id="salario" name="salario" step="0.01" min="0" placeholder="Salário do funcionário"
                   value="<?= htmlspecialchars($func['salario']) ?>" required>
        </div>
        <div class="campo">
            <label for="cargo">Cargo</label>
            <select name="cargo" id="cargo" required>
                <option value="">Selecione um cargo</option>
                <?php foreach ($cargos as $cargo): ?>
                    <option value="<?= $cargo['idcargos'] ?>" <?= $cargo['idcargos'] == $func['cargos_idcargos'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cargo['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="acoes campo">
            <button type="submit"><?= $texto_botao ?></button>
            <a href="dashboard.php?pagina=funcionarios"><button type="button" id="cancelar">Cancelar</button></a>
        </div>
    </div>
</form>
<script src="dashboard/funcionarios/script/mascaras.js"></script>
</main> 