'use strict';
const formValidation = document.querySelector('#form');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        if (formValidation) {
            const fv = FormValidation.formValidation(formValidation, {
                fields: {
                    endpoint: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                    api_key: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: '',
                        rowSelector: '.mb-3'
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),

                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                },
                init: instance => {
                    instance.on('plugins.message.placed', function (e) {
                        if (e.element.parentElement.classList.contains('input-group')) {
                            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                        }
                    });
                }
            });
        }
    })();
});
