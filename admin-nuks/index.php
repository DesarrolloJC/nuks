<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/css/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/css/stylesLogin.css">
    <title>Panel de Administrador</title>
</head>
<body>
<div class="container-sm text-center">
<form action="" method="POST" id="login-frm" class="login flex-container">
    <br>
    <h2 class="text-center m-3">Iniciar sesión</h2>
    <br>
    <input type="email" name="email" data-regla="correo" data-msg="Revise su correo"
           placeholder="ejemplo@email.com">

    <input type="password" name="pass" data-regla="password" data-msg="Revise su correo" placeholder="******">

    <input type="submit" class="button-submit" value="Iniciar Sesi&oacute;n">
    
</form>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script
        src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
        crossorigin="anonymous"></script>

<script src="../assets/js/main.js"></script>
<script src="../assets/js/forms.js"></script>
<script type="text/javascript">
    localStorage.clear()
    jQuery('#login-frm').submit(function (event) {
        event.preventDefault();
        var datos = new FormData(jQuery(this)[0]);
        jQuery.ajax({
            url: './php/login.user.php',
            type: 'POST',
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                var r = JSON.parse(response)

                if (r.success) {
                    alerta('Acceso correcto!', 'Bienvenido al administrador de contenido',
                        'success', 'Aceptar');
                    setTimeout(function () {
                        switch (r.role) {
                            case "3":
                            case "4":
                                location.href = './mng-admin/';
                                break;
                            default:
                                location.href = './mng-home/';
                                break;
                        }
                    }, 2500);
                } else alerta('Error!', 'Datos incorrectos revise el usuario y contraseña', 'error',
                    'OK');
            },
            error: function () {
                alerta('Error! No se pudo conectar al servidor, inténtelo más tarde', '', 'warning',
                    'OK');
            }
        })
    })
</script>
</body>

</html>
