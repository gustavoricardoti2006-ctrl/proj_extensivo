
<?php
    require_once("includes/conexao.php");

    $id = $_GET['id'] ?? null;

    if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
        die("ID do produto inválido.");
    }

    $sql = "SELECT
                p.id_produto,
                p.nome_prod,
                p.marca,
                p.descricao,
                p.preco_compra,
                p.preco,
                p.preco_promocional,
                p.id_categoria
            FROM produto p
            WHERE p.id_produto = ?";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Erro ao preparar consulta: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $produto = mysqli_fetch_assoc($resultado);

    if (!$produto) {
        die("Produto não encontrado.");
    }

    $sql_categorias = "SELECT id_categorias, nome_cat
                    FROM categorias
                    ORDER BY nome_cat";

    $resultado_categorias = mysqli_query($con, $sql_categorias);

    if (!$resultado_categorias) {
        die("Erro ao buscar categorias: " . mysqli_error($con));
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/produtos_adm.css">
    <link rel="stylesheet" href="css/header_admin.css">

    <title>Editar produto</title>
</head>

<body>

    <?php include("includes/header_admin.php"); ?>

    <main class="conteudo">

        <div class="formulario-produto">

            <div class="titulo-formulario">
                <h2>Editar produto</h2>

                <a href="produtos_adm.php"
                   class="btn-fechar"
                   title="Voltar para produtos">
                    &times;
                </a>
            </div>

            <form action="atualizar_produto.php" method="POST"
                  class="form-produto">

                <input type="hidden" name="id"
                       value="<?= $produto['id_produto'] ?>">

                <h3>Dados do produto</h3>

                <label for="nome">Nome do produto</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= htmlspecialchars($produto['nome_prod']) ?>"
                    required
                >

                <label for="descricao">Descrição</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    required
                ><?= htmlspecialchars($produto['descricao']) ?></textarea>

                <label for="marca">Marca</label>
                <input
                    type="text"
                    id="marca"
                    name="marca"
                    value="<?= htmlspecialchars($produto['marca']) ?>"
                    required
                >

                <label for="id_categoria">Categoria</label>

                <select id="id_categoria" name="id_categoria" required>

                    <option value="">Selecione</option>

                    <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)) { ?>

                        <option
                            value="<?= $categoria['id_categorias'] ?>"
                            <?= ($categoria['id_categorias'] == $produto['id_categoria']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($categoria['nome_cat']) ?>
                        </option>

                    <?php } ?>

                </select>

                <hr>

                <h3>Preços</h3>

                <label for="preco_compra">Preço de compra</label>
                <input
                    type="number"
                    id="preco_compra"
                    name="preco_compra"
                    value="<?= htmlspecialchars((string)$produto['preco_compra']) ?>"
                    step="0.01"
                    min="0"
                    required
                >

                <label for="preco">Preço de venda</label>
                <input
                    type="number"
                    id="preco"
                    name="preco"
                    value="<?= htmlspecialchars((string)$produto['preco']) ?>"
                    step="0.01"
                    min="0"
                    required
                >
                <label for="preco_promocional">Preço promocional</label>
                <input
                    type="number"
                    id="preco_promocional"
                    name="preco_promocional"
                    value="<?= htmlspecialchars((string)($produto['preco_promocional'] ?? '')) ?>"
                    step="0.01"
                    min="0"
                >
                <div class="acoes-formulario">
                    <a href="produtos_adm.php" class="btn-cancelar">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-salvar">
                        Atualizar produto
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>