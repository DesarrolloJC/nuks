<?php
include_once '../php/header.php';
$FILE = './model/weus.html';
$DATA = file_get_contents($FILE);
?>

<div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12 document-editor">
                <form action="./controller/update.php" method="POST" data-confirmar="false">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Sobre nosotros</strong>
                            <button type="submit" class="btn btn-primary btn-sm float-right">
                                <i class="fa fa-save "></i>&nbsp;&nbsp;Aceptar
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                    <textarea name="contenido" id="contenido" class="ckeditor"
                                              required><?= $DATA ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= $URLBASE ?>assets/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="./js/index.js"></script>

<?php include_once '../php/footer.php' ?>
