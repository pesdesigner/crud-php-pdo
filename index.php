<?php
session_start();
include_once './conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP PDO - Home</title>
</head>

<body>
    <a href="index.php">Listar</a>
    <a href="cadastrar.php">Cadastrar</a>
    <hr>
    <h1>Listar</h1>

    <?php
    if (isset($_SESSION['msg_success'])) {
        echo $_SESSION['msg_success'];
        unset($_SESSION['msg_success']);
    }

    // receber n° da página
    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
    //  var_dump($pagina);

    // setar a qntd de registros por página
    $limite_resultado = 2;

    // calcular o inicio da visualização
    $inicio = ($limite_resultado * $pagina) - $limite_resultado;

    // listar
    $query_usuarios = "SELECT id, nome, email FROM usuarios ORDER BY id DESC LIMIT $inicio, $limite_resultado";
    $result_usuarios = $conn->prepare($query_usuarios);
    $result_usuarios->execute();

    if (($result_usuarios) and ($result_usuarios->rowCount() != 0)) {
        while ($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
            // var_dump($row_usuario);
            extract($row_usuario);
            echo "ID: $id <br>";
            echo "Nome: $nome <br>";
            echo "E-mail: $email <br>";
            echo "<hr>";
        }
        // qntd de registros no BD
        $query_qnt_registros = "SELECT COUNT(id) AS num_result FROM usuarios";
        $result_qnt_registros = $conn->prepare($query_qnt_registros);
        $result_qnt_registros->execute();
        $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

        // Quantidade de página
        $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

        // Máximo de link
        $maximo_link = 2;

        echo "<a href='index.php?page=1'>Primeira</a> ";

        for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
            if ($pagina_anterior >= 1) {
                echo "<a href='index.php?page=$pagina_anterior'>$pagina_anterior</a> ";
            }
        }
        echo "<a href='#'>$pagina</a> ";

        for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
            if ($proxima_pagina <= $qnt_pagina) {
                echo "<a href='index.php?page=$proxima_pagina'>$proxima_pagina</a> ";
            }
        }

        echo "<a href='index.php?page=$qnt_pagina'>Última</a> ";
    } else {
        echo "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: Nenhum usuário encontrado!</p>";
    }
    ?>
</body>

</html>