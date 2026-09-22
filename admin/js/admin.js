/**
 * Gold Intelligence
 * Admin JavaScript
 */

(function () {

    'use strict';

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const settingsForm =
                document.querySelector(
                    '.gi-settings-form'
                );

            if (settingsForm) {

                settingsForm.addEventListener(
                    'submit',
                    function () {

                        const button =
                            settingsForm.querySelector(
                                'button[type="submit"], input[type="submit"]'
                            );

                        if (!button) {
                            return;
                        }

                        button.disabled = true;

                        button.dataset.originalText =
                            button.tagName === 'BUTTON'
                                ? button.textContent
                                : button.value;

                        if (button.tagName === 'BUTTON') {

                            button.textContent =
                                'Saving...';

                        } else {

                            button.value =
                                'Saving...';
                        }

                    }
                );
            }

            /**
             * Toggle API key visibility.
             */
            const toggleButtons =
                document.querySelectorAll(
                    '[data-gi-toggle-api-key]'
                );

            toggleButtons.forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const targetId =
                                button.getAttribute(
                                    'data-gi-toggle-api-key'
                                );

                            if (!targetId) {
                                return;
                            }

                            const input =
                                document.getElementById(
                                    targetId
                                );

                            if (!input) {
                                return;
                            }

                            if (
                                input.type === 'password'
                            ) {

                                input.type = 'text';

                                button.textContent =
                                    'Hide';

                            } else {

                                input.type = 'password';

                                button.textContent =
                                    'Show';
                            }

                        }
                    );

                }
            );

            /**
             * Prevent accidental double submission.
             */
            const forms =
                document.querySelectorAll(
                    '.gi-prevent-double-submit'
                );

            forms.forEach(
                function (form) {

                    form.addEventListener(
                        'submit',
                        function () {

                            if (
                                form.dataset.submitted === '1'
                            ) {
                                return;
                            }

                            form.dataset.submitted = '1';

                            const submit =
                                form.querySelector(
                                    'button[type="submit"], input[type="submit"]'
                                );

                            if (!submit) {
                                return;
                            }

                            submit.disabled = true;

                        }
                    );

                }
            );

        }
    );

})();
