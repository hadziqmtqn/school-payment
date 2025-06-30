$(document).ready(function () {
    $('#select-class-level').on('select2:select', function (e) {
        // Ambil nama yang dipilih dari select2
        const selectedName = e.params.data.text;
        // Update judul level saat ini
        $('#currentLevelName').text('Dari ' + selectedName);
    });
});