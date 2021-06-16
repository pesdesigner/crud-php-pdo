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

$query_usuarios = "SELECT id, nome, email FROM usuarios WHERE id = $id LIMIT 1";
$result_usuarios = $conn->prepare($query_usuarios);
$result_usuarios->execute();

if (($result_usuarios) and ($result_usuarios->rowCount() != 0)) {
    $row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC);
    // var_dump($row_usuario);
} else {
    $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: Nenhum usuário encontrado!</p>";
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
    <title>CRUD PHP PDO - Editar</title>
</head>

<body>
    <a href="index.php">Listar</a>
    <a href="cadastrar.php">Cadastrar</a>
    <hr>
    <h1>Editar dados</h1>

    <?php
    // receber dados
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['EditUsuario'])) {
        // var_dump($dados);
        $empty_input = false;

        $dados = array_map('trim', $dados);
        if (in_array("", $dados)) {
            $empty_input = true;
            echo "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: Preencha todos os campos!</p>";
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $empty_input = true;
            echo "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: E-mail inválido!</p>";
        }

        if (!$empty_input) {
            $query_usuario =  "UPDATE usuarios SET nome=:nome, email=:email WHERE id=:id";
            $edit_usuario = $conn->prepare($query_usuario);
            $edit_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':id', $id, PDO::PARAM_INT);
            if ($edit_usuario->execute()) {
                $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: green; padding: 5px;'>Usuário editado!</p>";
                header("Location: index.php");
            } else {
                echo "<p 'style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: edição inválida!</p>";
            }
        }
    }
    ?>

    <form name="edit-usuario" method="POST" action="">
        <label>Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome completo" value="<?php
                                                                                    if (isset($dados['nome'])) {
                                                                                        echo $dados['nome'];
                                                                                    } elseif (isset($row_usuario['nome'])) {
                                                                                        echo $row_usuario['nome'];
                                                                                    }

                                                                                    ?>">
        <br><br>

        <label for="">Email: </label>
        <input type="email" name="email" id="email" placeholder="Seu melhor e-mail" value="<?php
                                                                                            if (isset($row_usuario['email'])) {
                                                                                                echo $row_usuario['email'];
                                                                                            } elseif (isset($dados['email'])) {
                                                                                                echo $dados['email'];
                                                                                            }

                                                                                            ?>">
        <br><br>

        <input type="submit" value="Salvar" name="EditUsuario">
    </form>

</body>

</html>