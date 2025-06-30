$(function () {
    const table = '#datatable';
    let token = $('meta[name="csrf-token"]').attr('content');

    const selectClassLevel = $('#select-class-level');

    const dataTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        lengthChange: false,
        order: [[1, 'asc']],
        ajax: {
            url: "/promoted-to-next-grade/datatable",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: function (d) {
                d.search = $(table + '_filter ' + 'input[type="search"]').val();
                d.current_class_level = selectClassLevel.val();
                d.current_level = 'yes';
                d.next_level = 'no';
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'promoted', name: 'promoted'},
            {data: 'subClassLevel', name: 'subClassLevel'},
        ],
        dom:
            '<"card-header d-flex flex-column flex-sm-row pb-md-0 align-items-start align-items-sm-center pt-md-2"<"head-label"><"d-flex align-items-sm-center justify-content-end mt-2 mt-sm-0 gap-3"l<"dt-action-buttons"f>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
        language: {
            sLengthMenu: '_MENU_'
        },
    });

    $('div.head-label').html('<h5 class="card-title text-nowrap mb-2 mb-sm-0" id="currentLevelName">Dari Kelas -</h5>');
    $('.dataTables_length').addClass('mt-0 mt-md-3');
    $('.dt-action-buttons').addClass('pt-0');

    $('.filter').on('change', function () {
        dataTable.ajax.params({
            current_class_level: selectClassLevel.val(),
        });

        dataTable.ajax.reload();
    });
});
