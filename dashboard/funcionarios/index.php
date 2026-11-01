<?php
include_once "includes/helpers_funcionarios.php";

$estabelecimento_id = $_SESSION['estabelecimento_id'];

$stmt = $conexao->prepare(
    "SELECT f.*, c.nome AS nome_cargo
     FROM funcionarios f
     JOIN cargos c ON c.idcargos = f.cargos_idcargos
     WHERE f.estabelecimento_idestabelecimento = :estabelecimento_id
     ORDER BY f.idfuncionarios"
);
$stmt->execute([':estabelecimento_id' => $estabelecimento_id]);
$funcionarios = $stmt->fetchAll();
?>
<main class="tabela content fundo">
    <?php if (!empty($_SESSION['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_SESSION['erro']) ?></p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>
    <div class="cabecalho-tabela">
        <h1 class="titulo">Lista de funcionários</h1>
        <div style="display:flex; gap:10px;">
            <a href="dashboard/funcionarios/exportar_funcionarios.php" class="btn-cadastrar">Gerar Relatório</a>
            <a href="dashboard.php?pagina=funcionarios_cadastrar" class="btn-cadastrar">Cadastrar Funcionário</a>
        </div>
    </div>

    <table class="table-sb">
        <tr class="linha">
            <th class="celulas">ID</th>
            <th class="celulas">Nome</th>
            <th class="celulas">Cargo</th>
            <th class="celulas">Telefone</th>
            <th class="celulas">Salário</th>
            <th class="celulas">Ações</th>
        </tr>

        <?php if (count($funcionarios) > 0): ?>
            <?php foreach ($funcionarios as $func): ?>
                <tr class="linha">
                    <td class="celulas"><?= htmlspecialchars($func['idfuncionarios']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($func['nome']) ?></td>
                    <td class="celulas"><?= htmlspecialchars($func['nome_cargo']) ?></td>
                    <td class="celulas"><?= htmlspecialchars(formatar_telefone($func['telefone'])) ?></td>
                    <td class="celulas destaque"><?= formatar_dinheiro($func['salario']) ?></td>
                    <td class="celulas">
                        <div class="acoes">
                            <!-- Diferente das outras telas: aqui também tem o "detalhar" -->
                            <a href="dashboard.php?pagina=funcionarios_detalhar&id_func=<?= $func['idfuncionarios'] ?>" title="Detalhar">
                                <span class="material-symbols-outlined icon detalhar">visibility</span>
                            </a>
                            <a href="dashboard.php?pagina=funcionarios_editar&id_func=<?= $func['idfuncionarios'] ?>" title="Editar">
                                <span class="material-symbols-outlined icon editar">edit_square</span>
                            </a>
                            <a href="dashboard.php?pagina=funcionarios_excluir&id_func=<?= $func['idfuncionarios'] ?>" title="Excluir"
                               onclick="return confirm('Tem certeza que deseja excluir este funcionário? As vendas dele continuam registradas.');">
                                <span class="material-symbols-outlined icon excluir">delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="linha">
                <td class="celulas" colspan="6">Nenhum funcionário cadastrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</main>