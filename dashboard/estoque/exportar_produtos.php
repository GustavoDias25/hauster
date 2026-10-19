<?php
/**
 * exportar_produtos.php
 * Gera um Excel (.xlsx) com os produtos do estabelecimento logado.
 *
 * Fica em dashboard/estoque/. Não pode ser incluído dentro do dashboard.php,
 * porque qualquer HTML impresso antes do download corrompe o .xlsx.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------
// 1) CONEXÃO + AUTOLOAD
// ---------------------------------------------------------------
require __DIR__ . '/../../includes/conexao.php'; // deve definir $conexao (PDO)
require __DIR__ . '/vendor/autoload.php';

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

// ---------------------------------------------------------------
// 3) QUERY  (ordem das colunas = ordem na planilha)
//    A Nome | B Marca | C Categoria | D Preço | E Estoque | F Validade
// ---------------------------------------------------------------
$sql = 'SELECT p.nome_prod AS "Nome do produto", p.validade AS Validade, p.marca AS Marca, p.preco AS "Preço", p.estoque AS "Estoque", c.nome
FROM produtos p, categorias c
WHERE estabelecimento_idestabelecimento = :estabelecimento_id AND p.categorias_idcategorias = c.idcategorias ORDER BY nome_prod DESC;';

$stmt = $conexao->prepare($sql);
$stmt->execute([':estabelecimento_id' => $estabelecimento_id]);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC); // precisamos dos nomes das colunas

if (empty($dados)) {
    $_SESSION['erro'] = 'Não há produtos para exportar.';
    header('Location: ../../dashboard.php?pagina=produtos');
    exit;
}

// ---------------------------------------------------------------
// 4) PLANILHA: cabeçalho
// ---------------------------------------------------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Produtos');

$sheet->fromArray(array_keys($dados[0]), null, 'A1');
$ultimaColuna = $sheet->getHighestColumn(); // 'F'

$sheet->getStyle("A1:{$ultimaColuna}1")->applyFromArray([
    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2F5597']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
]);

// ---------------------------------------------------------------
// 5) PLANILHA: dados (data em DD/MM/AAAA)
// ---------------------------------------------------------------
$linhas = [];
foreach ($dados as $row) {
    $row['Validade'] = date('d/m/Y', strtotime($row['Validade']));
    $linhas[] = array_values($row);
}
$sheet->fromArray($linhas, null, 'A2');

$ultimaLinha = count($linhas) + 1;
$linhaTotal  = $ultimaLinha + 1;

// ---------------------------------------------------------------
// 6) Formatação e totais
// ---------------------------------------------------------------
// D = Preço (R$)
$sheet->getStyle("D2:D{$linhaTotal}")->getNumberFormat()->setFormatCode('"R$" #,##0.00');
// E = Estoque, F = Validade (centralizados)
$sheet->getStyle("E2:F{$linhaTotal}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue("A{$linhaTotal}", 'TOTAL');
$sheet->setCellValue("D{$linhaTotal}", "=SUM(D2:D{$ultimaLinha})");
$sheet->setCellValue("E{$linhaTotal}", "=SUM(E2:E{$ultimaLinha})");
// Se preferir o valor total em estoque (preço x quantidade), troque a linha do D por:
// $sheet->setCellValue("D{$linhaTotal}", "=SUMPRODUCT(D2:D{$ultimaLinha},E2:E{$ultimaLinha})");

$sheet->getStyle("A{$linhaTotal}:{$ultimaColuna}{$linhaTotal}")->getFont()->setBold(true);

foreach (range('A', $ultimaColuna) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->freezePane('A2');
$sheet->setAutoFilter("A1:{$ultimaColuna}{$ultimaLinha}");

// ---------------------------------------------------------------
// 7) Download
// ---------------------------------------------------------------
$nomeArquivo = 'Estoque_' . date('Y-m-d') . '.xlsx';

while (ob_get_level() > 0) {
    ob_end_clean();
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');
header('Cache-Control: max-age=0');

(new Xlsx($spreadsheet))->save('php://output');
exit;