/**
 * Gold Intelligence
 * Public JavaScript
 */

(function () {

    'use strict';

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            /**
             * Prevent accidental double submission
             * on Gold Intelligence calculator forms.
             */
            const calculatorForms =
                document.querySelectorAll(
                    '.gi-calculator form, .gi-jewellery-calculator form'
                );

            calculatorForms.forEach(
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

                            const button =
                                form.querySelector(
                                    'button[type="submit"]'
                                );

                            if (!button) {
                                return;
                            }

                            button.disabled = true;

                            button.dataset.originalText =
                                button.textContent;

                            button.textContent =
                                'Calculating...';

                        }
                    );

                }
            );

            /**
             * Basic numeric validation.
             */
            const numericInputs =
                document.querySelectorAll(
                    '.gi-calculator input[type="number"], .gi-jewellery-calculator input[type="number"]'
                );

            numericInputs.forEach(
                function (input) {

                    input.addEventListener(
                        'input',
                        function () {

                            const value =
                                parseFloat(
                                    input.value
                                );

                            if (
                                !isNaN(value) &&
                                value < 0
                            ) {
                                input.value = 0;
                            }

                        }
                    );

                }
            );

            /**
             * Making charge type helper.
             *
             * Changes the label depending on whether
             * making charge is percentage, per gram,
             * or fixed.
             */
            const makingType =
                document.querySelector(
                    'select[name="making_charge_type"]'
                );

            const makingInput =
                document.querySelector(
                    'input[name="making_charge"]'
                );

            if (
                makingType &&
                makingInput
            ) {

                const updateMakingPlaceholder =
                    function () {

                        switch (
                            makingType.value
                        ) {

                            case 'percent':

                                makingInput.placeholder =
                                    'Example: 10';

                                break;

                            case 'per_gram':

                                makingInput.placeholder =
                                    '₹ per gram';

                                break;

                            case 'fixed':

                                makingInput.placeholder =
                                    'Fixed amount';

                                break;

                            default:

                                makingInput.placeholder =
                                    '';

                                break;
                        }

                    };

                makingType.addEventListener(
                    'change',
                    updateMakingPlaceholder
                );

                updateMakingPlaceholder();
            }

        }
    );

})();
