<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatórios de Vendas | Janine Cosméticos</title>

    <link rel="stylesheet" href="css/header_admin.css">
    <link rel="stylesheet" href="css/relatorios_adm.css">
</head>
<body>

    <?php include 'includes/header_admin.php'; ?>

    <main class="relatorios-container">

        <section class="relatorios-intro">
            <h1>Relatórios de vendas</h1>
            <p>Analise o desempenho do negócio com dados demonstrativos.</p>
        </section>

        <section class="relatorios-filtros">
            <div class="filtros-esquerda">
                <button class="btn-periodo" type="button">
                    01 abr. 2025 — 24 abr. 2025
                </button>

                <button class="btn-comparar" type="button">
                    Comparar período
                </button>
            </div>

            <button class="btn-exportar" type="button">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v12"/>
                    <path d="m7 10 5 5 5-5"/>
                    <path d="M4 20h16"/>
                </svg>
                Exportar relatório demo
            </button>
        </section>

        <section class="relatorios-indicadores">

            <article class="indicador-card">
                <p>Vendas no período</p>
                <h2>R$ 18.420,50</h2>
                <span><strong>+12,4%</strong> vs. período anterior</span>
            </article>

            <article class="indicador-card">
                <p>Pedidos confirmados</p>
                <h2>128</h2>
                <span><strong>+8,2%</strong> vs. período anterior</span>
            </article>

            <article class="indicador-card">
                <p>Ticket médio</p>
                <h2>R$ 143,91</h2>
                <span><strong>+3,1%</strong> vs. período anterior</span>
            </article>

            <article class="indicador-card">
                <p>Itens vendidos</p>
                <h2>284</h2>
                <span><strong>+14,8%</strong> vs. período anterior</span>
            </article>

        </section>

        <section class="relatorios-graficos">

            <article class="grafico-card grafico-vendas">

                <div class="grafico-cabecalho">
                    <div>
                        <h3>Vendas por dia</h3>
                        <p>Valores confirmados em reais</p>
                    </div>

                    <div class="grafico-legenda">
                        <span class="legenda-ponto"></span>
                        Vendas
                    </div>
                </div>

                <div class="grafico-conteudo">
                    <div class="barras-container">

                        <div class="barra-item">
                            <div class="barra" style="--altura: 45%"></div>
                            <span>1 abr</span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 72%"></div>
                            <span></span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 58%"></div>
                            <span>3 abr</span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 87%"></div>
                            <span></span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 66%"></div>
                            <span>5 abr</span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 97%"></div>
                            <span></span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 81%"></div>
                            <span>7 abr</span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 98%"></div>
                            <span></span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 74%"></div>
                            <span>9 abr</span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 93%"></div>
                            <span></span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 98%"></div>
                            <span>11 abr</span>
                        </div>

                        <div class="barra-item">
                            <div class="barra" style="--altura: 83%"></div>
                            <span></span>
                        </div>

                    </div>
                </div>

            </article>

            <article class="grafico-card grafico-categorias">

                <div class="grafico-cabecalho">
                    <div>
                        <h3>Vendas por categoria</h3>
                        <p>Participação no faturamento</p>
                    </div>
                </div>

                <div class="categoria-conteudo">

                    <div class="grafico-rosca">
                        <div class="rosca-centro">
                            <strong>R$ 18,4 mil</strong>
                            <span>Total</span>
                        </div>
                    </div>

                    <div class="categorias-legenda">

                        <div class="categoria-item">
                            <span class="categoria-nome">
                                <i class="categoria-cor cabelos"></i>
                                Cabelos
                            </span>
                            <strong>34%</strong>
                        </div>

                        <div class="categoria-item">
                            <span class="categoria-nome">
                                <i class="categoria-cor perfumes"></i>
                                Perfumes
                            </span>
                            <strong>28%</strong>
                        </div>

                        <div class="categoria-item">
                            <span class="categoria-nome">
                                <i class="categoria-cor maquiagem"></i>
                                Maquiagem
                            </span>
                            <strong>22%</strong>
                        </div>

                        <div class="categoria-item">
                            <span class="categoria-nome">
                                <i class="categoria-cor moda"></i>
                                Moda
                            </span>
                            <strong>16%</strong>
                        </div>

                    </div>
                </div>

            </article>

        </section>

    </main>

</body>
</html>
