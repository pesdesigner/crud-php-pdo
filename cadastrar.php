<?php
session_start();
ob_start();
include_once './conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP PDO - Cadastrar</title>
</head>

<body>
    <a href="index.php">Listar</a>
    <a href="cadastrar.php">Cadastrar</a>
    <hr>
    <h1>Cadastrar</h1>
    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['CadUsuario'])) {
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
            $query_usuario =  "INSERT INTO usuarios (nome, email) VALUES (:nome, :email) ";
            $cad_usuario = $conn->prepare($query_usuario);
            $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            $cad_usuario->execute();
            if ($cad_usuario->rowCount()) {
                unset($dados);
                $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: green; padding: 5px;'>Usuário cadastrado!</p>";
                header("Location: index.php");
            } else {
                echo "<p 'style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: cadastro inválido!</p>";
            }
        }
    }
    ?>
    <form name="cad-usuario" method="POST" action="">
        <label name="cad-usuario" for="">Nome:</label>
        <input type="text" name="nome" id="nome" placeholder="Nome completo" value="<?php
                                                                                    if (isset($dados['nome'])) {
                                                                                        echo $dados['nome'];
                                                                                    } ?>">
        <br><br>

        <label for="">Email: </label>
        <input type="email" name="email" id="email" placeholder="Seu melhor e-mail" value="<?php
                                                                                            if (isset($dados['email'])) {
                                                                                                echo $dados['email'];
                                                                                            } ?>">
        <br><br>

        <input type="submit" value="Cadastrar" name="CadUsuario">
    </form>
</body>

</html>