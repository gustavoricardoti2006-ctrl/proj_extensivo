<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estoque</title>

    <link rel="stylesheet" href="estoque_adm.css">

    <!-- BIBLIOTECA DE ÍCONES -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- MENU LATERAL -->

    <nav class="navbar">

        <div class="logo">J</div>

        <div class="menu">

            <a href="#" title="Dashboard">
                <i data-lucide="layout-grid"></i>
            </a>

            <a href="#" title="Produtos">
                <i data-lucide="box"></i>
            </a>

            <a href="#" class="selecionado" title="Estoque">
                <i data-lucide="chart-column"></i>
            </a>

            <a href="#" title="Pedidos">
                <i data-lucide="receipt-text"></i>
            </a>

            <a href="#" title="Relatórios">
                <i data-lucide="chart-no-axes-column"></i>
            </a>

        </div>

        <a href="#" class="sair" title="Sair">
            <i data-lucide="log-out"></i>
        </a>

    </nav>


    <!-- CONTEÚDO PRINCIPAL -->

    <main class="conteudo">

        <!-- USUÁRIO -->

        <div class="usuario">
            <span class="nome-usuario">Janine Silva</span>
            <div class="foto-usuario">JS</div>
        </div>


        <!-- CABEÇALHO -->

        <div class="cabecalho">

            <div>
                <h2>OPERAÇÃO</h2>
                <h1>Controle de estoque</h1>
                <p>Acompanhe níveis, movimentações e solicitações especiais.</p>
            </div>

            <a href="#" class="btn-adicionar">
                <i data-lucide="plus"></i>
                Registrar movimentação
            </a>

        </div>


        <!-- RESUMO DO ESTOQUE -->

        <div class="resumo">

            <div class="card">

                <div>
                    <p>Unidades disponíveis</p>
                    <h2>842</h2>
                </div>

                <span class="icone">
                    <i data-lucide="package"></i>
                </span>

            </div>

            <div class="card">

                <div>
                    <p>Estoque baixo</p>
                    <h2>7</h2>
                </div>

                <span class="icone alerta">
                    <i data-lucide="triangle-alert"></i>
                </span>

            </div>

            <div class="card">

                <div>
                    <p>Produtos esgotados</p>
                    <h2>2</h2>
                </div>

                <span class="icone alerta">
                    <i data-lucide="minus"></i>
                </span>

            </div>

            <div class="card">

                <div>
                    <p>Aguardando confirmação</p>
                    <h2>3</h2>
                </div>

                <span class="icone alerta">
                    <i data-lucide="clipboard-list"></i>
                </span>

            </div>

        </div>


        <!-- SOLICITAÇÕES ACIMA DO ESTOQUE -->

        <div class="solicitacoes">

            <div class="titulo-secao">

                <div>
                    <h3>Solicitações acima do estoque</h3>
                    <p>Pedidos que precisam de análise manual</p>
                </div>

                <span class="pendentes">3 pendentes</span>

            </div>


            <!-- PEDIDO PENDENTE -->

            <div class="pedido">

                <div class="produto">

                    <div class="imagem-produto">
                        <img
                            src="https://images.unsplash.com/photo-1622618991746-fe6004db3a47?crop=entropy&amp;cs=tinysrgb&amp;fit=crop&amp;fm=jpg&amp;q=80&amp;w=700"
                            alt="Flor de Âmbar - 50 ml"
                        >
                    </div>

                    <div>
                        <h4>Flor de Âmbar - 50 ml</h4>
                        <p>Pedido #JC-1041 · Marina Costa</p>
                    </div>

                </div>

                <div class="quantidade">
                    <p>Solicitado</p>
                    <strong>6 un.</strong>
                </div>

                <div class="quantidade">
                    <p>Disponível</p>
                    <strong class="quantidade-baixa">3 un.</strong>
                </div>

                <div class="status-pedido">
                    <p>Status</p>
                    <span class="status">Aguardando análise</span>
                </div>

                <div class="acoes">

                    <a href="#" class="btn-prazo">
                        Informar prazo
                    </a>

                    <a href="#" class="btn-confirmar">
                        Confirmar
                    </a>

                </div>

            </div>

        </div>


        <!-- HISTÓRICO DE MOVIMENTAÇÕES -->

        <div class="historico">

            <div class="titulo-secao">

                <div>
                    <h3>Histórico de movimentações</h3>
                    <p>Entradas, saídas e ajustes recentes</p>
                </div>

                <a href="#" class="btn-exportar">
                    <i data-lucide="download"></i>
                    Exportar demo
                </a>

            </div>


            <!-- TABELA -->

            <div class="tabela-container">

                <table>

                    <thead>
                        <tr>
                            <th>Data e hora</th>
                            <th>Produto / Variação</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Responsável</th>
                            <th>Observação</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>24/04 · 10:32</td>
                            <td>Máscara Curvas · 300 g</td>
                            <td>Saída por venda</td>
                            <td class="saida">− 2</td>
                            <td>Sistema</td>
                            <td>Pedido #JC-1047</td>
                        </tr>

                        <tr>
                            <td>24/04 · 09:14</td>
                            <td>Lumina C · 30 ml</td>
                            <td>Entrada manual</td>
                            <td class="entrada">+ 12</td>
                            <td>Ana Martins</td>
                            <td>Reposição</td>
                        </tr>

                        <tr>
                            <td>23/04 · 17:48</td>
                            <td>Flor de Âmbar · 50 ml</td>
                            <td>Ajuste</td>
                            <td class="saida">− 1</td>
                            <td>Ana Martins</td>
                            <td>Avaria</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </main>


    <!-- CARREGAMENTO DOS ÍCONES -->

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
