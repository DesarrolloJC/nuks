<?php
include_once '../php/header.php';
?>

<div class="content mt-3">
    <div class="animated fadeIn mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Proveedores</strong>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                            data-target="#NwSup">
                            <i class="fas fa-user-plus"></i> Nuevo proveedor
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
                                    <table id="tblsuppliers"
                                        class="table table-bordered table-hover dataTable dtr-inline nowrap"
                                        style="width:100%" aria-describedby="example2_info">
                                        <thead>
                                            <tr>
                                                <th>Código del proveedor</th>
                                                <th>Nombre del proveedor</th>
                                                <th>Sitio del proveedor</th>
                                                <th>API del proveedor</th>
                                                <th>URL del producto</th>
                                                <th>URL de la imagen del producto</th>
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

<!-- Modal de agregar usuario -->
<div class="modal fade" id="NwSup" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Agregar nuevo Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/insert.php" method="POST" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edprov" value="">
                    <div class="form-group">
                        <label class="control-label mb-1">Código del proveedor</label>
                        <input name="code" type="text" class="form-control" placeholder="Código"
                            data-msg="Revise el código del proveedor." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Nombre</label>
                        <input name="name" type="text" class="form-control" placeholder="Proveedor"
                            data-msg="Revise el nombre del proveedor." required>
                    </div>

                    <div class="form-group">
                        <label class="control-label mb-1">Sitio Web</label>
                        <input name="site" type="text" class="form-control" placeholder="Web"
                            data-msg="Revise la dirección Web." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">API</label>
                        <input name="api" type="text" class="form-control" data-msg="Revise la dirección de la API">
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

<!-- Modal de editar usuario -->
<div class="modal fade" id="EdSup" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Editar informaci&oacute;n del proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/update.php" method="POST" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edprov" value="">
                    <div class="form-group">
                        <label class="control-label mb-1">Código del proveedor</label>
                        <input name="code" type="text" class="form-control" placeholder="Código"
                            data-msg="Revise el código del proveedor." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Nombre</label>
                        <input name="name" type="text" class="form-control" placeholder="Proveedor"
                            data-msg="Revise el nombre del proveedor." required>
                    </div>

                    <div class="form-group">
                        <label class="control-label mb-1">Sitio Web</label>
                        <input name="site" type="text" class="form-control" placeholder="Web"
                            data-msg="Revise la dirección Web." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">API</label>
                        <input name="api" type="text" class="form-control" data-msg="Revise la dirección de la API">
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

<?php include_once '../php/footer.php'?>
