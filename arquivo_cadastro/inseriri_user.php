
<?php
require_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome = $_POST['nome_completo'] ?? '';
    $email = $_POST['email'] ?? '';
    $tel = $_POST['telefone'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $data = date("Y-m-d H:i:s");

    $cep = $_POST['cep'] ?? '';
    $uf = $_POST['uf'] ?? '';
    $rua = $_POST['rua'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $num = $_POST['num'] ?? '';
    $comp = $_POST['comp'] ?? '';

    $sql = "INSERT INTO clientes(nome_cliente, email, senha, telefone, cpf)
            VALUES ('$nome', '$email', '$senha', '$tel', '$cpf')";

    $stmt = $con->prepare($sql);

    if ($stmt->execute()) {
        $id_cliente = $con->insert_id;

        $sql_ende = "INSERT INTO endereco(id_cliente, cep, UF, cidade, bairro, rua, numero, complemento)
                     VALUES ('$id_cliente', '$cep', '$uf', '$cidade', '$bairro', '$rua', '$num', '$comp')";

        $stmt = $con->prepare($sql_ende);

        if ($stmt->execute()) {
            echo "Cliente e endereço cadastrados com sucesso!";
        } else {
            echo "Erro ao cadastrar endereço.";
        }
    } else {
        echo "Erro ao cadastrar cliente.";
    }
}
?>
