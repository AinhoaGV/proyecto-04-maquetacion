// OBJETIVOS
// RECOJO EN CONSTANTES LOS ELEMENTOS A LOS QUE TENDRÉ QUE INSERTAR LOS NÚMEROS RANDOM Y TAMBIÉN EL INPUT
const idForm = document.querySelector("#idForm")

if(idForm){
    const num1 = document.getElementById("num1")
    const num2 = document.getElementById("num2")
    const operador = document.getElementById("operador")
    const respSystem = document.getElementById("respSystem")
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