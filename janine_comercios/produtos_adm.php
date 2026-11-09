<?php
    require_once("includes/conexao.php");
    
    // Buscar categorias cadastradas
$sql_categorias = "SELECT id_categorias, nome_cat
                   FROM categorias
                   WHERE ativo = 'ativo'
                   ORDER BY nome_cat";

$categorias = mysqli_query($con, $sql_categorias);

if (!$categorias) {
    die("Erro ao buscar categorias: " . mysqli_error($con));
}

        $msg = $_GET['msg'] ?? '';
    $pesquisa = $_GET['pesquisar'] ?? '';

    // Receber o filtro de categoria
$categoria_filtro = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);

if ($categoria_filtro === false || $categoria_filtro === null) {
    $categoria_filtro = 0;
}

// Receber o filtro de status
$status_filtro = $_GET['status'] ?? '';

if (!in_array($status_filtro, ['', '1', '0'], true)) {
    $status_filtro = '';
}

    $limite = 4;

    // Página atual
    $pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

    if (!$pagina || $pagina < 1) {
        $pagina = 1;
    }

    $sql = "SELECT
                p.id_produto,
                p.nome_prod,
                p.marca,
                p.preco,
                p.preco_compra,
                p.preco_promocional,
                c.nome_cat,
                COALESCE(SUM(e.quantidade), 0) AS estoque_total,
                MAX(
                    CASE
                        WHEN v.ativo = 'ativo' THEN 1
                        ELSE 0
                    END
                ) AS status_produto

            FROM produto p

            INNER JOIN categorias c
            ON p.id_categoria = c.id_categorias

            LEFT JOIN variacoes_produto v
            ON p.id_produto = v.id_produto

            LEFT JOIN estoque e
            ON v.id_variacao = e.id_variacao

            WHERE (
                p.nome_prod LIKE ?
                OR p.marca LIKE ?
            )

            AND (? = 0 OR p.id_categoria = ?)

            GROUP BY p.id_produto

            HAVING (? = '' OR status_produto = ?)";

    $termo = "%" . $pesquisa . "%";

    $sql_total = "SELECT COUNT(*) AS total
                  FROM ($sql) AS produtos_filtrados";

    $stmt_total = mysqli_prepare($con, $sql_total);

    mysqli_stmt_bind_param(
        $stmt_total,
        "ssiiss",
        $termo,
        $termo,
        $categoria_filtro,
        $categoria_filtro,
        $status_filtro,
        $status_filtro
    );

    mysqli_stmt_execute($stmt_total);

    $resultado_total = mysqli_stmt_get_result($stmt_total);

    $dados_total = mysqli_fetch_assoc($resultado_total);

    $total_produtos = $dados_total['total'];

    $total_paginas = ceil($total_produtos / $limite);

    if ($total_paginas > 0 && $pagina > $total_paginas) {
        $pagina = $total_paginas;
    }

    $offset = ($pagina - 1) * $limite;

    $sql .= " LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssiissii",
        $termo,
        $termo,
        $categoria_filtro,
        $categoria_filtro,
        $status_filtro,
        $status_filtro,
        $limite,
        $offset
    );

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $parametros = [
        'pesquisar' => $pesquisa,
        'categoria' => $categoria_filtro,
        'status' => $status_filtro
    ];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produtos</title>
    <link rel="stylesheet" href="css/produtos_adm.css">
    <link rel="stylesheet" href="css/header_admin.css">
</head>

<body>

    <?php include("includes/header_admin.php"); ?>

    <main class="conteudo">

        <section class="cabecalho">
            <div>
                <h2>CATÁLOGO</h2>
                <h1>Gerenciamento de produtos</h1>

                <p>
                    <?= $total_produtos ?>
                    <?= $total_produtos == 1 ? 'produto cadastrado' : 'produtos cadastrados' ?>
                </p>
            </div>

            <a href="adicionar_produto.php" class="btn-adicionar">
                + Adicionar produto
            </a>
        </section>

        <?php if (!empty($msg)) { ?>
            <p class="mensagem-sucesso">
                <?= htmlspecialchars($msg) ?>
            </p>
        <?php } ?>

        
<form action="" method="GET" class="filtros">

    <input
        type="text"
        id="pesquisar"
        name="pesquisar"
        placeholder="Buscar por nome ou marca..."
        value="<?= htmlspecialchars($pesquisa) ?>"
    >

    <!-- Filtro de categoria -->
    <select name="categoria">

        <option value="0">Todas as categorias</option>

        <?php while ($categoria = mysqli_fetch_assoc($categorias)) { ?>

            <option
                value="<?= $categoria['id_categorias'] ?>"
                <?= $categoria_filtro == $categoria['id_categorias'] ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($categoria['nome_cat']) ?>
            </option>

        <?php } ?>

    </select>

    <!-- Filtro de status -->
    <select name="status">

        <option value="">Todos os status</option>

        <option value="1" <?= $status_filtro === '1' ? 'selected' : '' ?>>
            Ativo
        </option>

        <option value="0" <?= $status_filtro === '0' ? 'selected' : '' ?>>
            Inativo
        </option>

    </select>

    <button type="submit" class="btn-filtrar">
        Filtrar
    </button>

    </form>


        <div class="tabela-container">

            <table>

                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Compra</th>
                        <th>Venda</th>
                        <th>Estoque</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($produto = mysqli_fetch_assoc($resultado)) { ?>

                        <tr>

                            <td class="produto-nome">
                                <?= htmlspecialchars($produto['nome_prod']) ?>
                                <p><?= htmlspecialchars($produto['marca']) ?></p>
                            </td>

                            <td>
                                <?= htmlspecialchars($produto['nome_cat']) ?>
                            </td>

                            <td>R$ <?= number_format($produto['preco_compra'], 2, ',', '.') ?>
                            </td>

                            <td>
                                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>

                                <p class="preco-promocional">
                                    <?php if ($produto['preco_promocional'] !== null) { ?>
                                        Promo: R$ <?= number_format($produto['preco_promocional'], 2, ',', '.') ?>
                                    <?php } ?>
                                </p>
                            </td>

                            <td>
                                <?= $produto['estoque_total'] ?>
                                un.
                            </td>

                            <td>
                                <span class="status <?= $produto['status_produto'] == 1 ? 'ativo' : 'inativo' ?>">
                                    <?= $produto['status_produto'] == 1 ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>

                            <td class="acoes">
                                <a href="editar_produto.php?id=<?= $produto['id_produto'] ?>">
                                    <img src="img/edit.png" alt="Editar">
                                </a>

                                <a href="excluir_produto.php?id=<?= $produto['id_produto'] ?>"
                                    class="excluir"
                                    onclick="return confirm('Tem certeza de que deseja excluir este produto?')">

                                        <img src="img/trash.png" alt="Excluir">
                                    </a>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="7">

                            <?php
                                $inicio = $total_produtos > 0 ? $offset + 1 : 0;
                                $fim = min($offset + $limite, $total_produtos);
                            ?>

                            <span class="info-paginacao">
                                Mostrando <?= $inicio ?>–<?= $fim ?>
                                de <?= $total_produtos ?> produtos
                            </span>

                            <div class="paginacao">

                                <!-- Página anterior -->
                                <?php if ($pagina > 1) { ?>

                                    <a href="?<?= http_build_query(array_merge($parametros, ['pagina' => $pagina - 1])) ?>">
                                        &lt;
                                    </a>

                                <?php } else { ?>

                                    <span class="desabilitado">&lt;</span>

                                <?php } ?>


                                <!-- Números das páginas -->
                                <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>

                                    <a
                                        href="?<?= http_build_query(array_merge($parametros, ['pagina' => $i])) ?>"
                                        class="<?= $pagina == $i ? 'pagina-atual' : '' ?>"
                                    >
                                        <?= $i ?>
                                    </a>

                                <?php } ?>


                                <!-- Próxima página -->
                                <?php if ($pagina < $total_paginas) { ?>

                                    <a href="?<?= http_build_query(array_merge($parametros, ['pagina' => $pagina + 1])) ?>">
                                        &gt;
                                    </a>

                                <?php } else { ?>

                                    <span class="desabilitado">&gt;</span>

                                <?php } ?>

                            </div>

                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>
</body>
</html>