function validateNoEmpty(key, value) {
    var valor = value;
    if (valor ==""){
        return "Campo " + key + " vacio";
    }
    
    return "";
}
function validateNoNegative(key,value) {
    var valor = value;
    if (valor <= 0){
        return  "Campo " + key + " negativo o nulo";
    }
    
    return "";
}
