document.addEventListener('DOMContentLoaded', function () {
    const manualRadio = document.getElementById('createManualy');
    const importRadio = document.getElementById('createImport');
    const manualForm = document.getElementById('manualForm');
    const importForm = document.getElementById('importForm');

    function showForm(showManual) {
        if (showManual) {
            manualForm.classList.remove('d-none');
            manualForm.classList.add('d-block');
            importForm.classList.remove('d-block');
            importForm.classList.add('d-none');
        } else {
            manualForm.classList.remove('d-block');
            manualForm.classList.add('d-none');
            importForm.classList.remove('d-none');
            importForm.classList.add('d-block');
        }
    }

    showForm(true);

    manualRadio.addEventListener('change', function () {
        if (manualRadio.checked) showForm(true);
    });

    importRadio.addEventListener('change', function () {
        if (importRadio.checked) showForm(false);
    });
});