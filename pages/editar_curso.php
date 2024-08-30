<?php
include("lib/conexao.php");
include("lib/enviarArquivo.php");
include('lib/protect.php');
protect(1);

$id = intval($_GET['id']);

if(isset($_POST['enviar'])) {

    $titulo = $_POST['titulo'];
    $descricao_curta = $_POST['descricao_curta'];
    $preco = $_POST['preco'];
    $conteudo = $_POST['conteudo'];
    
    $erro = array();
    if(empty($titulo))
        $erro[] = "Preencha o título";
    
    if(empty($descricao_curta))
        $erro[] = "Preencha a descrição curta";

    if(empty($preco))
        $erro[] = "Preencha o preço";

    if(empty($conteudo))
        $erro[] = "Preencha o conteúdo";

    if(count($erro) == 0) {
        $imagemAlterada = false;
        if (isset($_FILES['imagem']) && isset($_FILES['imagem']['size']) && $_FILES['imagem']['size'] > 0) {
            $deu_certo = enviarArquivo($_FILES['imagem']['error'], $_FILES['imagem']['size'], $_FILES['imagem']['name'], $_FILES['imagem']['tmp_name']);
            $imagemAlterada = true;
        } else {
            $deu_certo = true;
        }

        if($deu_certo !== false) {

            if ($imagemAlterada) {
                $sql_code = "UPDATE cursos SET 
                             titulo = ?,
                             descricao_curta = ?,
                             conteudo = ?,
                             preco = ?,
                             imagem = ?
                             WHERE id = ?";

                $values_to_insert = [$titulo, $descricao_curta, $conteudo, floatval($preco), $deu_certo, $id];
            } else {
                $sql_code = "UPDATE cursos SET 
                             titulo = ?,
                             descricao_curta = ?,
                             conteudo = ?,
                             preco = ?
                             WHERE id = ?";

                $values_to_insert = [$titulo, $descricao_curta, $conteudo, floatval($preco), $id];
            }

            $sql = $pdo->prepare($sql_code);
            $inserido = $sql->execute($values_to_insert);

            if(!$inserido){
                $erro[] = "Falha ao inserir no banco de dados";

                echo $sql_code;
                echo '<pre>'; print_r($values_to_insert); echo '</pre>';
            } else {
                die("<script>location.href=\"index.php?p=gerenciar_cursos\";</script>");
            }

        } else 
            $erro[] = "Falha ao enviar a imagem";

    }
}

$sql_query = $pdo->query("SELECT * FROM cursos WHERE id = '$id'") or die($pdo->errorInfo());
$curso = $sql_query->fetch();

?>
<!-- Page-header start -->
<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-6">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Editar Curso</h4>
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
                        <a href="index.php?p=gerenciar_cursos">
                            Gerenciar Cursos
                        </a>
                    </li>
                    <li class="breadcrumb-item">Cadastrar Curso</li>
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
                    <h5>Formulário de Cadastro</h5>
                </div>
                <div class="card-block">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Título</label>
                                    <input type="text" value="<?php echo $curso['titulo']; ?>" name="titulo" class="form-control">
                                </div>  
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="">Descrição Curta</label>
                                    <input type="text" value="<?php echo $curso['descricao_curta']; ?>" name="descricao_curta" class="form-control">
                                </div>  
                            </div>
                            
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="">Imagem</label>
                                    <input type="file" name="imagem" class="form-control">
                                </div>  
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Preço</label>
                                    <input type="text" value="<?php echo $curso['preco']; ?>" name="preco" class="form-control">
                                </div>  
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Conteúdo</label>
                                    <textarea name="conteudo" rows="10" class="form-control"><?php echo $curso['conteudo']; ?></textarea>
                                </div>  
                            </div>
                            <div class="col-lg-12">
                                <a href="index.php?p=gerenciar_cursos" class="btn btn-primary btn-round"><i class="ti-arrow-left"></i> Voltar</a>
                                <button type="submit" name="enviar" value="1" class="btn btn-success btn-round float-right"><i class="ti-save"></i> Salvar</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>