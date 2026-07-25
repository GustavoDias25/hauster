<?php

include "side-bar.php";

?>
<link rel="stylesheet" href="css/principal.css">
<main class="content">
    <div class="cards">
        <div class="card">
            <h1>Receita do último mês</h1>
            <p>R$ 12.549,37</p>
            <p>10% acima dos últimos meses</p>
        </div>
        <div class="card">
            <h1>Clientes cadastrados</h1>
            <p>125 Clientes</p>
            <p>10 clientes cadastrados esse mês</p>
        </div>
        <div class="card">
            <h1>Serviços realizados</h1>
            <p>23 realizados</p>
            <p>10 a mais em relação ao último mês</p>
        </div>
    </div>
</main>

<main class="tabela fundo content">
    <h1 class="titulo">Produtos mais vendidos</h1>
    <table class="table">
        <tr class="linha">
            <th class="celulas">ID</th>
            <th class="celulas">Nome</th>
            <th class="celulas">Marca</th>
            <th class="celulas">Categoria</th>
            <th class="celulas">Preço</th>
            <th class="celulas">Validade</th>
            <th class="celulas">Quantidade</th>
            <th class="celulas" id="th_acoes">Ações</th>
        </tr>
        <tr class="linha">
        <td class="celulas">1</td>
        <td class="celulas">Ração Seca Suprema</td>
        <td class="celulas">Golden</td>
        <td class="celulas">Ração</td>
        <td class="celulas">R$50,00</td>
        <td class="celulas">25/05/2027</td>
        <td class="estoque"><button>Alto</button></td>
        <td class="celulas">
                <div class="acoes">
                    <button id="editar">Editar</button>
                    <button id="excluir">Excluir</button>
                </div>
            </td>
        </tr>
        <tr class="linha">
        <td class="celulas">1</td>
        <td class="celulas">Ração Seca Suprema</td>
        <td class="celulas">Golden</td>
        <td class="celulas">Ração</td>
        <td class="celulas">R$50,00</td>
        <td class="celulas">25/05/2027</td>
        <td class="estoque"><button>Alto</button></td>
        <td class="celulas">
                <div class="acoes">
                    <button id="editar">Editar</button>
                    <button id="excluir">Excluir</button>
                </div>
            </td>
        </tr>
       <tr class="linha">
        <td class="celulas">1</td>
        <td class="celulas">Ração Seca Suprema</td>
        <td class="celulas">Golden</td>
        <td class="celulas">Ração</td>
        <td class="celulas">R$50,00</td>
        <td class="celulas">25/05/2027</td>
        <td class="estoque"><button>Alto</button></td>
        <td class="celulas">
                <div class="acoes">
                    <button id="editar">Editar</button>
                    <button id="excluir">Excluir</button>
                </div>
            </td>
        </tr>
        <tr class="linha">
        <td class="celulas">1</td>
        <td class="celulas">Ração Seca Suprema</td>
        <td class="celulas">Golden</td>
        <td class="celulas">Ração</td>
        <td class="celulas">R$50,00</td>
        <td class="celulas">25/05/2027</td>
        <td class="estoque"><button>Alto</button></td>
        <td class="celulas">
                <div class="acoes">
                    <button id="editar">Editar</button>
                    <button id="excluir">Excluir</button>
                </div>
            </td>
        </tr>
    </table>
</main>
