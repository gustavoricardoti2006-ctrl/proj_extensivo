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
                p.preco,
                p.preco_promocional,
                p.id_categoria
            FROM produto p
            WHERE p.id_produto = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $produto = mysqli_fetch_assoc($resultado);

    if (!$produto) {
        die("Produto não encontrado.");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/produtos.css">
    <title>Editar produto</title>
</head>
<body>
    <form action="atualizar_produto.php" method="POST">
        <input type="hidden" name="id" value="<?= $produto['id_produto'] ?>">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>

        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($produto['marca']) ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" value="<?= $produto['preco'] ?>" step="0.01" required>

        <label for="preco_promocional">Preço Promocional:</label>
        <input type="number" id="preco_promocional" name="preco_promocional" value="<?= $produto['preco_promocional'] ?>" step="0.01">

        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <?php
                $sql_categorias = "SELECT id_categorias, nome_cat FROM categorias";
                $stmt_categorias = mysqli_prepare($con, $sql_categorias);
                mysqli_stmt_execute($stmt_categorias);
                $resultado_categorias = mysqli_stmt_get_result($stmt_categorias);

                while ($categoria = mysqli_fetch_assoc($resultado_categorias)) {
                    $selected = ($categoria['id_categorias'] == $produto['id_categoria']) ? 'selected' : '';
                    echo "<option value='{$categoria['id_categorias']}' {$selected}>{$categoria['nome_cat']}</option>";
                }
            ?>
        </select>

        <button type="submit">Atualizar Produto</button>
    </form>
</body>
</html>