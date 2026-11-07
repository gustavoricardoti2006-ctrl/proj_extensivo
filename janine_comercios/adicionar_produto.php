<?php
    require_once("includes/conexao.php");

    // Buscar as categorias cadastradas
    $sql = "SELECT id_categorias, nome_cat
        FROM categorias
        WHERE ativo = 'ativo'
        ORDER BY nome_cat";

    $resultado = mysqli_query($con, $sql);

    if (!$resultado) {
        die("Erro ao buscar categorias: " . mysqli_error($con));
    }
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/produtos.css">
    <title>Novo produto</title>
</head>
<body>
    <h2>Novo produto</h2>
    <a href="produtos_adm.php">X</a>

    <form action="adicionar_produto.php" method="POST">
        <label for="nome">Nome do produto</label>
        <input type="text" name="nome" id="nome" placeholder="Ex.: Sérum Facial Lumina C" required>

        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" placeholder="Descreva os benéficios e diferenciais" required></textarea>

        <label for="marca">Marca</label>
        <input type="text" name="marca" id="marca" placeholder="Ex.: Lumina" required>

        <label for="categoria">Categoria</label>
         <select name="id_categoria" id="categoria" required>
            <option value="">Selecione</option>

            <?php while ($categoria = mysqli_fetch_assoc($resultado)) { ?>
                <option value="<?= $categoria['id_categorias'] ?>">
                    <?= htmlspecialchars($categoria['nome_cat']) ?>
                </option>
            <?php } ?>

        </select>
        <hr>
        <h3>Preco e estoque</h3>

        <label for="preco_compra">Preço de compra</label>
        <input type="number" name="preco_compra" id="preco_compra" placeholder="R$ 0,00" step="0.01" min="0" required>

        <label for="preco_venda">Preço de venda</label>
        <input type="number" name="preco_venda" id="preco_venda" placeholder="R$ 0,00" step="0.01" min="0" required>

        <label for="estoque_inicial">Estoque inicial</label>
        <input type="number" name="estoque_inicial" id="estoque_inicial" placeholder="0" step="1" min="0">

        <button type="reset">Zerar</button>

        <button type="submit">Salvar produto</button>
    
    </form>    
</body>
</html>