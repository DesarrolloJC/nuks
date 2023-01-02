<?php
include_once '../php/header.php';
?>

<div class="content mt-3">
    <div class="animated fadeIn mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Usuarios</strong>
                        <button type="button" class="btn btn-primary btn-sm float-right addContact" data-toggle="modal"
                            data-target="#NwUs">
                            <i class="fas fa-user-plus"></i> Nuevo usuario
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
                                    <table id="tblusers"
                                        class="table table-bordered table-hover dataTable dtr-inline nowrap"
                                        style="width:100%" aria-describedby="example2_info">
                                        <thead>
                                            <tr>
                                                <th>Usuario</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>Role</th>
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
<div class="modal fade" id="NwUs" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Agregar nuevo usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/insert.php" method="POST" autocomplete="off">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label mb-1">Nombre</label>
                        <input name="name" type="text" class="form-control" placeholder="Nombre" data-regla="numletras"
                            data-msg="Revise el nombre del usuario." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Apellido</label>
                        <input name="lastname" type="text" class="form-control" placeholder="Apellido"
                            data-regla="numletras" data-msg="Revise el apellido del usuario." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Correo Electrónico</label>
                        <input name="email" type="email" class="form-control" placeholder="Correo" data-regla="correo"
                            data-msg="Revise la dirección de correo electrónico." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Contraseña</label>
                        <input name="pass" type="password" class="form-control" placeholder="***************"
                            data-regla="password"
                            data-msg="La contraseña debe medir al menos 8 caracteres y contener al menos: un número, una mayúscula, una minúscula y un símbolo @$!%*?&#=+-"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="control-label mb-1">Roles</label>
                        <select name="role" id="role" class="form-control">
                        </select>
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
<div class="modal fade" id="EdUs" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Editar informaci&oacute;n del usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/update.php" method="POST" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edus" value="">
                    <div class="form-group">
                        <label class="control-label mb-1">Nombre</label>
                        <input name="name" type="text" class="form-control" placeholder="Usuario"
                            data-msg="Revise el nombre del usuario." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Apellido</label>
                        <input name="apellido" type="text" class="form-control" placeholder="Apellido"
                            data-msg="Revise el nombre del usuario." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Correo Electr&oacute;nico</label>
                        <input name="sesion" type="email" class="form-control" placeholder="Nombre"
                            data-msg="Revise la dirección de correo electrónico." required>
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Contrase&ntilde;a</label>
                        <input name="contra" type="password" class="form-control"
                            data-msg="La contraseña debe medir al menos 8 caracteres y contener al menos: un número, una mayúscula, una minúscula y un símbolo @$!%*?&#=+-">
                    </div>
                    <div class="form-group">
                        <label class="control-label mb-1">Roles</label>
                        <select name="role" id="role" class="form-control">
                        </select>
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