<?php
// Incluir a conexão com o banco de dados
require_once 'includes/conexao.php';

// Buscar os produtos cadastrados no banco de dados (limitados a 8 para a home)
$sql = "SELECT * FROM produtos ORDER BY id DESC LIMIT 8";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janine Comércios - Início</title>
    
    <!-- Google Fonts (Inter & DM Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Seus Arquivos CSS -->
    <link rel="stylesheet" href="css/header_cliente.css">
    <link rel="stylesheet" href="css/footer_cliente.css">
    <link rel="stylesheet" href="css/tela_inicial.css">
</head>
<body>

    <!-- Cabeçalho do Cliente -->
    <?php include_once 'includes/header_cliente.php'; ?>

    <main class="container-home">
        
        <!-- Banner / Hero Principal -->
        <section class="hero-banner">
            <div class="hero-conteudo">
                <h1>Descubra sua melhor versão</h1>
                <p>As melhores novidades em cosméticos e cuidados pessoais com um atendimento acolhedor.</p>
            </div>
        </section>

        <!-- Vitrine de Produtos Dinâmica -->
        <section class="secao-vitrine">
            <div class="cabecalho-secao">
                <h2 class="secao-titulo">Produtos em Destaque</h2>
                <span class="secao-subtitulo">Selecionados especialmente para o seu cuidado diário</span>
            </div>

            <div class="grid-produtos">
                <?php
                if ($result && $result->num_rows > 0) {
                    while($produto = $result->fetch_assoc()) {
                        $nome = htmlspecialchars($produto['nome'] ?? 'Produto sem nome');
                        $preco = number_format($produto['preco'] ?? 0, 2, ',', '.');
                        $imagem = htmlspecialchars($produto['imagem'] ?? 'img/placeholder.png');
                ?>
                    <div class="card-produto">
                        <div class="card-imagem">
                            <img src="<?php echo $imagem; ?>" alt="<?php echo $nome; ?>">
                        </div>
                        <div class="card-corpo">
                            <h3><?php echo $nome; ?></h3>
                            <div class="preco">R$ <?php echo $preco; ?></div>
                            <a href="#" class="btn-comprar">Ver Detalhes</a>
                        </div>
                    </div>
                <?php 
                    }
                } else {
                ?>
                    <div class="sem-produtos">
                        <p>Nenhum produto cadastrado no momento. Visite o painel administrativo para adicionar novidades!</p>
                    </div>
                <?php } ?>
            </div>
        </section>

    </main>

    <!-- Rodapé do Cliente -->
    <?php include_once 'includes/footer_cliente.php'; ?>

</body>
</html>
