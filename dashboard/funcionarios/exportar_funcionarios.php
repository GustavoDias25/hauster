<?php
/**
 * exportar_funcionarios.php
 * Gera um Excel (.xlsx) com o relatório de vendas por funcionário do estabelecimento logado:
 * nome do funcionário, quantidade de vendas e total arrecadado.
 *
 * Fica em dashboard/funcionarios/ (o Composer fica em includes/vendor/).
 * É chamado DIRETO pelo botão (não passa pelo dashboard.php), pelo mesmo motivo
 * do exportar_pedidos.php: qualquer HTML antes do download corrompe o .xlsx.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Este arquivo está em hauster/dashboard/funcionarios/, por isso o ../../ para chegar em includes/
require __DIR__ . '/../../includes/conexao.php';
require __DIR__ . '/../../includes/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// ---------------------------------------------------------------
// 1) SEGURANÇA: só exporta se estiver logado, e só do próprio estabelecimento
// ---------------------------------------------------------------
if (empty($_SESSION['logado']) || empty($_SESSION['estabelecimento_id'])) {
    header('Location: ../../login_cadastro/login.php');
    exit;
}
$estabelecimento_id = $_SESSION['estabelecimento_id'];

// Só quem tem acesso a Funcionários pode gerar o relatório
require_once __DIR__ . '/../../includes/permissoes.php';
if (!pode_acessar('funcionarios')) {
    http_response_code(403);
    exit('Sem permissão para gerar este relatório.');
}

// ---------------------------------------------------------------
// 2) QUERY: LEFT JOIN para o funcionário sem vendas aparecer com 0
// ---------------------------------------------------------------
$sql = 'SELECT
            f.nome                      AS "Funcionário",
            COUNT(ip.id_pedido)         AS "Quantidade de vendas",
            COALESCE(SUM(ip.valor), 0)  AS "Total arrecadado"
        FROM funcionarios f
        LEFT JOIN itens_pedido ip
               ON ip.funcionarios_idfuncionarios = f.idfuncionarios
              AND ip.estabelecimento_idestabelecimento = f.estabelecimento_idestabelecimento
        WHERE f.estabelecimento_idestabelecimento = :estabelecimento_id
        GROUP BY f.idfuncionarios, f.nome
        ORDER BY COALESCE(SUM(ip.valor), 0) DESC, f.nome';

$stmt = $conexao->prepare($sql);
$stmt->execute([':estabelecimento_id' => $estabelecimento_id]);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC); // array associativo (precisamos dos nomes das colunas)

if (empty($dados)) {
    $_SESSION['erro'] = 'Não há funcionários para gerar o relatório.';
    header('Location: ../../dashboard.php?pagina=funcionarios');
    exit;
}

// ---------------------------------------------------------------
// 3) PLANILHA: cabeçalho
// ---------------------------------------------------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Vendas por funcionário');

$sheet->fromArray(array_keys($dados[0]), null, 'A1');
$ultimaColuna = $sheet->getHighestColumn(); // 'C'

$sheet->getStyle("A1:{$ultimaColuna}1")->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2F5597']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
]);

// ---------------------------------------------------------------
// 4) PLANILHA: dados
// ---------------------------------------------------------------
$linhas = [];
foreach ($dados as $row) {
    $row['Quantidade de vendas'] = (int) $row['Quantidade de vendas'];
    $row['Total arrecadado']     = (float) $row['Total arrecadado'];
    $linhas[] = array_values($row);
}
$sheet->fromArray($linhas, null, 'A2');

$ultimaLinha = count($linhas) + 1;
$linhaTotal  = $ultimaLinha + 1;

// ---------------------------------------------------------------
// 5) Formatação e totais   (A Funcionário | B Quantidade de vendas | C Total arrecadado)
// ---------------------------------------------------------------
$sheet->getStyle("C2:C{$linhaTotal}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
$sheet->getStyle("B2:B{$linhaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue("A{$linhaTotal}", 'TOTAL');
$sheet->setCellValue("B{$linhaTotal}", "=SUM(B2:B{$ultimaLinha})");
$sheet->setCellValue("C{$linhaTotal}", "=SUM(C2:C{$ultimaLinha})");
$sheet->getStyle("A{$linhaTotal}:{$ultimaColuna}{$linhaTotal}")->getFont()->setBold(true);

foreach (range('A', $ultimaColuna) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->freezePane('A2');
$sheet->setAutoFilter("A1:{$ultimaColuna}{$ultimaLinha}");

// ---------------------------------------------------------------
// 6) Download
// ---------------------------------------------------------------
$nomeArquivo = 'funcionarios_' . date('Y-m-d_His') . '.xlsx';

if (ob_get_length()) {
    ob_end_clean();
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
header('Cache-Control: max-age=0');

(new Xlsx($spreadsheet))->save('php://output');
exit;