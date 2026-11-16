<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciamento de Pedidos | Janine Cosméticos</title>

    <link rel="stylesheet" href="css/header_admin.css">
    <link rel="stylesheet" href="css/pedidos_adm.css">
</head>

<body>

<?php include 'includes/header_admin.php'; ?>

<main class="pedidos-container">

    <!-- titulo -->
    <section class="pedidos-intro">

        <span class="pedidos-etiqueta">VENDAS</span>

        <h1>Gerenciamento de pedidos</h1>

        <p>
            Consulte pagamentos e acompanhe o andamento das entregas.
        </p>

    </section>

    <!-- abas -->
    <section class="pedidos-abas">

        <button class="aba-pedido ativo"
                type="button"
                data-aba="Todos">
            Todos
            <span>128</span>
        </button>

        <button class="aba-pedido"
                type="button"
                data-aba="Aguard. pagamento">
            Aguardando pagamento
            <span>12</span>
        </button>

        <button class="aba-pedido"
                type="button"
                data-aba="Em separação">
            Em separação
            <span>18</span>
        </button>

        <button class="aba-pedido"
                type="button"
                data-aba="Enviado">
            Enviados
            <span>24</span>
        </button>

        <button class="aba-pedido"
                type="button"
                data-aba="Entregue">
            Entregues
            <span>71</span>
        </button>

    </section>

    <!-- filtros -->
    <section class="pedidos-filtros">

        <div class="campo-busca">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round">
                <circle cx="11" cy="11" r="7"/>
                <path d="m16 16 5 5"/>
            </svg>

            <input
                type="search"
                id="buscarPedido"
                placeholder="Buscar pedido ou cliente..."
            >

        </div>

        <input
            type="date"
            id="dataPedido"
            class="campo-data"
            aria-label="Filtrar por data"
        >

        <select id="statusPedido" class="campo-status">
            <option value="Todos">Todos os status</option>
            <option value="Aguard. pagamento">Aguardando pagamento</option>
            <option value="Em separação">Em separação</option>
            <option value="Enviado">Enviado</option>
            <option value="Entregue">Entregue</option>
            <option value="Cancelado">Cancelado</option>
        </select>

        <button class="btn-filtrar"
                id="btnFiltrar"
                type="button">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.8"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M4 5h16l-6 7v6l-4 2v-8L4 5Z"/>
            </svg>

            Filtrar
        </button>

    </section>

    <!-- tabela -->
    <section class="pedidos-tabela-card">

        <div class="pedidos-tabela-scroll">

            <table class="pedidos-tabela">

                <thead>
                    <tr>
                        <th>PEDIDO</th>
                        <th>CLIENTE</th>
                        <th>DATA</th>
                        <th>VALOR</th>
                        <th>STATUS</th>
                        <th aria-label="Ações"></th>
                    </tr>
                </thead>

                <tbody id="corpoPedidos">

                    <tr data-status="Aguard. pagamento"
                        data-data="2025-04-24">
                        <td class="pedido-id">#JC-1048</td>
                        <td>Juliana Martins</td>
                        <td>Hoje, 10:42</td>
                        <td>R$ 207,70</td>
                        <td>
                            <span class="status-badge aguardando">
                                Aguard. pagamento
                            </span>
                        </td>
                        <td>
                            <button class="pedido-acoes"
                                    type="button"
                                    aria-label="Mais opções">
                                •••
                            </button>
                        </td>
                    </tr>

                    <tr data-status="Em separação"
                        data-data="2025-04-24">
                        <td class="pedido-id">#JC-1047</td>
                        <td>Camila Souza</td>
                        <td>Hoje, 09:18</td>
                        <td>R$ 129,80</td>
                        <td>
                            <span class="status-badge separacao">
                                Em separação
                            </span>
                        </td>
                        <td>
                            <button class="pedido-acoes"
                                    type="button"
                                    aria-label="Mais opções">
                                •••
                            </button>
                        </td>
                    </tr>

                    <tr data-status="Enviado"
                        data-data="2025-04-23">
                        <td class="pedido-id">#JC-1046</td>
                        <td>Rafael Lima</td>
                        <td>Ontem, 17:06</td>
                        <td>R$ 189,90</td>
                        <td>
                            <span class="status-badge enviado">
                                Enviado
                            </span>
                        </td>
                        <td>
                            <button class="pedido-acoes"
                                    type="button"
                                    aria-label="Mais opções">
                                •••
                            </button>
                        </td>
                    </tr>

                    <tr data-status="Entregue"
                        data-data="2025-04-23">
                        <td class="pedido-id">#JC-1045</td>
                        <td>Bianca Alves</td>
                        <td>Ontem, 15:31</td>
                        <td>R$ 98,70</td>
                        <td>
                            <span class="status-badge entregue">
                                Entregue
                            </span>
                        </td>
                        <td>
                            <button class="pedido-acoes"
                                    type="button"
                                    aria-label="Mais opções">
                                •••
                            </button>
                        </td>
                    </tr>

                    <tr data-status="Cancelado"
                        data-data="2025-04-22">
                        <td class="pedido-id">#JC-1044</td>
                        <td>Larissa Prado</td>
                        <td>22 abr. 2025</td>
                        <td>R$ 75,40</td>
                        <td>
                            <span class="status-badge cancelado">
                                Cancelado
                            </span>
                        </td>
                        <td>
                            <button class="pedido-acoes"
                                    type="button"
                                    aria-label="Mais opções">
                                •••
                            </button>
                        </td>
                    </tr>

                    <tr data-status="Em separação"
                        data-data="2025-04-22">
                        <td class="pedido-id">#JC-1043</td>
                        <td>Pedro Rocha</td>
                        <td>22 abr. 2025</td>
                        <td>R$ 224,70</td>
                        <td>
                            <span class="status-badge separacao">
                                Em separação
                            </span>
                        </td>
                        <td>
                            <button class="pedido-acoes"
                                    type="button"
                                    aria-label="Mais opções">
                                •••
                            </button>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

        <!-- paginação -->
        <div class="pedidos-paginacao">

            <p id="textoPaginacao">
                Mostrando 1–6 de 6 pedidos demonstrativos
            </p>

            <div class="paginacao-botoes">

                <button type="button"
                        id="paginaAnterior"
                        aria-label="Página anterior">
                    ‹
                </button>

                <div id="numerosPaginas"
                     class="numeros-paginas"></div>

                <button type="button"
                        id="proximaPagina"
                        aria-label="Próxima página">
                    ›
                </button>

            </div>

        </div>

    </section>

    <!-- aviso -->
    <section class="pedidos-aviso">

        <svg viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="1.8"
             stroke-linecap="round"
             stroke-linejoin="round">
            <rect x="3" y="5" width="18" height="14" rx="2"/>
            <path d="M3 10h18"/>
        </svg>

        <div>
            <strong>
                Status de pagamento e entrega são tratados separadamente.
            </strong>

            <p>
                Um pedido pode estar com pagamento confirmado
                e ainda aguardando separação.
            </p>
        </div>

    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const linhas = Array.from(
        document.querySelectorAll('#corpoPedidos tr')
    );

    const abas = document.querySelectorAll('.aba-pedido');
    const busca = document.getElementById('buscarPedido');
    const data = document.getElementById('dataPedido');
    const status = document.getElementById('statusPedido');
    const filtrar = document.getElementById('btnFiltrar');

    const textoPaginacao = document.getElementById('textoPaginacao');
    const numerosPaginas = document.getElementById('numerosPaginas');
    const anterior = document.getElementById('paginaAnterior');
    const proxima = document.getElementById('proximaPagina');

    let abaAtual = 'Todos';
    let paginaAtual = 1;
    const itensPorPagina = 6;

    function normalizar(texto) {
        return texto.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function atualizarTabela() {

        const termo = normalizar(busca.value.trim());
        const dataSelecionada = data.value;
        const statusSelecionado = status.value;

        const filtradas = linhas.filter(linha => {

            const texto = normalizar(linha.textContent);
            const statusLinha = linha.dataset.status;
            const dataLinha = linha.dataset.data;

            const correspondeBusca = texto.includes(termo);

            const correspondeAba =
                abaAtual === 'Todos' ||
                statusLinha === abaAtual;

            const correspondeStatus =
                statusSelecionado === 'Todos' ||
                statusLinha === statusSelecionado;

            const correspondeData =
                !dataSelecionada ||
                dataLinha === dataSelecionada;

            return correspondeBusca &&
                   correspondeAba &&
                   correspondeStatus &&
                   correspondeData;
        });

        const total = filtradas.length;
        const totalPaginas = Math.max(
            1,
            Math.ceil(total / itensPorPagina)
        );

        paginaAtual = Math.min(paginaAtual, totalPaginas);

        linhas.forEach(linha => {
            linha.style.display = 'none';
        });

        const inicio = (paginaAtual - 1) * itensPorPagina;
        const fim = inicio + itensPorPagina;

        filtradas.slice(inicio, fim).forEach(linha => {
            linha.style.display = '';
        });

        if (total === 0) {
            textoPaginacao.textContent =
                'Nenhum pedido encontrado';
        } else {
            textoPaginacao.textContent =
                `Mostrando ${inicio + 1}–${Math.min(fim, total)} de ${total} pedidos demonstrativos`;
        }

        numerosPaginas.innerHTML = '';

        for (let i = 1; i <= totalPaginas; i++) {

            const botao = document.createElement('button');
            botao.type = 'button';
            botao.textContent = i;

            if (i === paginaAtual) {
                botao.classList.add('ativo');
            }

            botao.addEventListener('click', function () {
                paginaAtual = i;
                atualizarTabela();
            });

            numerosPaginas.appendChild(botao);
        }

        anterior.disabled = paginaAtual === 1;
        proxima.disabled = paginaAtual === totalPaginas;
    }

    abas.forEach(aba => {
        aba.addEventListener('click', function () {

            abas.forEach(item => {
                item.classList.remove('ativo');
            });

            this.classList.add('ativo');
            abaAtual = this.dataset.aba;
            paginaAtual = 1;

            atualizarTabela();
        });
    });

    busca.addEventListener('input', function () {
        paginaAtual = 1;
        atualizarTabela();
    });

    filtrar.addEventListener('click', function () {
        paginaAtual = 1;
        atualizarTabela();
    });

    anterior.addEventListener('click', function () {
        if (paginaAtual > 1) {
            paginaAtual--;
            atualizarTabela();
        }
    });

    proxima.addEventListener('click', function () {
        paginaAtual++;
        atualizarTabela();
    });

    atualizarTabela();
});
</script>

</body>
</html>
