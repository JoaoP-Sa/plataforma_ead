<?php

include("lib/conexao.php");
include('lib/protect.php');
protect(1);

$id = intval($_GET['id']);
$pdo->query("DELETE FROM usuarios WHERE id = '$id'") or die($pdo->errorInfo());

die("<script>location.href=\"index.php?p=gerenciar_usuarios\";</script>");