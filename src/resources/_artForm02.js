// ZONA DE ENVO DE FORMULARIO MEDIANTE AJAX

// 1) Recogemos los elementos HTML necesarios
const formulario = document.querySelector("#idFormAjax")
const botonEnviarAjax = document.querySelector("#btnEnviarAjax")
const inputs = formulario.querySelectorAll("input, textarea")

const modal_envio_ok_msg = document.querySelector("#modal_envio_ok_msg")
const errorForm02 = document.querySelector("#errorForm02")
const loader = document.getElementById("moduleLoader01")

const modalEnvioOk = document.querySelector("#modal_envio_ok")
const volverAlFormulario = document.querySelector("#volver_al_formulario")

// OBJETIVOS
function generarCaptcha(){
    // RECOJO EN CONSTANTES LOS ELEMENTOS A LOS QUE TENDRÉ QUE INSERTAR LOS NÚMEROS RANDOM Y TAMBIÉN EL INPUT
    const num1 = document.getElementById("num1ajax")
    const num2 = document.getElementById("num2ajax")
    const operador = document.getElementById("operadorajax")
    const respSystem = document.getElementById("respSystemajax")
    // CALCULAR DOS NÚMEROS RANDOM DEL 0 AL 10
    let valorNum1 = (Number)(Math.floor(Math.random()*10))
    let valorNum2 = (Number)(Math.floor(Math.random()*10))

    // EJERCICIO EXTRA: MÁXIMO 4 CON SWITCH CASE
    let valorNum3 = (Number)(Math.floor(Math.random()*3))
    let resultado
    switch (valorNum3){
        case 0:
            resultado = valorNum1 + valorNum2
            operador.innerText = "+"
            break
        case 1:
            resultado = valorNum1 - valorNum2
            operador.innerText = "-"
            break
        case 2:
            resultado = valorNum1 * valorNum2
            operador.innerText = "x"
            break
        default:
            console.log("No está dentro")
            break
    }

    // HACER SU SUMA
    //resultado = valorNum1 + valorNum2
    // INSERTARLOS COMO TEXTOS DENTRO DE DOS SPAN HTML
    num1.innerText = valorNum1
    num2.innerText = valorNum2
    // EL RESULTADO DE SU SUMA, INSERTARLO COMO VALUE DENTRO DEL INPUT OCULTO
    respSystem.value = resultado
}

volverAlFormulario.addEventListener("click", function(){
    modalEnvioOk.style.display = "none"
    formulario.style.display = ""
    modal_envio_ok_msg.innerText = ""
    errorForm02.innerText = ""
    // habilitamos de nuevo los inputs
    botonEnviarAjax.style.pointerEvents = "initial"
    formulario.style.filter = "initial"
    inputs.forEach(input => {
        input.disabled=false
        if(input.type!="submit"){
            if(input.type=="checkbox"){
                input.checked = false
            }else{
                input.value = ""
            }
        }
    })
    generarCaptcha()
})

// Evento de escucha de que se haga submit del form
formulario.addEventListener("submit", function(evento){
    // Prevenimos el comportamiento por defecto del formulario, que es recargar la página
    evento.preventDefault()
    // Recoger todas las claves valor del formulario
    const camposFormulario = new FormData(document.forms.namedItem("idFormAjax"))
    
    //OPCIÓN A XMLHTTPREQUEST (AJAX)
    // construimos el objeto de clase xmlhttprequest
    const xmlhttp = new XMLHttpRequest()
    xmlhttp.onload = function(){
        //esperamos y recibimos respuesta
        if(xmlhttp.status == 200){
            // Recibo respuesta del servidor
            
            console.log(xmlhttp.responseText)
            let jsonRecibido = xmlhttp.responseText
            let ArrayJson = JSON.parse(jsonRecibido)
            let mensaje = ArrayJson.mensaje
            let fallo = ArrayJson.fallo
            let campo = ArrayJson.campo

            // Quitamos el loader
            loader.style.display = "none"
            //muestro fallo si es fallo o muestro el gracias
            if(fallo == true){
                errorForm02.innerText = mensaje
                // habilitamos de nuevo los inputs
                botonEnviarAjax.style.pointerEvents = "initial"
                formulario.style.filter = "initial"
                inputs.forEach(input => {
                    input.disabled=false
                })
            }else{
                //Cuando no hay fallo
                formulario.style.display = "none"
                modal_envio_ok_msg.innerText = mensaje
                modalEnvioOk.style.display = "initial"
            }
        }
    }
    xmlhttp.open("POST","/App/artForm02.php", true)// true para que sea asíncrono
    xmlhttp.send(camposFormulario)

    //Aquí podría ejecutar código simultáneo al envío
    // muestro loader
    loader.style.display = "initial"
    formulario.style.filter = "blur(2px)"
    botonEnviarAjax.style.pointerEvents = "none"

    //Iteramos el array "inputs" donde dentro están todos los input y textarea del elemento formulario.
    inputs.forEach((input) => {
        input.disabled=true
    })

    //OPCIÓN B FETCH

    // fetch("/App/artForm02.php", { method: "POST", body: camposFormulario })
    // .then(async (res) => {
    //     if (!res.ok) throw new Error(`HTTP ${res.status}`)
 
    //     const texto = await res.text()
    //     console.log(texto)              // equivalente a xmlhttp.responseText
 
    //     return JSON.parse(texto)        // equivalente a JSON.parse(xmlhttp.responseText)
    // })
    // .then(({ mensaje, fallo, campo }) => {
 
    //     // quitaría el loader
 
    //     // lógica una vez se recibe con éxito la respuesta y los valores
    //     if (fallo === true) {
    //         errorForm02.innerText = mensaje
    //     } else {
    //         formulario.style.display = "none"
    //         h3Form02.innerText = mensaje
    //     }
    // })
    // .catch((err) => {
 
    //     // quitaría el loader
 
    //     console.error(err)
    //     errorForm02.innerText = "Ha ocurrido un error al enviar el formulario."
    // })
 
    // // loader
})

generarCaptcha()
