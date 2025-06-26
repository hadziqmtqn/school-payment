/**
 *  Pages Validation
 */

'use strict';
const manualForm = document.querySelector('#manualForm');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        // Form validation for Add new record
        if (manualForm) {
            const fv = FormValidation.formValidation(manualForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                            emailAddress: {
                                message: 'Harap masukkan email valid'
                            }
                        }
                    },
                    gender: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                    whatsapp_number: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                            stringLength: {
                                min: 10,
                                message: 'No. Whatsapp minimal 10 karakter'
                            }
                        }
                    },
                    class_level_id: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                    sub_class_level_id: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                            stringLength: {
                                min: 8,
                                message: 'Kata sandi harus minimal 8 karakter unik'
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: 'Kolom ini wajib diisi'
                            },
                            identical: {
                                compare: function () {
                                    return formChangePass.querySelector('[name="password"]').value;
                                },
                                message: 'Konfirmasi kata sandi tidak sesuai'
                            },
                            stringLength: {
                                min: 8,
                                message: 'Konfirmasi kata sandi harus minimal 8 karakter unik'
                            }
                        }
                    },
                    send_detail_account: {
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
                        rowSelector: '.col-12'
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
