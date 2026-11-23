document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!confirm(form.dataset.confirm)) event.preventDefault();
        });
    });

    const tipo = document.querySelector('#tipo');
    const categoria = document.querySelector('select[name="categoria_id"]');
    if (tipo && categoria) {
        const filtrarCategorias = () => {
            [...categoria.options].forEach((option) => {
                if (!option.value) return;
                option.hidden = option.dataset.tipo !== tipo.value;
                if (option.selected && option.hidden) categoria.value = '';
            });
        };
        tipo.addEventListener('change', filtrarCategorias);
        filtrarCategorias();
    }
});
