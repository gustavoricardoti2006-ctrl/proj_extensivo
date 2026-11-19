
<?php
require_once('includes/conexao.php');

/* =========================================
   RECEBE O ID DO PRODUTO
========================================= */

$id_produto = filter_input(INPUT_GET, 'id_produto', FILTER_VALIDATE_INT);

if (!$id_produto) {
    die("Produto não encontrado.");
}

/* =========================================
   BUSCA O PRODUTO
========================================= */

$sql = "SELECT
            id_produto,
            id_categoria,
            nome_prod,
            descricao,
            marca,
            preco,
            preco_promocional,
            imagem_produto,
            data_cadastro
        FROM produto
        WHERE id_produto = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_produto);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Produto não encontrado.");
}

$produto = $resultado->fetch_assoc();

/* =========================================
   BUSCA AS VARIAÇÕES
========================================= */

$sql_variacoes = "SELECT
                      id_variacao,
                      id_produto,
                      tamanho,
                      cor,
                      volume,
                      fragancia,
                      preco_adicional,
                      ativo
                  FROM variacoes_produto
                  WHERE id_produto = ?";

$stmt_variacoes = $con->prepare($sql_variacoes);
$stmt_variacoes->bind_param("i", $id_produto);
$stmt_variacoes->execute();

$resultado_variacoes = $stmt_variacoes->get_result();

$variacoes = [];

while ($linha = $resultado_variacoes->fetch_assoc()) {
    $variacoes[] = $linha;
}

/* =========================================
   PREÇOS
========================================= */

$preco_original = (float)$produto['preco'];

$preco_base = (
    $produto['preco_promocional'] !== null &&
    $produto['preco_promocional'] !== '' &&
    (float)$produto['preco_promocional'] > 0
)
    ? (float)$produto['preco_promocional']
    : $preco_original;

$em_promocao = $preco_base < $preco_original;

/* =========================================
   IMAGEM
========================================= */

$imagem = !empty($produto['imagem_produto'])
    ? $produto['imagem_produto']
    : 'img/produto-sem-imagem.png';

/* =========================================
   FUNÇÃO DE SEGURANÇA
========================================= */

function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($produto['nome_prod']) ?> | Janine Cosmeticos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="css/header_cliente.css">
    <link rel="stylesheet" href="css/footer_cliente.css">

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #FDFBF9;
            color: #382820;
            font-family: 'Inter', sans-serif;
        }

        .container {
            width: 84%;
            max-width: 1250px;
            margin: 0 auto;
            padding: 28px 0;
        }

        /* CAMINHO */

        .breadcrumb {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 11px;
            color: #947F74;
            margin-bottom: 24px;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #947F74;
        }

        .breadcrumb a:hover {
            color: #593D32;
        }

        /* PRODUTO */

        .produto {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 52px;
            align-items: start;
        }

        /* IMAGEM */

        .imagem-container {
            width: 100%;
            height: 447px;
            background: #F0E8E0;
            border-radius: 12px;
            overflow: hidden;
        }

        .imagem-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* INFORMAÇÕES */

        .informacoes {
            padding-top: 8px;
        }

        .marca {
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #C96659;
            font-weight: 600;
            margin-bottom: 13px;
        }

        .nome-produto {
            font-family: 'DM Sans', sans-serif;
            font-size: 29px;
            font-weight: 500;
            color: #382820;
            line-height: 1.25;
            margin-bottom: 10px;
        }

        .descricao {
            font-size: 12px;
            line-height: 1.8;
            color: #806E65;
            margin-top: 15px;
            margin-bottom: 17px;
        }

        /* PREÇO */

        .preco-area {
            border-top: 1px solid #E8D9CE;
            padding-top: 17px;
            margin-top: 17px;
        }

        .preco {
            font-family: 'DM Sans', sans-serif;
            font-size: 23px;
            font-weight: 600;
            color: #593D32;
        }

        .preco-antigo {
            font-size: 13px;
            color: #A99990;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .parcelamento {
            font-size: 10px;
            color: #947F74;
            margin-top: 5px;
        }

        /* VARIAÇÕES */

        .volume-area {
            margin-top: 21px;
        }

        .titulo-opcao {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .opcoes {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .opcao-volume {
            border: 1px solid #E5D4C8;
            background: white;
            color: #593D32;
            padding: 9px 15px;
            border-radius: 7px;
            font-size: 10px;
            cursor: pointer;
            transition: .2s;
        }

        .opcao-volume:hover {
            border-color: #593D32;
        }

        .opcao-volume.selecionado {
            background: #593D32;
            color: white;
            border-color: #593D32;
        }

        /* AÇÕES */

        .acoes {
            display: grid;
            grid-template-columns: 71px 1fr;
            gap: 8px;
            margin-top: 19px;
        }

        .quantidade {
            height: 33px;
            border: 1px solid #E5D4C8;
            background: white;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .quantidade button {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #593D32;
            font-size: 15px;
        }

        .quantidade input {
            width: 20px;
            border: none;
            outline: none;
            text-align: center;
            font-size: 11px;
            color: #382820;
        }

        .btn-carrinho {
            height: 33px;
            border: none;
            background: #E98576;
            color: #382820;
            border-radius: 7px;
            font-size: 11px;
            cursor: pointer;
            transition: .2s;
        }

        .btn-carrinho:hover {
            background: #D97566;
        }

        .btn-comprar {
            width: 100%;
            height: 34px;
            margin-top: 8px;
            border: 1px solid #E5D4C8;
            background: white;
            color: #593D32;
            border-radius: 7px;
            font-size: 11px;
            cursor: pointer;
            transition: .2s;
        }

        .btn-comprar:hover {
            background: #F7F1E8;
        }

        /* BENEFÍCIOS */

        .beneficios {
            margin-top: 16px;
            border-top: 1px solid #E8D9CE;
        }

        .beneficio {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #E8D9CE;
        }

        .beneficio .icone {
            font-size: 16px;
        }

        .beneficio strong {
            display: block;
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .beneficio span {
            font-size: 9px;
            color: #947F74;
        }

        /* RESPONSIVIDADE */

        @media (max-width: 850px) {

            .container {
                width: 92%;
            }

            .produto {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .imagem-container {
                height: 400px;
            }

            .informacoes {
                padding-top: 0;
            }

        }

        @media (max-width: 480px) {

            .imagem-container {
                height: 330px;
            }

            .nome-produto {
                font-size: 25px;
            }

        }

    </style>

</head>

<body>

    <?php include("includes/header_cliente.php")?>

    <main class="container">

        <!-- CAMINHO -->

        <div class="breadcrumb">

            <a href="tela_inicial.php">Início</a>

            <span>›</span>

            <span><?= e($produto['nome_prod']) ?></span>

        </div>

        <section class="produto">

            <!-- IMAGEM DO PRODUTO -->

            <div class="imagem-container">

                <img
                    src="<?= e($imagem) ?>"
                    alt="<?= e($produto['nome_prod']) ?>"
                    onerror="this.onerror=null; this.src='img/produto-sem-imagem.png';"
                >

            </div>

            <!-- INFORMAÇÕES DO PRODUTO -->

            <div class="informacoes">

                <div class="marca">
                    <?= e($produto['marca']) ?>
                </div>

                <h1 class="nome-produto">
                    <?= e($produto['nome_prod']) ?>
                </h1>

                <p class="descricao">
                    <?= nl2br(e($produto['descricao'])) ?>
                </p>

                <!-- PREÇO -->

                <div class="preco-area">

                    <div>

                        <span class="preco" id="preco">
                            R$ <?= number_format($preco_base, 2, ',', '.') ?>
                        </span>

                        <?php if ($em_promocao): ?>

                            <span class="preco-antigo">
                                R$ <?= number_format($preco_original, 2, ',', '.') ?>
                            </span>

                        <?php endif; ?>

                    </div>

                    <p class="parcelamento" id="parcelamento">
                        ou 2x de R$ <?= number_format($preco_base / 2, 2, ',', '.') ?> sem juros
                    </p>

                </div>

                <!-- FORMULÁRIO -->

                <form action="adicionar_carrinho.php" method="POST" id="formProduto">

                    <input
                        type="hidden"
                        name="id_produto"
                        value="<?= (int)$produto['id_produto'] ?>"
                    >

                    <input
                        type="hidden"
                        name="id_variacao"
                        id="id_variacao"
                        value=""
                    >

                    <!-- VARIAÇÕES -->

                    <?php if (!empty($variacoes)): ?>

                        <div class="volume-area">

                            <p class="titulo-opcao">Volume</p>

                            <div class="opcoes">

                                <?php foreach ($variacoes as $index => $variacao): ?>

                                    <?php
                                    $descricao_variacao = [];

                                    if (!empty($variacao['volume'])) {
                                        $descricao_variacao[] = $variacao['volume'];
                                    }

                                    if (!empty($variacao['tamanho'])) {
                                        $descricao_variacao[] = $variacao['tamanho'];
                                    }

                                    if (!empty($variacao['cor'])) {
                                        $descricao_variacao[] = $variacao['cor'];
                                    }

                                    if (!empty($variacao['fragancia'])) {
                                        $descricao_variacao[] = $variacao['fragancia'];
                                    }

                                    $nome_variacao = !empty($descricao_variacao)
                                        ? implode(' - ', $descricao_variacao)
                                        : 'Opção ' . $variacao['id_variacao'];

                                    $preco_variacao = $preco_base + (float)$variacao['preco_adicional'];
                                    ?>

                                    <button
                                        type="button"
                                        class="opcao-volume <?= $index == 0 ? 'selecionado' : '' ?>"
                                        data-id="<?= (int)$variacao['id_variacao'] ?>"
                                        data-preco="<?= $preco_variacao ?>"
                                        onclick="selecionarVariacao(this)"
                                    >
                                        <?= e($nome_variacao) ?>
                                    </button>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- QUANTIDADE E CARRINHO -->

                    <div class="acoes">

                        <div class="quantidade">

                            <button type="button" onclick="alterarQuantidade(-1)">
                                −
                            </button>

                            <input
                                type="number"
                                name="quantidade"
                                id="quantidade"
                                value="1"
                                min="1"
                                max="99"
                                readonly
                            >

                            <button type="button" onclick="alterarQuantidade(1)">
                                +
                            </button>

                        </div>

                        <button
                            type="submit"
                            name="acao"
                            value="carrinho"
                            class="btn-carrinho"
                        >
                            Adicionar ao carrinho &nbsp; ♧
                        </button>

                    </div>

                    <!-- COMPRAR AGORA -->

                    <button
                        type="submit"
                        name="acao"
                        value="comprar"
                        class="btn-comprar"
                    >
                        Comprar agora
                    </button>

                </form>

                <!-- BENEFÍCIOS -->

                <div class="beneficios">

                    <div class="beneficio">

                        <div class="icone">♧</div>

                        <div>
                            <strong>Consulte entrega e prazo</strong>
                            <span>Valores demonstrativos no checkout</span>
                        </div>

                    </div>

                    <div class="beneficio">

                        <div class="icone">♢</div>

                        <div>
                            <strong>Compra segura</strong>
                            <span>Seus dados sempre protegidos</span>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>

    <?php include("includes/footer_cliente.php")?>

    <script>

        /* =====================================
           ALTERAR QUANTIDADE
        ===================================== */

        function alterarQuantidade(valor) {

            const campo = document.getElementById('quantidade');

            let quantidade = parseInt(campo.value);

            quantidade += valor;

            if (quantidade < 1) {
                quantidade = 1;
            }

            if (quantidade > 99) {
                quantidade = 99;
            }

            campo.value = quantidade;

        }

        /* =====================================
           SELECIONAR VARIAÇÃO
        ===================================== */

        function selecionarVariacao(botao) {

            document.querySelectorAll('.opcao-volume').forEach(opcao => {
                opcao.classList.remove('selecionado');
            });

            botao.classList.add('selecionado');

            const id = botao.dataset.id;
            const preco = parseFloat(botao.dataset.preco);

            document.getElementById('id_variacao').value = id;

            document.getElementById('preco').textContent =
                'R$ ' + preco.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            document.getElementById('parcelamento').textContent =
                'ou 2x de R$ ' + (preco / 2).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' sem juros';

        }

        /* =====================================
           SELECIONA PRIMEIRA VARIAÇÃO
        ===================================== */

        const primeiraOpcao = document.querySelector('.opcao-volume');

        if (primeiraOpcao) {
            selecionarVariacao(primeiraOpcao);
        }

    </script>

</body>
</html>
