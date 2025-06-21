/**
 *  Pages Validation
 */

'use strict';
const formAuthentication = document.querySelector('#formCreate');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        // Form validation for Add new record
        if (formAuthentication) {
            const fv = FormValidation.formValidation(formAuthentication, {
                fields: {
                    first_year: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                    last_year: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            }
                        }
                    },
                    is_active: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            }
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
