<?php

include('lib/conexao.php');
include('lib/protect.php');
protect(1);

$sql_usuarios = "SELECT * FROM usuarios";
$sql_query = $pdo->query($sql_usuarios) or die($pdo->errorInfo());
$num_usuarios = $sql_query->rowCount();

?>
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Gerenciar Usuários</h4>
                    <span>Administre os usuários cadastrados no sistema</span>
                </div>  
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="index.php">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Gerenciar Usuários</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Todos os Usuários</h5>
                    <span><a href="index.php?p=cadastrar_usuario">Clique aqui</a> para cadastrar um usuário</span>
                </div>
                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Créditos</th>
                                    <th>Data de Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($num_usuarios == 0) { ?>
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum usuário foi cadastrado</td>
                                </tr>
                                <?php } else {
                                    
                                    while ($usuario = $sql_query->fetch()) {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $usuario['id']; ?></th>
                                            <td><?php echo $usuario['nome']; ?></td>
                                            <td><?php echo $usuario['email']; ?></td>
                                            <td>R$ <?php echo number_format($usuario['creditos'], 2, ',', '.'); ?></td>
                                            <td><a href="index.php?p=editar_usuario&id=<?php echo $usuario['id']; ?>">editar</a> 
                                              | <a href="index.php?p=deletar_usuario&id=<?php echo $usuario['id']; ?>">deletar</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>