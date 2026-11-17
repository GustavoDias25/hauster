<?php
/**
 * exportar_pedidos.php
 * Gera um Excel (.xlsx) com os pedidos do estabelecimento logado.
 *
 * Fica em dashboard/vendas/ (o Composer fica em includes/vendor/).
 * É chamado DIRETO pelo botão (não passa pelo dashboard.php).
 *
 * Por que um arquivo separado e não dentro de dashboard.php?pagina=...:
 * o dashboard imprime HTML (menu, cabeçalho) antes de incluir a página, e
 * qualquer HTML antes do download corrompe o .xlsx.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexão do sistema (cria a variável $conexao) e autoload do Composer
// Este arquivo está em hauster/dashboard/vendas/, por isso o ../../ para chegar em includes/
require __DIR__ . '/../../includes/conexao.php';
require __DIR__ . '/../../includes/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// ---------------------------------------------------------------
// 2) SEGURANÇA: só exporta se estiver logado, e só do próprio estabelecimento
// ---------------------------------------------------------------
if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {
    header('Location: ../../login_cadastro/login.php');
    exit;
}
$estabelecimento_id = $_SESSION['estabelecimento_id'];

// Só quem tem acesso a Vendas pode gerar o relatório
require_once __DIR__ . '/../../includes/permissoes.php';
if (!pode_acessar('pedidos')) {
    http_response_code(403);
    exit('Sem permissão para gerar este relatório.');
}

// ---------------------------------------------------------------
// 3) QUERY
// ---------------------------------------------------------------
$sql = 'SELECT
            p.nome_prod         AS "Produtos",
            ip.dt_pedido        AS "Data do pedido",
            ip.valor            AS "Valor total do pedido",
            ip.quantidade       AS "Quantidade de produtos pedidos",
            ip.form_pagamento   AS "Forma de pagamento",
            COALESCE(f.nome, \'-\') AS "Funcionário"
        FROM itens_pedido ip
        JOIN produtos p ON p.idprodutos = ip.produtos_idprodutos
        LEFT JOIN funcionarios f ON f.idfuncionarios = ip.funcionarios_idfuncionarios
        WHERE ip.estabelecimento_idestabelecimento = :estabelecimento_id
        ORDER BY ip.quantidade DESC';

$stmt = $conexao->prepare($sql);
$stmt->execute([':estabelecimento_id' => $estabelecimento_id]);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC); // força array associativo (precisamos dos nomes das colunas)

if (empty($dados)) {
    $_SESSION['erro'] = 'Não há pedidos para exportar.';
    header('Location: ../../dashboard.php?pagina=pedidos');
    exit;
}

// ---------------------------------------------------------------
// 4) PLANILHA: cabeçalho
// ---------------------------------------------------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Pedidos');

$sheet->fromArray(array_keys($dados[0]), null, 'A1');
$ultimaColuna = $sheet->getHighestColumn(); // 'F'

$sheet->getStyle("A1:{$ultimaColuna}1")->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2F5597']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
]);

// ---------------------------------------------------------------
// 5) PLANILHA: dados (data em DD/MM/AAAA)
// ---------------------------------------------------------------
$linhas = [];
foreach ($dados as $row) {
    $row['Data do pedido'] = date('d/m/Y', strtotime($row['Data do pedido']));
    $linhas[] = array_values($row);
}
$sheet->fromArray($linhas, null, 'A2');

$ultimaLinha = count($linhas) + 1;
$linhaTotal  = $ultimaLinha + 1;

// ---------------------------------------------------------------
// 6) Formatação e totais   (A Produto | B Data | C Valor | D Qtd | E Pagamento | F Funcionário)
// ---------------------------------------------------------------
$sheet->getStyle("C2:C{$linhaTotal}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
$sheet->getStyle("B2:B{$ultimaLinha}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("D2:D{$linhaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue("A{$linhaTotal}", 'TOTAL');
$sheet->setCellValue("C{$linhaTotal}", "=SUM(C2:C{$ultimaLinha})");
$sheet->setCellValue("D{$linhaTotal}", "=SUM(D2:D{$ultimaLinha})");
$sheet->getStyle("A{$linhaTotal}:{$ultimaColuna}{$linhaTotal}")->getFont()->setBold(true);

foreach (range('A', $ultimaColuna) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->freezePane('A2');
$sheet->setAutoFilter("A1:{$ultimaColuna}{$ultimaLinha}");

// ---------------------------------------------------------------
// 7) Download
// ---------------------------------------------------------------
$nomeArquivo = 'pedidos_' . date('Y-m-d_His') . '.xlsx';

if (ob_get_length()) {
    ob_end_clean();
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
header('Cache-Control: max-age=0');

(new Xlsx($spreadsheet))->save('php://output');
exit;