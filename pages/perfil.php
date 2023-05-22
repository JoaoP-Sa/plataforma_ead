<?php
include("lib/conexao.php");
include("lib/enviarArquivo.php");
include('lib/protect.php');
protect(0);

$id = intval($_SESSION['usuario']);

if(isset($_POST['enviar'])) {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $rsenha = $_POST['rsenha'];
    
    $erro = array();
    if(empty($nome))
        $erro[] = "Preencha o nome";

    if(empty($email))
        $erro[] = "Preencha o e-mail";

    if($rsenha != $senha)
        $erro[] = "As senhas não batem";

    if(count($erro) == 0) {
        $sql_code = "UPDATE usuarios 
                     SET nome = ?, email = ?
                     WHERE id = $id";

        $insert_values = [$nome, $email];

        if(!empty($senha)) {
            $senha = password_hash($senha, PASSWORD_DEFAULT);

            $sql_code = "UPDATE usuarios 
                         SET nome = ?, email = ?, senha = ?
                         WHERE id = $id";

            $insert_values = [$nome, $email, $senha];
        }

        $sql = $pdo->prepare($sql_code);
        $sql->execute($insert_values) or die($pdo->errorInfo());

        die("<script>location.href=\"index.php\";</script>");

    }
}

$sql_query = $pdo->query("SELECT * FROM usuarios WHERE id = '$id'") or die($pdo->errorInfo());
$usuario = $sql_query->fetch();

?>
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-6">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Informações de Usuário</h4>
                    <span>Preencha as informações e clique em Salvar</span>
                </div>  
            </div>
        </div>
        <div class="col-lg-6">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="index.php">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="index.php?p=gerenciar_usuarios">
                            Gerenciar Usuário
                        </a>
                    </li>
                    <li class="breadcrumb-item">Cadastrar Usuário</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <?php if(isset($erro) && count($erro) > 0) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach($erro as $e) { echo "$e<br>"; } ?>
                </div>
                <?php
            }
            ?>
            
            <div class="card">
                <div class="card-header">
                    <h5>Informações de Usuário</h5>
                </div>
                <div class="card-block">
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Nome</label>
                                    <input type="text" value="<?php echo $usuario['nome']; ?>" name="nome" class="form-control">
                                </div>  
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">E-mail</label>
                                    <input type="text" value="<?php echo $usuario['email']; ?>" name="email" class="form-control">
                                </div>  
                            </div>

                            <div class="col-lg-4"></div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Senha</label>
                                    <input type="text" name="senha" class="form-control">
                                </div>  
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Repita a senha</label>
                                    <input type="text" name="rsenha" class="form-control">
                                </div>  
                            </div>

                           
                            <div class="col-lg-12">
                                <a href="index.php?p=gerenciar_usuarios" class="btn btn-primary btn-round"><i class="ti-arrow-left"></i> Voltar</a>
                                <button type="submit" name="enviar" value="1" class="btn btn-success btn-round float-right"><i class="ti-save"></i> Salvar</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>