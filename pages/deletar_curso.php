<?php

include("lib/conexao.php");
include('lib/protect.php');
protect(1);
$id = intval($_GET['id']);

$mysql_query = $pdo->query("SELECT imagem FROM cursos WHERE id = '$id'") or die($pdo->errorInfo());
$curso = $mysql_query->fetch();

if(unlink($curso['imagem'])) {
    $pdo->query("DELETE FROM cursos WHERE id = '$id'") or die($pdo->errorInfo());
}

die("<script>location.href=\"index.php?p=gerenciar_cursos\";</script>");