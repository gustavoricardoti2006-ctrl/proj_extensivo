<?php
session_start();

require_once('includes/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['sessao'])) {
    header('Location: login.php');
    exit;
}

// Recebe o ID do cliente pela sessão
$id_cliente = $_SESSION['sessao'];

// Recebe o ID da variação pelo formulário
$id_variacao = $_POST['id_variacao'] ?? 0;

if ($id_variacao <= 0) {
    exit("Variação inválida.");
}


// 1. Verifica se a variação existe e está ativa
$sql_variacao = "SELECT id_variacao FROM variacoes_produto 
                 WHERE id_variacao = ? AND ativo = 'ativo'";

$stmt = $con->prepare($sql_variacao);
$stmt->bind_param("i", $id_variacao);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    exit("Variação não encontrada.");
}

$stmt->close();


// 2. Verifica se o cliente já possui um carrinho
$sql_carrinho = "SELECT id_carrinhos FROM carrinhos WHERE id_cliente = ?";

$stmt = $con->prepare($sql_carrinho);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    // Recupera o carrinho existente
    $carrinho = $resultado->fetch_assoc();
    $id_carrinho = $carrinho['id_carrinhos'];

} else {

    // Cria um novo carrinho
    $sql_novo_carrinho = "INSERT INTO carrinhos (id_cliente) VALUES (?)";

    $stmt_novo = $con->prepare($sql_novo_carrinho);
    $stmt_novo->bind_param("i", $id_cliente);
    $stmt_novo->execute();

    $id_carrinho = $con->insert_id;

    $stmt_novo->close();
}

$stmt->close();


// 3. Verifica se a variação já está no carrinho
$sql_item = "SELECT id_item_carrinho, quantidade 
             FROM item_carrinho 
             WHERE id_carrinho = ? AND id_variacao = ?";

$stmt = $con->prepare($sql_item);
$stmt->bind_param("ii", $id_carrinho, $id_variacao);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    // Se já existe, aumenta a quantidade
    $item = $resultado->fetch_assoc();

    $nova_quantidade = $item['quantidade'] + 1;

    $sql_atualizar = "UPDATE item_carrinho 
                      SET quantidade = ? 
                      WHERE id_item_carrinho = ?";

    $stmt_atualizar = $con->prepare($sql_atualizar);
    $stmt_atualizar->bind_param("ii", $nova_quantidade, $item['id_item_carrinho']);
    $stmt_atualizar->execute();

    $stmt_atualizar->close();

} else {

    // Se não existe, adiciona um novo item
    $sql_inserir = "INSERT INTO item_carrinho 
                    (id_carrinho, id_variacao, quantidade) 
                    VALUES (?, ?, 1)";

    $stmt_inserir = $con->prepare($sql_inserir);
    $stmt_inserir->bind_param("ii", $id_carrinho, $id_variacao);
    $stmt_inserir->execute();

    $stmt_inserir->close();
}

$stmt->close();

// Volta para a página anterior
header('Location: filtro_produto.php');
exit;

?>