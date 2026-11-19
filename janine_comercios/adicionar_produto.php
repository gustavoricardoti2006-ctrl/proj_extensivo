
<?php
require_once("includes/conexao.php");

$msg = "";

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Dados do produto
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);

    $preco_compra = $_POST['preco_compra'] ?? '';
    $preco_venda = $_POST['preco_venda'] ?? '';

    $variacoes = $_POST['variacoes'] ?? [];

    // Validações básicas
    if (
        $nome === '' ||
        $descricao === '' ||
        $marca === '' ||
        !$id_categoria ||
        !is_numeric($preco_compra) ||
        !is_numeric($preco_venda) ||
        $preco_compra < 0 ||
        $preco_venda < 0 ||
        !is_array($variacoes) ||
        count($variacoes) === 0
    ) {
        $msg = "Preencha corretamente os dados do produto e adicione uma variação.";

    } else {

        try {
            // Iniciar transação
            mysqli_begin_transaction($con);

            // 1. Cadastrar produto
            $sql_produto = "INSERT INTO produto
                (nome_prod, descricao, marca, preco_compra, preco, id_categoria)
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmt_produto = mysqli_prepare($con, $sql_produto);

            mysqli_stmt_bind_param(
                $stmt_produto,
                "sssddi",
                $nome,
                $descricao,
                $marca,
                $preco_compra,
                $preco_venda,
                $id_categoria
            );

            if (!mysqli_stmt_execute($stmt_produto)) {
                throw new Exception(mysqli_stmt_error($stmt_produto));
            }

            // Recuperar o ID do produto cadastrado
            $id_produto = mysqli_insert_id($con);

            // 2. Preparar cadastro das variações
            $sql_variacao = "INSERT INTO variacoes_produto
                (id_produto, tamanho, cor, volume, fragancia, preco_adicional, ativo)
                VALUES (?, ?, ?, ?, ?, ?, 'ativo')";

            $stmt_variacao = mysqli_prepare($con, $sql_variacao);

            // 3. Preparar cadastro do estoque
            $sql_estoque = "INSERT INTO estoque
                (id_variacao, quantidade, atualizado)
                VALUES (?, ?, NOW())";

            $stmt_estoque = mysqli_prepare($con, $sql_estoque);

            // Percorrer todas as variações recebidas
            foreach ($variacoes as $variacao) {

                $tamanho = trim($variacao['tamanho'] ?? '');
                $cor = trim($variacao['cor'] ?? '');
                $volume = trim($variacao['volume'] ?? '');
                $fragancia = trim($variacao['fragancia'] ?? '');

                $preco_adicional = $variacao['preco_adicional'] ?? 0;
                $estoque_inicial = $variacao['estoque_inicial'] ?? 0;

                if (
                    !is_numeric($preco_adicional) ||
                    filter_var($estoque_inicial, FILTER_VALIDATE_INT) === false ||
                    $preco_adicional < 0 ||
                    $estoque_inicial < 0
                ) {
                    throw new Exception("Dados inválidos em uma das variações.");
                }

                // Cadastrar variação
                mysqli_stmt_bind_param(
                    $stmt_variacao,
                    "issssd",
                    $id_produto,
                    $tamanho,
                    $cor,
                    $volume,
                    $fragancia,
                    $preco_adicional
                );

                if (!mysqli_stmt_execute($stmt_variacao)) {
                    throw new Exception(mysqli_stmt_error($stmt_variacao));
                }

                // Recuperar ID da variação
                $id_variacao = mysqli_insert_id($con);

                // Cadastrar estoque inicial
                mysqli_stmt_bind_param(
                    $stmt_estoque,
                    "ii",
                    $id_variacao,
                    $estoque_inicial
                );

                if (!mysqli_stmt_execute($stmt_estoque)) {
                    throw new Exception(mysqli_stmt_error($stmt_estoque));
                }
            }

            // Confirmar todas as operações
            mysqli_commit($con);

            header("Location: produtos_adm.php?msg=Produto cadastrado com sucesso!");
            exit;

        } catch (Exception $e) {

            // Desfazer operações se houver erro
            mysqli_rollback($con);

            $msg = "Erro ao cadastrar produto: " . $e->getMessage();
        }
    }
}

// Buscar categorias cadastradas
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

    <link rel="stylesheet" href="css/produtos_adm.css">
    <link rel="stylesheet" href="css/header_admin.css">
    <title>Novo produto</title>
</head>

<body>
    <?php include("includes/header_admin.php"); ?>


    <?php if ($msg !== '') { ?>
        <p><?= htmlspecialchars($msg) ?></p>
    <?php } ?>

    <div class="titulo-formulario">
        <h2>Novo produto</h2>

        <a href="produtos_adm.php" class="btn-fechar" title="Voltar para produtos">
            &times;
        </a>
    </div>

    <form action="adicionar_produto.php" method="POST">

        <h3>Dados do produto</h3>

        <label for="nome">Nome do produto</label>
        <input type="text" name="nome" id="nome"
            placeholder="Ex.: Sérum Facial Lumina C" required>

        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao"
            placeholder="Descreva os benefícios e diferenciais" required></textarea>

        <label for="marca">Marca</label>
        <input type="text" name="marca" id="marca"
            placeholder="Ex.: Lumina" required>

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

        <h3>Preço</h3>

        <label for="preco_compra">Preço de compra</label>
        <input type="number" name="preco_compra" id="preco_compra"
            placeholder="R$ 0,00" step="0.01" min="0" required>

        <label for="preco_venda">Preço de venda</label>
        <input type="number" name="preco_venda" id="preco_venda"
            placeholder="R$ 0,00" step="0.01" min="0" required>

        <hr>

        <h3>Variações do produto</h3>

        <div id="container-variacoes">

            <div class="variacao">

                <h4>Variação 1</h4>

                <label>Tamanho</label>
                <input type="text" name="variacoes[0][tamanho]"
                    placeholder="Ex.: P, M, G">

                <label>Cor</label>
                <input type="text" name="variacoes[0][cor]"
                    placeholder="Ex.: Vermelho">

                <label>Volume</label>
                <input type="text" name="variacoes[0][volume]"
                    placeholder="Ex.: 30 ml">

                <label>Fragrância</label>
                <input type="text" name="variacoes[0][fragancia]"
                    placeholder="Ex.: Lavanda">

                <label>Preço adicional</label>
                <input type="number" name="variacoes[0][preco_adicional]"
                    value="0.00" step="0.01" min="0">

                <label>Estoque inicial</label>
                <input type="number" name="variacoes[0][estoque_inicial]"
                    value="0" min="0" step="1" required>

            </div>

        </div>

        <button type="button" id="adicionar-variacao">
            + Adicionar variação
        </button>

        <hr>

        <button type="reset">Zerar</button>
        <button type="submit">Salvar produto</button>

    </form>
    <script src="js/variacoes.js"></script>

</body>
</html>
