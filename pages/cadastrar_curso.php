<?php
include('lib/conexao.php');
include("lib/enviarArquivo.php");
include("lib/protect.php");
protect(1);

if (isset($_POST['enviar'])) {
    $titulo = $_POST['titulo'];
    $descricao_curta = $_POST['descricao_curta'];
    $preco = $_POST['preco'];
    $conteudo = $_POST['conteudo'];

    $erro = [];

    if (empty($titulo)) {
        $erro[] = 'Preencha o título';
    }

    if (empty($descricao_curta)) {
        $erro[] = 'Preencha a descricao curta';
    }

    if (empty($preco)) {
        $erro[] = 'Preencha o preço';
    }

    if (empty($conteudo)) {
        $erro[] = 'Preencha o conteúdo';
    }

    if (!isset($_FILES) || !isset($_FILES['imagem']) || $_FILES['imagem']['size'] == 0) {
        $erro[] = 'Selecione uma imagem para o conteúdo';
    }

    if (count($erro) == 0) {
        $deu_certo = enviarArquivo($_FILES['imagem']['error'], $_FILES['imagem']['size'], $_FILES['imagem']['name'], $_FILES['imagem']['tmp_name']);
        if ($deu_certo !== false) {
            $sql_code = "INSERT INTO cursos (titulo, descricao_curta, conteudo, data_cadastro, preco, imagem) 
                         VALUES (?, ?, ?, NOW(), ?, ?)";

            $sql = $pdo->prepare($sql_code);
            $inserido = $sql->execute([$titulo, $descricao_curta, $conteudo, str_replace(',', '.', $preco), $deu_certo]);
            
            if (!$inserido){
                $erro[] = "Falha ao inserir no banco de dados";
            } else {
                die("<script>location.href=\"index.php?p=gerenciar_cursos\";</script>");
            }
        } else
            $erro[] = "Falha ao enviar a imagem";
    }
}
?>

<div class="page-header card">
    <div class="row align-items-end">
        <div class="col-lg-6">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Cadastrar Curso</h4>
                    <span>Preencha as informações e clique em salvar</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="index.html">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Gerenciar Curso</a>
                    </li>
                    <li class="breadcrumb-item">Cadastrar Curso</a>
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
            <?php if (isset($erro) && count($erro) > 0) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($erro as $e) {
                        echo "$e<br>";
                    } ?>
                </div>
            <?php } ?>

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
                                    <input type="text" name="titulo" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="">Descrição Curta</label>
                                    <input type="text" name="descricao_curta" class="form-control">
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
                                    <input type="text" name="preco" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Conteúdo</label>
                                    <textarea name="conteudo" rows="10" class="form-control"></textarea>
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