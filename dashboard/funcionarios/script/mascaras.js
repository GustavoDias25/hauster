// Máscaras simples para CPF e telefone (o PHP guarda só os números)
function somenteNumeros(texto) {
    return texto.replace(/\D/g, '');
}

document.getElementById('cpf').addEventListener('input', function () {
    let n = somenteNumeros(this.value).slice(0, 11);
    n = n.replace(/(\d{3})(\d)/, '$1.$2');
    n = n.replace(/(\d{3})(\d)/, '$1.$2');
    n = n.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    this.value = n;
});

document.getElementById('telefone').addEventListener('input', function () {
    let n = somenteNumeros(this.value).slice(0, 11);
    n = n.replace(/^(\d{2})(\d)/, '($1) $2');
    // celular (11 dígitos) = 5+4, fixo (10 dígitos) = 4+4
    if (n.length > 13) {
        n = n.replace(/(\d{5})(\d{1,4})$/, '$1-$2');
    } else {
        n = n.replace(/(\d{4})(\d{1,4})$/, '$1-$2');
    }
    this.value = n;
});
