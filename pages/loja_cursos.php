<?php

include('lib/protect.php');
protect(0);

if(!isset($_SESSION))
    session_start();

$erro = false;
if(isset($_POST['adquirir'])) {

    // verificar se o usuario possui creditos para compra-lo
    $id_user = $_SESSION['usuario'];
    $sql_query_creditos = $pdo->prepare("SELECT creditos FROM usuarios WHERE id = ?") or die($pdo->errorInfo());
    $sql_query_creditos->execute([$id_user]);

    $usuario = $sql_query_creditos->fetch();

    $creditos_do_usuario = $usuario['creditos'];

    $id_curso = intval($_POST['adquirir']);
    $sql_query_curso = $pdo->prepare("SELECT preco FROM cursos WHERE id = ?") or die($pdo->errorInfo());
    $sql_query_curso->execute([$id_curso]);

    $curso = $sql_query_curso->fetch();

    $preco_do_curso = $curso['preco'];

    if($preco_do_curso > $creditos_do_usuario) {
        $erro = "Você não possui créditos para adquirir este curso.";
    } else {
        $query = $pdo->prepare("INSERT INTO relatorio (id_usuario, id_curso, valor, data_compra) 
                                VALUES(?, ?, ?, NOW())") or die($pdo->errorInfo());

        $query->execute([$id_user, $id_curso, $preco_do_curso]);

        $novo_credito = $creditos_do_usuario - $preco_do_curso;

        $query = $pdo->prepare("UPDATE usuarios SET creditos = ? WHERE id = ?") or die($pdo->errorInfo());
        $query->execute([$novo_credito, $id_user]);
        die("<script>location.href='index.php?p=meus_cursos';</script>");
    }

}


$id_usuario = $_SESSION['usuario'];
$cursos_query = $pdo->query("SELECT * FROM cursos WHERE id NOT IN 
                                (SELECT id_curso FROM relatorio WHERE id_usuario = '$id_usuario')
                            ") or die($pdo->errorInfo());

?>
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Loja de Cursos</h4>
                    <span>Adquira nossos cursos usando o seu crédito</span>
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
                    <li class="breadcrumb-item">Loja de Cursos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page-header end -->

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
        <?php if($erro !== false) {
                                    ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $erro; ?>
            </div>
            <?php
        }
        ?>
        </div>
        <?php while($curso = $cursos_query->fetch()) { ?>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo $curso['titulo']; ?></h5>
                </div>
                <div class="card-block">
                    <img src="<?php echo $curso['imagem']; ?>" class="img-fluid mb-3" alt="">
                    <p>
                    <?php echo $curso['descricao_curta']; ?>
                    </p>
                    <form action="" method="post">
                        <button type="submit" name="adquirir" value="<?php echo $curso['id']; ?>" class="btn form-control btn-out-dashed btn-success btn-square">Adquirir por R$ <?php echo number_format($curso['preco'], 2, ',', '.'); ?></button>   
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
        
    </div>
</div>