function showAlert(title, text, icon, useTimer = true) {
    let buttonClass = 'btn btn-primary me-3 waves-effect waves-light';
    if (icon !== 'success') {
        buttonClass = 'btn btn-danger me-3 waves-effect waves-light';
    }

    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: false,
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: buttonClass,
        },
        buttonsStyling: false,
        timer: useTimer ? 4000 : undefined
    });
}

// Make showAlert globally available
window.showAlert = showAlert;

function showConfirmAlert(title, text, icon, confirmCallback) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        customClass: {
            confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
            cancelButton: 'btn btn-secondary waves-effect waves-light',
        },
        buttonsStyling: false,
    }).then((result) => {
        if (result.isConfirmed) {
            confirmCallback();
        }
    });
}

function showCreateConfirmAlert(title, text, icon, confirmCallback) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: 'Ya!',
        cancelButtonText: 'Batal!',
        customClass: {
            confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
            cancelButton: 'btn btn-secondary waves-effect waves-light',
        },
        buttonsStyling: false,
    }).then((result) => {
        if (result.isConfirmed) {
            confirmCallback();
        }
    });
}

// Make showConfirmAlert globally available
window.showConfirmAlert = showConfirmAlert;
window.showCreateConfirmAlert = showCreateConfirmAlert;