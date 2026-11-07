<?php 
    require_once("includes/conexao.php");

    $id = $_GET['id'] ?? null;  
    
    if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
        die("ID do produto inválido.");
    }   

    $sql = "DELETE FROM produto WHERE id_produto = ?";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Erro ao preparar exclusão: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: produtos_adm.php?msg=Produto excluído com sucesso.");
        } else {
            header("Location: produtos_adm.php?msg=Produto não encontrado.");
        }
        exit();
    } else {
        echo "Erro ao excluir produto: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
?>