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
    $query_del_usuario = "DELETE FROM usuarios WHERE id = $id";
    $apagar_usuario = $conn->prepare($query_del_usuario);

    if ($apagar_usuario->execute()) {
        $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: green; padding: 5px;'>Usuário excluído com sucesso!</p>";
        header("Location: index.php");
    } else {
        $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: Usuário não foi excluído!</p>";
        header("Location: index.php");
    }
} else {
    $_SESSION['msg_success'] = "<p style='width: 30%; text-align: center; color: white; background: red; padding: 5px;'>Erro: Nenhum usuário encontrado!</p>";
    header("Location: index.php");
    exit();
}
