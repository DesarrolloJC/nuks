function alerta(titulo, texto, icono, boton, time = 30000) {
    Swal.fire({
        title: titulo, html: texto, icon: icono, confirmButtonText: boton, timer: time
        // title: titulo, html: texto, icon: icono, confirmButtonText: boton
    })
}

$(document).ready(function () {
    setActiveStatus();

    //   var URLactual = window.location;
    //   alert(URLactual);

});

function setActiveStatus() {
    //Obtenemos el path completo
    var pathname = window.location.pathname;
    // console.log('PATHNAME FULL: ' + pathname);
    //Lo dividimos en un arreglo
    let PATHNAME = pathname.split('/');
    // console.log('pathname[1]: ' + PATHNAME[1]);
    // console.log('pathname[2]: ' + PATHNAME[2]);
    // console.log('pathname[3]: ' + PATHNAME[3]);
    let url = PATHNAME[3];
    // $("#".PATHNAME[3]).addClass('active');
    // console.log(url)
    // $("#" + url).addClass('active');
    if (document.getElementById(url)) {
        document.getElementById(url).classList.add("active")
    }

}
