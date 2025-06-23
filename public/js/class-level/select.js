$(document).ready(function () {
    const selectClassLevel = $('#select-class-level');
    const selectSubClassLevel = $('#select-sub-class-level');

    selectClassLevel.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Pilih',
        dropdownParent: selectClassLevel.parent(),
        ajax: {
            url: '/select-class-level',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });

    selectSubClassLevel.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Pilih',
        dropdownParent: selectSubClassLevel.parent(),
        ajax: {
            url: '/select-sub-class-level',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
});
