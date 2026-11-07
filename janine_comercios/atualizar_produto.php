<?php
    require_once("includes/conexao.php");

    $id = $_POST['id'] ?? null;

    if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
        die("ID do produto inválido.");
    }

    $nome = $_POST['nome'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $preco_promocional = $_POST['preco_promocional'] ?? null;
    if ($preco_promocional === '') {
        $preco_promocional = null;
    }

    $id_categoria = $_POST['id_categoria'] ?? null;

    $sql =
    "UPDATE produto 
    SET
        nome_prod = ?,
        marca = ?,
        descricao = ?,
        preco = ?,
        preco_promocional = ?,
        id_categoria = ?
    WHERE id_produto = ?";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Erro ao preparar atualização: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "sssddii", $nome, $marca, $descricao, $preco, $preco_promocional, $id_categoria, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: produtos_adm.php?msg=Produto atualizado com sucesso.");
        exit();
    } else {
        echo "Erro ao atualizar o produto.";
    }
    
?>