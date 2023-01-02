<?php include "../php/header.php";

require_once "../php/connection.class.php";
require_once "../php/table.class.php";
require_once "../mng-supplier/model/supplier.class.php";
$DB = new DBConnection;
$suppliers = new Supplier($DB);?>

<script>
    const id = <?=$_GET['id']?>;
</script>

<div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <form action="./controller/update.php" method="POST" data-confirmar="false" autocomplete="off">
                        <div class="card-header">
                            <strong class="card-title">Editar Producto</strong>
                            <!--p><?= $URLBASE ?></!--p-->
                            <button type="submit" class="btn btn-primary btn-sm float-right p-1 ml-2">
                                <i class="fa fa-save"></i>&nbsp;Aceptar
                            </button>
                            <a href="./" class="btn btn-secondary btn-sm float-right p-1">
                                <i class="fa fa-times"></i>&nbsp;Cancelar
                            </a>
                        </div>
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

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <input type="hidden" name="id" required>
                                        <input type="hidden" name="urlprod" required>
                                        <label class="required">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" data-regla="numletras"
                                               data-msg="Ingresar un nombre de producto válido" required>

                                    </div>


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
                                        <input type="text" class="form-control" disabled name="priceClient"
                                               id="priceClient">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Precio Distribuidor 1 $</span>
                                        </div>
                                        <input type="text" class="form-control" disabled name="priceProv1"
                                               id="priceProv1">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Precio Distribuidor 2 $</span>
                                        </div>
                                        <input type="text" class="form-control" disabled name="priceProv2"
                                               id="priceProv2">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Precio Distribuidor 3 $</span>
                                        </div>
                                        <input type="text" class="form-control" disabled name="priceProv3"
                                               id="priceProv3">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Precio general $</span>
                                        </div>
                                        <input type="text" class="form-control" disabled name="priceGeneral"
                                               id="priceGeneral">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="required">Descripci&oacute;n</label>
                                        <textarea class="ckeditor" name="description2" id="description2"
                                                  required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Imagen principal del producto</label>&nbsp;
                                        <label class="badge badge-success">Imagen</label>
                                        <label class="badge badge-warning">250 x 200 px</label>
                                        <input type="file" name="img[]" multiple>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Url del proveedor</label>
                                        <input type="text" class="form-control" name="prov_url" id="prov_url">
                                    </div>

                                    <div class="form-group">
                                        <label class="required">Colores</label>
                                        <input type="text" class="form-control" name="colors" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="required">Categor&iacute;a</label>
                                        <select class="form-control" name="categoria[]" required></select>
                                    </div>

                                    <div class="form-group mt-4 mb-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="status"
                                                   name="status" value="">
                                            <label class="custom-control-label" for="status">Estado del
                                                producto</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="required">Informaci&oacute;n del producto</label>
                                        <textarea name="contenidoEdit" id="contenidoEdit" class="ckeditor"
                                                  required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Slider de imágenes</label>&nbsp;
                                        <span class="badge badge-warning">720px x 720px</span>
                                        <input type="file" name="slider[]" multiple>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div><!-- .animated -->
</div><!-- .content -->

<script src="<?= $URLBASE ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $URLBASE ?>assets/js/main.js"></script>
<script src="<?= $URLBASE ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= $URLBASE ?>assets/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="./js/edit.js"></script>

<?php include "../php/footer.php" ?>
