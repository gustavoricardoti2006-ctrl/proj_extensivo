
const container = document.getElementById("container-variacoes");
const botaoAdicionar = document.getElementById("adicionar-variacao");

let contador = 1;

botaoAdicionar.addEventListener("click", function () {

    const novaVariacao = document.createElement("div");

    novaVariacao.classList.add("variacao");

    novaVariacao.innerHTML = `
        <hr>

        <h4>Variação ${contador + 1}</h4>

        <label>Tamanho</label>
        <input type="text" name="variacoes[${contador}][tamanho]"
            placeholder="Ex.: P, M, G">

        <label>Cor</label>
        <input type="text" name="variacoes[${contador}][cor]"
            placeholder="Ex.: Vermelho">

        <label>Volume</label>
        <input type="text" name="variacoes[${contador}][volume]"
            placeholder="Ex.: 30 ml">

        <label>Fragrância</label>
        <input type="text" name="variacoes[${contador}][fragancia]"
            placeholder="Ex.: Lavanda">

        <label>Preço adicional</label>
        <input type="number" name="variacoes[${contador}][preco_adicional]"
            value="0.00" step="0.01" min="0">

        <label>Estoque inicial</label>
        <input type="number" name="variacoes[${contador}][estoque_inicial]"
            value="0" min="0" step="1" required>

        <button type="button" class="remover-variacao">
            Remover variação
        </button>
    `;

    container.appendChild(novaVariacao);

    contador++;
});

// Remover uma variação
container.addEventListener("click", function (evento) {

    if (evento.target.classList.contains("remover-variacao")) {

        evento.target.closest(".variacao").remove();

    }

});
