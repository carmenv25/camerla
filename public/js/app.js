document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.option-row').forEach((row) => {
        row.addEventListener('click', () => {
            const input = row.querySelector('input');
            if (input) input.checked = true;
        });
    });
});
