<?php include_once '../php/header.php' ?>
<div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">SEO de pagina</strong>
                        <div class="mb-2">
                            <div class="btn btn-sm btn-warning float-right" id="sitemapGen">Generar SiteMap</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12 col-md-6"></div>
                                <div class="col-sm-12 col-md-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">

                                    <table id="tblSEO"
                                           class="table table-bordered table-hover dataTable dtr-inline nowrap"
                                           style="width:100%" aria-describedby="example2_info">
                                        <thead>
                                        <tr>
                                            <th>Descripci&oacute;n</th>
                                            <th>Palabras Clave</th>
                                            <th>Editar</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .animated -->
</div><!-- .content -->


<!-- Modal de editar SEO -->
<div class="modal fade" id="EdSEO" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Editar informaci&oacute;n del usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/update.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edus" value="">
                    <div class="form-group">
                        <label class="control-label mb-1">Keywords</label>
                        <textarea name="keywords" id="keywords" type="text" class="form-control"
                                  placeholder="Usuario" rows="5" data-msg="Revise el nombre del usuario."
                                  required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Descripci&oacute;n de la pagina</label>
                        <span aria-hidden="true">(Texto recomendable de 140 a 160 caracteres)</span>
                        <textarea name="description" id="description" type="text" class="form-control" rows="5"
                                  data-msg="Revise el nombre del usuario." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="./js/index.js"></script>

<?php include_once '../php/footer.php' ?>
