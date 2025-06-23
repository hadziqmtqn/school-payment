$(document).ready(function () {
    // Gunakan fungsi reusable untuk inisialisasi select2 pada banyak elemen id berbeda dengan endpoint yang sama

    /**
     * Inisialisasi select2 pada elemen dengan id tertentu dan endpoint tertentu
     * @param {string} idSelector - Selektor id (contoh: "#select-class-level")
     * @param {string} url - Endpoint URL untuk AJAX select2
     */
    function initSelect2WithAjax(idSelector, url) {
        const $select = $(idSelector);

        $select.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Pilih',
            dropdownParent: $select.parent(),
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });
    }

    $(document).ready(function () {
        // Contoh penggunaan:
        // Untuk id berbeda, gunakan fungsi ini dengan id dan endpoint yang diinginkan
        initSelect2WithAjax('#select-class-level', '/select-class-level');
        initSelect2WithAjax('#select-sub-class-level', '/select-sub-class-level');

        // Jika ingin menambah select2 lain dengan endpoint sama:
        initSelect2WithAjax('#select-class-level-alternative', '/select-class-level');
        initSelect2WithAjax('#select-sub-class-level-alternative', '/select-sub-class-level');
    });
});
