<?php include_once '../php/header.php' ?>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Slider Home</strong>
                            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                    data-target="#NwSl">
                                <i class="far fa-images"></i> Nuevo Slider
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6"></div>
                                    <div class="col-sm-12 col-md-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="tblSlider"
                                               class="table table-bordered table-hover dataTable dtr-inline nowrap"
                                               style="width:100%" aria-describedby="example2_info">
                                            <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Imagen</th>
                                                <th>URL</th>
                                                <th>Opciones</th>
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

    <!-- Modal de agregar Slider -->
    <div class="modal fade" id="NwSl" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Agregar nuevo Slider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="./controller/insert.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label mb-1">Titulo</label>
                            <input name="name" type="text" class="form-control" placeholder="Titulo" data-regla="texto"
                                   data-msg="Revise el nombre del titulo." required>
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-1">URL</label>
                            <input name="url" type="text" class="form-control" placeholder="www.ejemplo.com/pagina"
                                   data-regla="erb" data-msg="Revise la URL ingresada">
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-1">Imagen</label>
                            <input name="imagen" type="file" required>
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

    <!-- Modal de editar Slider -->
    <div class="modal fade" id="EdSl" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Editar Slide</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="./controller/update.php" method="POST" autocomplete="off" enctype="multipart/form-data" id="editar">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edus" value="">
                        <div class="form-group">
                            <label class="control-label mb-1">Titulo</label>
                            <input name="name" type="text" class="form-control" placeholder="Usuario"
                                   data-msg="Revise el nombre del usuario." required>
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-1">URL</label>
                            <input name="url" type="text" class="form-control" placeholder="Apellido"
                                   data-msg="Revise el nombre del usuario." required>
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-1">Imagen</label>
                            <input name="imagen" type="file" required>
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
