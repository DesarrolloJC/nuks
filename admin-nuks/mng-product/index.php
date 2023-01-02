<?php
include_once '../php/header.php';

require_once "../php/connection.class.php";
require_once "../php/table.class.php";
require_once "../mng-supplier/model/supplier.class.php";
$DB = new DBConnection;
$suppliers = new Supplier($DB);
?>

<div class="content mt-3 pb-5">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Productos</strong>
                        <?php if ($user_role == 4) { ?>
                            <div class="btn btn-warning btn-sm float-right p-1 ml-2" id="fixProds">
                                <i class="fas fa-bug"></i> Solucionar Problemas con Productos
                            </div><?php } ?>
                        <button type="button" class="btn btn-primary btn-sm float-right p-1 ml-2"
                                data-toggle="modal"
                                data-target="#UpdPro">
                            <i class="fas fa-sync-alt"></i> Actualizar productos por proveedor
                        </button>
                        <button type="button" class="btn btn-primary btn-sm float-right p-1 ml-2" data-toggle="modal"
                                data-target="#NwProPro">
                            <i class="fas fa-cart-plus"></i> Añadir un Nuevo Producto Desde Proveedor
                        </button>

                        <button type="button" class="btn btn-primary btn-sm float-right p-1 ml-2" data-toggle="modal"
                                data-target="#NwPro">
                            <i class="fas fa-cart-plus"></i> Añadir un Nuevo Producto
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
                                    <table id="tbl_Product"
                                           class="table table-bordered table-hover dataTable dtr-inline nowrap"
                                           style="width:100%" aria-describedby="example2_info">
                                        <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Imagen</th>
                                            <th>Código</th>
                                            <th>URL del proveedor</th>
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


<!-- Modal de agregar Producto-->
<div class="modal fade" id="NwPro" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Agregar nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/new.php" method="POST" data-confirmar="false" autocomplete="off"
                  class="mx-4 mt-3">
                <div class="card-body">
                    <div class="form-group">
                        <label class="control-label mb-1">Proveedores</label>
                        <select name="prov" id="provAdd" class="custom-select">
                            <option value="default">Seleccione un proveedor</option>
                            <?php
                            $Suppliers = $suppliers->getAll();
                            foreach ($Suppliers as $supplier) {
                                ?>
                                <option value="<?= $supplier['supplier_code'] ?>"><?= $supplier['supplier_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row mb-5">

                        <div class="col">

                            <div class="form-group">
                                <label class="required">Nombre</label>
                                <input type="hidden" name="id" required>
                                <input type="hidden" name="urlprod" required>
                                <input type="text" class="form-control" id="name" name="nombre" data-regla="numletras"
                                       data-msg="Ingresar un nombre de producto válido" required>
                            </div>

                            <div class="form-group">
                                <label class="required">Url del proveedor</label>
                                <input type="text" class="form-control" id="prov_url">
                                <input type="hidden" class="form-control" name="prov_url" id="prov_urlH">
                            </div>

                           <div class="my-4">
                               <div class="input-group">
                                   <div class="input-group-prepend">
                                       <span class="input-group-text">Precio del producto $</span>
                                   </div>
                                   <input type="text" class="form-control" name="price" id="price">
                                   <div class="input-group-append">
                                       <span class="input-group-text">.00</span>
                                   </div>
                               </div>
                               <div class="input-group">
                                   <div class="input-group-prepend">
                                       <span class="input-group-text">Precio del cliente $</span>
                                   </div>
                                   <input type="text" class="form-control" disabled id="priceClient">
                                   <input type="hidden" class="form-control" name="priceClient" id="priceClientH">
                                   <div class="input-group-append">
                                       <span class="input-group-text">.00</span>
                                   </div>
                               </div>
                               <div class="input-group">
                                   <div class="input-group-prepend">
                                       <span class="input-group-text">Precio Distribuidor 1 $</span>
                                   </div>
                                   <input type="text" class="form-control" disabled id="priceProv1">
                                   <input type="hidden" class="form-control" name="priceProv1" id="priceProv1H">
                                   <div class="input-group-append">
                                       <span class="input-group-text">.00</span>
                                   </div>
                               </div>
                               <div class="input-group">
                                   <div class="input-group-prepend">
                                       <span class="input-group-text">Precio Distribuidor 2 $</span>
                                   </div>
                                   <input type="text" class="form-control" disabled id="priceProv2">
                                   <input type="hidden" class="form-control" name="priceProv2" id="priceProv2H">
                                   <div class="input-group-append">
                                       <span class="input-group-text">.00</span>
                                   </div>
                               </div>
                               <div class="input-group">
                                   <div class="input-group-prepend">
                                       <span class="input-group-text">Precio Distribuidor 3 $</span>
                                   </div>
                                   <input type="text" class="form-control" disabled id="priceProv3">
                                   <input type="hidden" class="form-control" name="priceProv3" id="priceProv3H">
                                   <div class="input-group-append">
                                       <span class="input-group-text">.00</span>
                                   </div>
                               </div>
                               <div class="input-group">
                                   <div class="input-group-prepend">
                                       <span class="input-group-text">Precio general $</span>
                                   </div>
                                   <input type="text" class="form-control" disabled id="priceGeneral">
                                   <input type="hidden" class="form-control" name="priceGeneral" id="priceGeneralH">
                                   <div class="input-group-append">
                                       <span class="input-group-text">.00</span>
                                   </div>
                               </div>
                           </div>

                            <div class="form-group">
                                <label class="required">Descripci&oacute;n</label>
                                <textarea class="ckeditor" name="description" id="description"
                                          required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="required">Slider de imágenes</label>&nbsp;
                                <span class="badge badge-warning">720px x 720px</span>
                                <input type="file" name="slider[]" multiple>
                            </div>

                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="required">C&oacute;digo de producto</label>
                                <input type="text" name="cod" id="cod" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="required">Colores</label>
                                <input type="text" class="form-control" name="colors" required>
                            </div>

                            <div class="form-group">
                                <label class="required">Categor&iacute;a</label>
                                <select class="form-control" name="categoria[]" required></select>
                            </div>

                            <div class="form-group my-5">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status" value="">
                                    <input type="hidden" class="custom-control-input" id="statusH" name="status">
                                    <label class="custom-control-label" for="status">Estado del producto</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="required">Informaci&oacute;n del producto</label>
                                <textarea name="contenido" id="contenido" class="ckeditor" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Imagen principal del producto</label>&nbsp;
                                <label class="badge badge-success">Imagen</label>
                                <label class="badge badge-warning">250 x 200 px</label>
                                <input type="file" name="img" id="img">
                            </div>

                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="container-fluid ">
                            <button type="submit" class="btn btn-primary p-1 ml-2 float-right">
                                <i class="fa fa-save"></i>&nbsp;Guardar
                            </button>
                            <button type="button" class="btn btn-secondary p-1 ml-2 float-right"
                                    data-bs-dismiss="modal">
                                <i
                                        class="fa fa-save"></i>&nbsp;Cancelar
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal de agregar Producto desde el Proveedor -->
<div class="modal fade" id="NwProPro" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Agregar nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label mb-1">Proveedores</label>
                        <select name="prov" id="prov2" class="custom-select">
                            <option value="default">Seleccione un proveedor</option>
                            <?php
                            $Suppliers = $suppliers->getAll();
                            foreach ($Suppliers as $supplier) {
                                ?>
                                <option value="<?= $supplier['supplier_code'] ?>"><?= $supplier['supplier_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group" id="macma">
                        <label class="control-label mb-1">C&oacute;digo de producto</label>
                        <input name="product_code" id="product_code" type="text" class="form-control"
                               placeholder="123456"
                               data-msg="Revise el código de producto." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="aceptar2"><i class="fa fa-save"></i>
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de actualizar Producto -->
<div class="modal fade" id="UpdPro" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Actualizar productos por proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./controller/insert.php" method="GET" autocomplete="off" enctype="multipart/form-data"
                  id="update-prov">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label mb-1">Proveedores</label>
                        <select name="provUpd" id="provUpd" class="custom-select">
                            <option value="default">Seleccione un proveedor</option>
                            <?php
                            $Suppliers = $suppliers->getAll();
                            foreach ($Suppliers as $supplier) {
                                ?>
                                <option value="<?= $supplier['supplier_id'] ?>"><?= $supplier['supplier_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="file-in" class="container mb-2"></div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary " id="aceptarUpd"><i
                                class="fa fa-sync-alt"></i> Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= $URLBASE ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $URLBASE ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= $URLBASE ?>assets/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="./js/new.js"></script>
<script src="./js/index.js"></script>

<?php include_once '../php/footer.php' ?>
