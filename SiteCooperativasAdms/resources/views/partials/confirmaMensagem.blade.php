<dialog id="confirmaMensagem">
    <p id="textoConfirmaMensagem"></p>
    <div class="dialog-actions">
        <button type="button" id="botaoCancelar">Cancelar</button>
        <button type="button" id="botaoConfirmar">Confirmar</button>
    </div>
</dialog>

<script>
    (function () {
        const modal = document.getElementById('confirmaMensagem');
        const texto = document.getElementById('textoConfirmaMensagem');
        let formulario = null;

        // Todo formulário com data-confirmar passa pelo modal antes de ser enviado
        document.querySelectorAll('form[data-confirmar]').forEach(function (form) {
            form.addEventListener('submit', function (evento) {
                evento.preventDefault();
                formulario = form;
                texto.textContent = form.dataset.confirmar;
                modal.showModal();
            });
        });

        document.getElementById('botaoConfirmar').addEventListener('click', function () {
            modal.close();
            if (formulario) formulario.submit();
        });

        document.getElementById('botaoCancelar').addEventListener('click', function () {
            modal.close();
            formulario = null;
        });
    })();
</script>