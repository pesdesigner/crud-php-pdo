<?php
session_start();
ob_start();
include_once './conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: blue; padding: 5px;'>Usuário não encontrado!</p>";
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP PDO - Visualizar</title>
</head>

<body>
    <a href="index.php">Listar</a>
    <a href="cadastrar.php">Cadastrar</a>
    <hr>
    <h1>Visualizar</h1>
    <?php
    // listar
    $query_usuarios = "SELECT id, nome, email FROM usuarios WHERE id = $id LIMIT 1";
    $result_usuarios = $conn->prepare($query_usuarios);
    $result_usuarios->execute();

    if (($result_usuarios) and ($result_usuarios->rowCount() != 0)) {
        $row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC);
        // var_dump($row_usuario);
        extract($row_usuario);
        echo "ID: $id <br>";
        echo "Nome: $nome <br>";
        echo "E-mail: $email <br><br>";
        echo "<a href='index.php'>Voltar</a>";
        echo "<hr>";
    } else {
        $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: Nenhum usuário encontrado!</p>";
        header("Location: index.php");
    }

    ?>

</body>

</html>