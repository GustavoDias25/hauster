<?php
include_once "includes/helpers_funcionarios.php";

$estabelecimento_id = $_SESSION['estabelecimento_id'];
$erro = '';

// Valores do formulário: vazio ao abrir a página; se der erro, volta com o que foi digitado
$func = [
    'nome' => '', 'dt_nasc' => '', 'salario' => '', 'cpf' => '',
    'email' => '', 'telefone' => '', 'senha' => '', 'cargos_idcargos' => '',
];

$cargos = $conexao->query("SELECT * FROM cargos ORDER BY idcargos")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CPF e telefone já vêm só com números; senha com trim(), igual ao logar.php
    $func = ler_dados_funcionario();
    $erro = validar_funcionario($func, $cargos);

    if ($erro === '') {
        $stmt = $conexao->prepare(
            "SELECT COUNT(*) FROM funcionarios
             WHERE cpf = :cpf AND estabelecimento_idestabelecimento = :estab"
        );
        $stmt->execute([':cpf' => $func['cpf'], ':estab' => $estabelecimento_id]);
        if ($stmt->fetchColumn() > 0) {
            $erro = 'Já existe um funcionário com este CPF.';
        }
    }

    if ($erro === '') {
        try {
            $stmt = $conexao->prepare(
                "INSERT INTO funcionarios
                    (nome, dt_nasc, salario, cpf, email, telefone, senha, cargos_idcargos, estabelecimento_idestabelecimento)
                 VALUES
                    (:nome, :dt_nasc, :salario, :cpf, :email, :telefone, :senha, :cargo, :estab)"
            );
            $stmt->execute([
                ':nome'     => $func['nome'],
                ':dt_nasc'  => $func['dt_nasc'],
                ':salario'  => $func['salario'],
                ':cpf'      => $func['cpf'],
                ':email'    => $func['email'],
                ':telefone' => $func['telefone'],
                ':senha'    => password_hash($func['senha'], PASSWORD_DEFAULT), // nunca guarda a senha pura
                ':cargo'    => $func['cargos_idcargos'],
                ':estab'    => $estabelecimento_id,
            ]);

            header('Location: dashboard.php?pagina=funcionarios');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao cadastrar funcionário: ' . $e->getMessage();
        }
    }
}

$acao_form   = 'dashboard.php?pagina=funcionarios_cadastrar';
$texto_botao = 'Cadastrar';
?>
<main class="fundo content">

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <h1 class="titulo">Cadastrar funcionário</h1>
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
            <input type="password" id="senha" name="senha" autocomplete="new-password" placeholder="Senha do funcionário" required>
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