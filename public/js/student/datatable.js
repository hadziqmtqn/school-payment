$(function () {
    const table = '#datatable';
    let token = $('meta[name="csrf-token"]').attr('content');

    const schoolYear = $('#select-school-year');
    const selectClassLevel = $('#select-class-level');
    const selectSubClassLevel = $('#select-sub-class-level');

    const dataTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        order: [[1, 'asc']],
        ajax: {
            url: "/student/datatable",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: function (d) {
                d.search = $(table + '_filter ' + 'input[type="search"]').val();
                d.school_year_id = schoolYear.val();
                d.class_level_id = selectClassLevel.val();
                d.sub_class_level_id = selectSubClassLevel.val();
                d.is_active = $('#status').val();
                d.is_graduate = $('#isGraduate').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'regNumber', name: 'regNumber'},
            {data: 'classLevel', name: 'classLevel'},
            {data: 'whatsappNumber', name: 'whatsappNumber'},
            {data: 'is_active', name: 'is_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        dom:
            '<"row mx-1"' +
            '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-3"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start mt-md-0 mt-3"B>>' +
            '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-3"' +
            'f' + // Input pencarian DataTable
            '<"filters d-flex gap-2">' + // Container untuk filter tambahan
            '>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
        language: {
            sLengthMenu: '_MENU_',
            search: '',
            searchPlaceholder: 'Cari data'
        },
        buttons: [
            {
                text: '<i class="mdi mdi-plus me-1"></i>Tambah Baru',
                className: 'btn btn-primary waves-effect waves-light',
                attr: {
                    'data-bs-target': '#modalCreate',
                    'data-bs-toggle': 'modal',
                }
            }
        ],
        initComplete: function () {
            $(
                '<select id="status" class="form-select">' +
                '<option value="">Pilih Status</option>' +
                '<option value="active">Aktif</option>' +
                '<option value="inactive">Tidak Aktif</option>' +
                '<option value="deleted">Terhapus</option>' +
                '</select>'
            )
                .appendTo('.filters')
                .on('change', function () {
                    dataTable.ajax.reload(); // Reload DataTables saat filter berubah
                });

            $(
                '<select id="isGraduate" class="form-select">' +
                '<option value="">Pilih Kelulusan</option>' +
                '<option value="yes">Ya</option>' +
                '<option value="no">Tidak</option>' +
                '</select>'
            )
                .appendTo('.filters')
                .on('change', function () {
                    dataTable.ajax.reload(); // Reload DataTables saat filter berubah
                });
        }
    });

    $('.filter').on('change', function () {
        dataTable.ajax.params({
            school_year_id: schoolYear.val(),
            class_level_id: selectClassLevel.val(),
            sub_class_level_id: selectSubClassLevel.val()
        });

        dataTable.ajax.reload();
    });

    function handleAction(element, action, message, buttonText, buttonClass, method = 'delete') {
        let username = $(element).data('username');
        let url = `/student/${username}/${action}`;

        Swal.fire({
            title: 'Peringatan!',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: `YA, ${buttonText}`,
            customClass: {
                confirmButton: `btn btn-${buttonClass} me-3 waves-effect waves-light`,
                cancelButton: 'btn btn-label-secondary waves-effect'
            },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                blockUi();

                axios[method](url, {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function (response) {
                    toastr.success(response.data.message);
                    dataTable.ajax.reload(); // Reload tabel tanpa mengubah halaman
                    unBlockUi();
                }).catch(function (error) {
                    unBlockUi();
                    toastr.error(error.response.data.message);
                });
            }
        });
    }

    $(table).on('click', '.delete', function () {
        handleAction(this, 'delete', 'Apakah Anda ingin menghapus data ini?', 'HAPUS!', 'danger');
    });

    $(table).on('click', '.restore', function () {
        handleAction(this, 'restore', 'Apakah Anda ingin mengembalikan data ini?', 'KEMBALIKAN!', 'warning', 'post');
    });

    $(table).on('click', '.force-delete', function () {
        handleAction(this, 'force-delete', 'Apakah Anda ingin menghapus permanen data ini?', 'HAPUS PERMANEN!', 'danger');
    });
});
