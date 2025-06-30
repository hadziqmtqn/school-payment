$(document).ready(function() {
    // Tombol submit di dalam .card-footer
    $('#promotedToNextGradeForm .btn-primary').on('click', function(e) {
        e.preventDefault();

        blockUi();
        toastrOption();

        // 1. Ambil semua user_id
        let userIds = [];
        $('input[name^="user_id"]').each(function() {
            userIds.push($(this).val());
        });

        // 2. Ambil semua promoted (checkbox)
        let promoted = {};
        $('input[name^="promoted"]').each(function() {
            let userId = $(this).attr('name').match(/promoted\[(\d+)]/)[1];
            promoted[userId] = $(this).is(':checked') ? "yes" : "no";
        });

        // 3. Ambil semua next_sub_class_level
        let nextSubClassLevel = {};
        $('select[name^="next_sub_class_level"]').each(function() {
            let userId = $(this).attr('name').match(/next_sub_class_level\[(\d+)]/)[1];
            nextSubClassLevel[userId] = $(this).val();
        });

        // 4. Buat object data untuk submit
        let data = {
            user_id: userIds,
            promoted: promoted,
            next_sub_class_level: nextSubClassLevel,
            // Tambahkan _token jika pakai CSRF Laravel
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // 5. AJAX submit
        $.ajax({
            url: '/promoted-to-next-grade/store', // Ganti dengan route yang sesuai
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                unBlockUi();
                // Tampilkan pesan sukses/gagal
                if(response.status === 200) {
                    alert('Data berhasil disimpan!');
                } else {
                    alert(response.message || 'Gagal menyimpan data.');
                }
            },
            error: function(xhr) {
                unBlockUi();
                // Bisa juga tampilkan pesan validasi dari response JSON
                let msg = 'Terjadi kesalahan!';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
            }
        });
    });
});