
<?php
include("includes/conexao.php");
include("includes/funcoes.php");
include("includes/session.php");

// ========================================
// CONFIGURAÇÃO DA PAGINAÇÃO
// ========================================

$limite = 9;
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

if ($pagina < 1) {
    $pagina = 1;
}

// ========================================
// FILTROS RECEBIDOS
// ========================================

$categoria_selecionada = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;
$marca_selecionada = $_GET['marca'] ?? '';
$preco_min = isset($_GET['preco_min']) ? (float) $_GET['preco_min'] : 0;
$preco_max = isset($_GET['preco_max']) ? (float) $_GET['preco_max'] : 0;

$ordenacao = $_GET['ordenacao'] ?? 'recentes';

// ========================================
// CONSULTA DAS CATEGORIAS
// ========================================

$sql_categorias = "SELECT
    c.id_categorias,
    c.nome_cat,
    COUNT(p.id_produto) AS total_produtos
FROM categorias c
LEFT JOIN produto p ON p.id_categoria = c.id_categorias
GROUP BY c.id_categorias, c.nome_cat
HAVING total_produtos >= 1
ORDER BY c.nome_cat";

$resultado_categorias = consultar($con, $sql_categorias);

// ========================================
// CONSULTA DAS MARCAS
// ========================================

$sql_marcas = "SELECT DISTINCT marca
FROM produto
WHERE marca IS NOT NULL AND marca <> ''
ORDER BY marca";

$resultado_marcas = consultar($con, $sql_marcas);

// ========================================
// CONSTRUIR OS FILTROS SQL
// ========================================

$filtros = [];

if ($categoria_selecionada > 0) {
    $filtros[] = "p.id_categoria = $categoria_selecionada";
}

if ($marca_selecionada != '') {
    $marca_segura = $con->real_escape_string($marca_selecionada);
    $filtros[] = "p.marca = '$marca_segura'";
}

if ($preco_min > 0) {
    $filtros[] = "COALESCE(NULLIF(p.preco_promocional, 0), p.preco) >= $preco_min";
}

if ($preco_max > 0) {
    $filtros[] = "COALESCE(NULLIF(p.preco_promocional, 0), p.preco) <= $preco_max";
}

$where = '';

if (!empty($filtros)) {
    $where = " WHERE " . implode(" AND ", $filtros);
}

// ========================================
// ORDENAÇÃO
// ========================================

switch ($ordenacao) {
    case 'menor_preco':
        $order = "COALESCE(NULLIF(p.preco_promocional, 0), p.preco) ASC";
        break;

    case 'maior_preco':
        $order = "COALESCE(NULLIF(p.preco_promocional, 0), p.preco) DESC";
        break;

    case 'nome':
        $order = "p.nome_prod ASC";
        break;

    default:
        $order = "p.id_produto DESC";
        break;
}

// ========================================
// TOTAL DE PRODUTOS
// ========================================

$sql_total = "SELECT COUNT(*) AS total
FROM produto p
$where";

$resultado_total = consultar($con, $sql_total);
$dados_total = $resultado_total->fetch_assoc();

$total_produtos = $dados_total['total'];

$total_paginas = ceil($total_produtos / $limite);

if ($total_paginas > 0 && $pagina > $total_paginas) {
    $pagina = $total_paginas;
}

$offset = ($pagina - 1) * $limite;

// ========================================
// CONSULTA DOS PRODUTOS
// ========================================

$sql_produtos = "SELECT
    p.id_produto,
    p.nome_prod,
    p.imagem_produto,
    p.marca,
    p.preco,
    p.preco_promocional
FROM produto p
$where
ORDER BY $order
LIMIT $limite OFFSET $offset";

$resultado_produtos = consultar($con, $sql_produtos);

// ========================================
// FUNÇÃO PARA MONTAR LINKS DA PAGINAÇÃO
// ========================================

function linkPagina($numero) {
    $parametros = $_GET;
    $parametros['pagina'] = $numero;

    return '?' . http_build_query($parametros);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Todos os produtos - Janine Cosméticos</title>
    
    <link rel="stylesheet" href="css/header_cliente.css">
    <link rel="stylesheet" href="css/filtro_produtos.css">
    <link rel="stylesheet" href="css/footer_cliente.css">

</head>

<body>

<?php include("includes/header_cliente.php"); ?>

<main class="pagina-produtos">

    <!-- ========================================
         CABEÇALHO
    ========================================= -->

    <section class="titulo-pagina">

        <div>
            <span class="subtitulo">DESCUBRA SEU NOVO FAVORITO</span>

            <h1>Todos os produtos</h1>

            <p>Beleza e autocuidado reunidos em uma curadoria especial.</p>
        </div>

        <div class="contador">

            <strong><?php echo $total_produtos; ?></strong>

            <span>produtos encontrados</span>

        </div>

    </section>


    <!-- ========================================
         CONTEÚDO DA LOJA
    ========================================= -->

    <div class="conteudo-loja">

        <!-- ========================================
             BARRA LATERAL
        ========================================= -->

        <aside class="sidebar">

            <div class="filtro-topo">

                <h3>Filtrar por</h3>

                <a href="filtro_produto.php">Limpar tudo</a>

            </div>

            <form action="filtro_produto.php" method="GET">

                <!-- CATEGORIAS -->

                <div class="grupo-filtro">

                    <h4>Categorias</h4>

                    <?php while ($categoria = $resultado_categorias->fetch_assoc()) { ?>

                        <label class="opcao-filtro">

                            <input
                                type="radio"
                                name="categoria"
                                value="<?php echo $categoria['id_categorias']; ?>"
                                <?php echo ($categoria_selecionada == $categoria['id_categorias']) ? 'checked' : ''; ?>
                            >

                            <span>
                                <?php echo htmlspecialchars($categoria['nome_cat']); ?>
                            </span>

                            <small>
                                <?php echo $categoria['total_produtos']; ?>
                            </small>

                        </label>

                    <?php } ?>

                </div>


                <!-- MARCAS -->

                <div class="grupo-filtro">

                    <h4>Marcas</h4>

                    <?php while ($marca = $resultado_marcas->fetch_assoc()) { ?>

                        <label class="opcao-filtro">

                            <input
                                type="radio"
                                name="marca"
                                value="<?php echo htmlspecialchars($marca['marca']); ?>"
                                <?php echo ($marca_selecionada == $marca['marca']) ? 'checked' : ''; ?>
                            >

                            <span>
                                <?php echo htmlspecialchars($marca['marca']); ?>
                            </span>

                        </label>

                    <?php } ?>

                </div>


                <!-- FAIXA DE PREÇO -->

                <div class="grupo-filtro">

                    <h4>Faixa de preço</h4>

                    <div class="precos">

                        <input
                            type="number"
                            name="preco_min"
                            placeholder="R$ 0"
                            min="0"
                            step="0.01"
                            value="<?php echo $preco_min ?: ''; ?>"
                        >

                        <span>—</span>

                        <input
                            type="number"
                            name="preco_max"
                            placeholder="R$ 500"
                            min="0"
                            step="0.01"
                            value="<?php echo $preco_max ?: ''; ?>"
                        >

                    </div>

                </div>


                <input type="hidden" name="ordenacao" value="<?php echo htmlspecialchars($ordenacao); ?>">

                <button type="submit" class="btn-filtrar">
                    Aplicar filtros
                </button>

            </form>

        </aside>


        <!-- ========================================
             LISTAGEM DOS PRODUTOS
        ========================================= -->

        <section class="area-produtos">

            <!-- BARRA DE ORDENAÇÃO -->

            <div class="barra-produtos">

                <span>
                    Página <?php echo $total_paginas > 0 ? $pagina : 0; ?>
                    de <?php echo $total_paginas; ?>
                </span>

                <form action="filtro_produto.php" method="GET">

                    <?php if ($categoria_selecionada > 0) { ?>
                        <input type="hidden" name="categoria" value="<?php echo $categoria_selecionada; ?>">
                    <?php } ?>

                    <?php if ($marca_selecionada != '') { ?>
                        <input type="hidden" name="marca" value="<?php echo htmlspecialchars($marca_selecionada); ?>">
                    <?php } ?>

                    <input type="hidden" name="preco_min" value="<?php echo $preco_min; ?>">
                    <input type="hidden" name="preco_max" value="<?php echo $preco_max; ?>">

                    <label for="ordenacao">Ordenar por:</label>

                    <select name="ordenacao" id="ordenacao" onchange="this.form.submit()">

                        <option value="recentes" <?php echo ($ordenacao == 'recentes') ? 'selected' : ''; ?>>
                            Mais recentes
                        </option>

                        <option value="menor_preco" <?php echo ($ordenacao == 'menor_preco') ? 'selected' : ''; ?>>
                            Menor preço
                        </option>

                        <option value="maior_preco" <?php echo ($ordenacao == 'maior_preco') ? 'selected' : ''; ?>>
                            Maior preço
                        </option>

                        <option value="nome" <?php echo ($ordenacao == 'nome') ? 'selected' : ''; ?>>
                            Nome
                        </option>

                    </select>

                </form>

            </div>


            <!-- GRADE DE PRODUTOS -->

            <div class="grade-produtos">

                <?php if ($total_produtos > 0) { ?>

                    <?php while ($produtos = $resultado_produtos->fetch_assoc()) { ?>

                        <?php
                        $id_produto = (int) $produtos['id_produto'];

                        $sql_variacoes = "SELECT
                            id_variacao,
                            tamanho,
                            cor,
                            volume,
                            fragancia,
                            preco_adicional
                        FROM variacoes_produto
                        WHERE id_produto = $id_produto
                        AND ativo = 'ativo'";

                        $resultado_variacoes = consultar($con, $sql_variacoes);

                        $tem_variacao = ($resultado_variacoes->num_rows > 0);

                        $preco_atual = (
                            $produtos['preco_promocional'] > 0
                        ) ? $produtos['preco_promocional'] : $produtos['preco'];

                        $em_promocao = (
                            $produtos['preco_promocional'] > 0 &&
                            $produtos['preco_promocional'] < $produtos['preco']
                        );
                        ?>

                        <article class="card-produto">

                            <!-- IMAGEM -->

                            <div class="imagem-produto">

                                <a href="realizar_compra.php?id_produto=<?php echo $id_produto; ?>">

                                    <img
                                        src="<?php echo htmlspecialchars($produtos['imagem_produto']); ?>"
                                        alt="<?php echo htmlspecialchars($produtos['nome_prod']); ?>"
                                    >

                                </a>


                                <!-- PROMOÇÃO -->

                                <?php if ($em_promocao) { ?>

                                    <span class="etiqueta-promocao">
                                        OFERTA
                                    </span>

                                <?php } ?>


                                <!-- FAVORITO -->

                                <button type="button" class="favorito" aria-label="Adicionar aos favoritos">
                                    ♡
                                </button>


                                <!-- FORMULÁRIO DO CARRINHO -->

                                <form
                                    action="adicionar_carrinho.php"
                                    method="POST"
                                    class="form-carrinho"
                                >

                                    <input
                                        type="hidden"
                                        name="id_produto"
                                        value="<?php echo $id_produto; ?>"
                                    >

                                    <?php if ($tem_variacao) { ?>

                                        <select name="id_variacao" required>

                                            <option value="">Escolha uma variação</option>

                                            <?php while ($variacao = $resultado_variacoes->fetch_assoc()) { ?>

                                                <?php
                                                $descricao = array_filter([
                                                    $variacao['tamanho'],
                                                    $variacao['cor'],
                                                    $variacao['volume'],
                                                    $variacao['fragancia']
                                                ]);

                                                $nome_variacao = implode(' - ', $descricao);
                                                ?>

                                                <option value="<?php echo $variacao['id_variacao']; ?>">

                                                    <?php echo htmlspecialchars($nome_variacao); ?>

                                                    (+ R$
                                                    <?php echo number_format($variacao['preco_adicional'], 2, ',', '.'); ?>)

                                                </option>

                                            <?php } ?>

                                        </select>

                                    <?php } else { ?>

                                        <input type="hidden" name="id_variacao" value="">

                                    <?php } ?>


                                    <button type="submit" name="add_carrinho">

                                        Adicionar ao carrinho

                                    </button>

                                </form>

                            </div>


                            <!-- INFORMAÇÕES DO PRODUTO -->

                            <div class="informacoes-produto">

                                <span class="marca-produto">
                                    <?php echo htmlspecialchars($produtos['marca']); ?>
                                </span>

                                <a
                                    href="realizar_compra.php?id_produto=<?php echo $id_produto; ?>"
                                    class="nome-produto"
                                >
                                    <?php echo htmlspecialchars($produtos['nome_prod']); ?>
                                </a>


                                <!-- PREÇO -->

                                <div class="preco-produto">

                                    <strong>
                                        R$ <?php echo number_format($preco_atual, 2, ',', '.'); ?>
                                    </strong>

                                    <?php if ($em_promocao) { ?>

                                        <del>
                                            R$ <?php echo number_format($produtos['preco'], 2, ',', '.'); ?>
                                        </del>

                                    <?php } ?>

                                </div>

                            </div>

                        </article>

                    <?php } ?>

                <?php } else { ?>

                    <div class="sem-produtos">

                        <h2>Nenhum produto encontrado</h2>

                        <p>Não encontramos produtos com os filtros selecionados.</p>

                        <a href="filtro_produto.php">
                            Limpar filtros
                        </a>

                    </div>

                <?php } ?>

            </div>


            <!-- ========================================
                 PAGINAÇÃO
            ========================================= -->

            <?php if ($total_paginas > 1) { ?>

                <nav class="paginacao" aria-label="Navegação de páginas">

                    <!-- ANTERIOR -->

                    <?php if ($pagina > 1) { ?>

                        <a
                            href="<?php echo htmlspecialchars(linkPagina($pagina - 1)); ?>"
                            class="pagina-seta"
                            aria-label="Página anterior"
                        >
                            ‹
                        </a>

                    <?php } ?>


                    <!-- NÚMEROS DAS PÁGINAS -->

                    <?php

                    $ultima_exibida = 0;

                    for ($i = 1; $i <= $total_paginas; $i++) {

                        if (
                            $i == 1 ||
                            $i == $total_paginas ||
                            abs($i - $pagina) <= 1
                        ) {

                            if (
                                $ultima_exibida > 0 &&
                                $i > $ultima_exibida + 1
                            ) {
                    ?>

                                <span class="paginacao-pontos">...</span>

                    <?php
                            }
                    ?>

                            <a
                                href="<?php echo htmlspecialchars(linkPagina($i)); ?>"
                                class="pagina-numero <?php echo ($i == $pagina) ? 'ativa' : ''; ?>"
                                <?php echo ($i == $pagina) ? 'aria-current="page"' : ''; ?>
                            >
                                <?php echo $i; ?>
                            </a>

                    <?php

                            $ultima_exibida = $i;
                        }
                    }

                    ?>


                    <!-- PRÓXIMA -->

                    <?php if ($pagina < $total_paginas) { ?>

                        <a
                            href="<?php echo htmlspecialchars(linkPagina($pagina + 1)); ?>"
                            class="pagina-seta"
                            aria-label="Próxima página"
                        >
                            ›
                        </a>

                    <?php } ?>

                </nav>

            <?php } ?>

        </section>

    </div>

</main>

<?php include("includes/footer_cliente.php"); ?>

</body>
</html>
