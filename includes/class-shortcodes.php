<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gold_Intelligence_Shortcodes {

    private $api_client;

    public function __construct(
        Gold_Intelligence_API_Client $api_client
    ) {

        $this->api_client = $api_client;

        add_shortcode(
            'gold_price',
            array(
                $this,
                'gold_price_shortcode',
            )
        );

        add_shortcode(
            'gold_calculator',
            array(
                $this,
                'gold_calculator_shortcode',
            )
        );

        add_shortcode(
            'gold_jewellery_calculator',
            array(
                $this,
                'jewellery_calculator_shortcode',
            )
        );
    }

    /**
     * Live gold price shortcode.
     *
     * Usage:
     * [gold_price]
     */
    public function gold_price_shortcode() {

        $price = $this->api_client->get_price();

        if ( is_wp_error( $price ) ) {

            return '<div class="gi-error">' .
                esc_html(
                    $price->get_error_message()
                ) .
                '</div>';
        }

        $purity = isset(
            $price['data']['purity']
        )
            ? $price['data']['purity']
            : array();

        ob_start();

        ?>

        <div class="gi-price-widget">

            <h3>
                Live Gold Price
            </h3>

            <div class="gi-price-grid">

                <div class="gi-price-item">

                    <span class="gi-purity">
                        24K
                    </span>

                    <strong>
                        <?php
                        echo isset( $purity['24K'] )
                            ? '₹' . esc_html(
                                number_format(
                                    (float) $purity['24K'],
                                    2
                                )
                            )
                            : '—';
                        ?>
                    </strong>

                    <small>
                        per gram
                    </small>

                </div>

                <div class="gi-price-item">

                    <span class="gi-purity">
                        22K
                    </span>

                    <strong>
                        <?php
                        echo isset( $purity['22K'] )
                            ? '₹' . esc_html(
                                number_format(
                                    (float) $purity['22K'],
                                    2
                                )
                            )
                            : '—';
                        ?>
                    </strong>

                    <small>
                        per gram
                    </small>

                </div>

                <div class="gi-price-item">

                    <span class="gi-purity">
                        18K
                    </span>

                    <strong>
                        <?php
                        echo isset( $purity['18K'] )
                            ? '₹' . esc_html(
                                number_format(
                                    (float) $purity['18K'],
                                    2
                                )
                            )
                            : '—';
                        ?>
                    </strong>

                    <small>
                        per gram
                    </small>

                </div>

            </div>

        </div>

        <?php

        return ob_get_clean();
    }

    /**
     * Gold calculator shortcode.
     *
     * Usage:
     * [gold_calculator]
     */
    public function gold_calculator_shortcode() {

        $result = null;
        $error  = null;

        if (
            isset( $_POST['gi_gold_calculator_submit'] )
        ) {

            if (
                ! isset( $_POST['gi_gold_calculator_nonce'] ) ||
                ! wp_verify_nonce(
                    sanitize_text_field(
                        wp_unslash(
                            $_POST['gi_gold_calculator_nonce']
                        )
                    ),
                    'gi_gold_calculator'
                )
            ) {

                $error = 'Security verification failed.';

            } else {

                $purity = isset( $_POST['purity'] )
                    ? absint(
                        $_POST['purity']
                    )
                    : 22;

                $weight = isset( $_POST['weight_grams'] )
                    ? floatval(
                        $_POST['weight_grams']
                    )
                    : 0;

                if ( $weight <= 0 ) {

                    $error = 'Please enter a valid weight.';

                } else {

                    $result = $this->api_client->calculate_gold(
                        $purity,
                        $weight
                    );

                    if ( is_wp_error( $result ) ) {

                        $error = $result->get_error_message();

                        $result = null;
                    }
                }
            }
        }

        ob_start();

        ?>

        <div class="gi-calculator">

            <h3>
                Gold Value Calculator
            </h3>

            <?php if ( $error ) : ?>

                <div class="gi-error">
                    <?php
                    echo esc_html( $error );
                    ?>
                </div>

            <?php endif; ?>

            <form method="post">

                <?php
                wp_nonce_field(
                    'gi_gold_calculator',
                    'gi_gold_calculator_nonce'
                );
                ?>

                <p>

                    <label for="gi-purity">
                        Purity
                    </label>

                    <select
                        id="gi-purity"
                        name="purity"
                    >

                        <option value="24">
                            24K
                        </option>

                        <option
                            value="22"
                            selected
                        >
                            22K
                        </option>

                        <option value="18">
                            18K
                        </option>

                    </select>

                </p>

                <p>

                    <label for="gi-weight">
                        Weight (grams)
                    </label>

                    <input
                        type="number"
                        id="gi-weight"
                        name="weight_grams"
                        min="0.001"
                        step="0.001"
                        required
                    >

                </p>

                <p>

                    <button
                        type="submit"
                        name="gi_gold_calculator_submit"
                        value="1"
                    >
                        Calculate
                    </button>

                </p>

            </form>

            <?php if ( is_array( $result ) ) : ?>

                <div class="gi-result">

                    <h4>
                        Calculation Result
                    </h4>

                    <?php
                    $data = isset(
                        $result['data']
                    )
                        ? $result['data']
                        : $result;
                    ?>

                    <?php if ( isset( $data['purity'] ) ) : ?>

                        <p>
                            <strong>Purity:</strong>
                            <?php
                            echo esc_html(
                                $data['purity']
                            );
                            ?>
                        </p>

                    <?php endif; ?>

                    <?php if ( isset( $data['weight_grams'] ) ) : ?>

                        <p>
                            <strong>Weight:</strong>
                            <?php
                            echo esc_html(
                                $data['weight_grams']
                            );
                            ?> g
                        </p>

                    <?php endif; ?>

                    <?php if ( isset( $data['gold_rate'] ) ) : ?>

                        <p>
                            <strong>Gold Rate:</strong>
                            ₹<?php
                            echo esc_html(
                                number_format(
                                    (float) $data['gold_rate'],
                                    2
                                )
                            );
                            ?>
                        </p>

                    <?php endif; ?>

                    <?php
                    if ( isset( $data['gold_value'] ) ) :
                    ?>

                        <p>

                            <strong>
                                Gold Value:
                            </strong>

                            ₹<?php
                            echo esc_html(
                                number_format(
                                    (float) $data['gold_value'],
                                    2
                                )
                            );
                            ?>

                        </p>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </div>

        <?php

        return ob_get_clean();
    }

    /**
     * Jewellery calculator shortcode.
     *
     * Usage:
     * [gold_jewellery_calculator]
     */
    public function jewellery_calculator_shortcode() {

        $result = null;
        $error  = null;

        if (
            isset(
                $_POST['gi_jewellery_calculator_submit']
            )
        ) {

            if (
                ! isset(
                    $_POST['gi_jewellery_calculator_nonce']
                ) ||
                ! wp_verify_nonce(
                    sanitize_text_field(
                        wp_unslash(
                            $_POST[
                                'gi_jewellery_calculator_nonce'
                            ]
                        )
                    ),
                    'gi_jewellery_calculator'
                )
            ) {

                $error = 'Security verification failed.';

            } else {

                $data = array(
                    'purity' => isset(
                        $_POST['purity']
                    )
                        ? absint(
                            $_POST['purity']
                        )
                        : 22,

                    'gross_weight_grams' => isset(
                        $_POST['gross_weight_grams']
                    )
                        ? floatval(
                            $_POST['gross_weight_grams']
                        )
                        : 0,

                    'stone_weight_grams' => isset(
                        $_POST['stone_weight_grams']
                    )
                        ? floatval(
                            $_POST['stone_weight_grams']
                        )
                        : 0,

                    'wastage_percent' => isset(
                        $_POST['wastage_percent']
                    )
                        ? floatval(
                            $_POST['wastage_percent']
                        )
                        : 0,

                    'making_charge' => isset(
                        $_POST['making_charge']
                    )
                        ? floatval(
                            $_POST['making_charge']
                        )
                        : 0,

                    'making_charge_type' => isset(
                        $_POST['making_charge_type']
                    )
                        ? sanitize_text_field(
                            wp_unslash(
                                $_POST[
                                    'making_charge_type'
                                ]
                            )
                        )
                        : 'percent',

                    'stone_charges' => isset(
                        $_POST['stone_charges']
                    )
                        ? floatval(
                            $_POST['stone_charges']
                        )
                        : 0,

                    'discount' => isset(
                        $_POST['discount']
                    )
                        ? floatval(
                            $_POST['discount']
                        )
                        : 0,

                    'gst_percent' => isset(
                        $_POST['gst_percent']
                    )
                        ? floatval(
                            $_POST['gst_percent']
                        )
                        : 3,
                );

                if (
                    $data['gross_weight_grams'] <= 0
                ) {

                    $error = 'Please enter a valid gross weight.';

                } else {

                    $result =
                        $this->api_client->calculate_jewellery(
                            $data
                        );

                    if ( is_wp_error( $result ) ) {

                        $error =
                            $result->get_error_message();

                        $result = null;
                    }
                }
            }
        }

        ob_start();

        ?>

        <div class="gi-jewellery-calculator">

            <h3>
                Jewellery Price Calculator
            </h3>

            <?php if ( $error ) : ?>

                <div class="gi-error">
                    <?php
                    echo esc_html( $error );
                    ?>
                </div>

            <?php endif; ?>

            <form method="post">

                <?php
                wp_nonce_field(
                    'gi_jewellery_calculator',
                    'gi_jewellery_calculator_nonce'
                );
                ?>

                <p>

                    <label>
                        Purity
                    </label>

                    <select name="purity">

                        <option value="24">
                            24K
                        </option>

                        <option
                            value="22"
                            selected
                        >
                            22K
                        </option>

                        <option value="18">
                            18K
                        </option>

                    </select>

                </p>

                <p>

                    <label>
                        Gross Weight (grams)
                    </label>

                    <input
                        type="number"
                        name="gross_weight_grams"
                        min="0.001"
                        step="0.001"
                        required
                    >

                </p>

                <p>

                    <label>
                        Stone Weight (grams)
                    </label>

                    <input
                        type="number"
                        name="stone_weight_grams"
                        min="0"
                        step="0.001"
                        value="0"
                    >

                </p>

                <p>

                    <label>
                        Wastage (%)
                    </label>

                    <input
                        type="number"
                        name="wastage_percent"
                        min="0"
                        step="0.01"
                        value="0"
                    >

                </p>

                <p>

                    <label>
                        Making Charge
                    </label>

                    <input
                        type="number"
                        name="making_charge"
                        min="0"
                        step="0.01"
                        value="0"
                    >

                </p>

                <p>

                    <label>
                        Making Charge Type
                    </label>

                    <select name="making_charge_type">

                        <option
                            value="percent"
                            selected
                        >
                            Percentage
                        </option>

                        <option value="per_gram">
                            Per Gram
                        </option>

                        <option value="fixed">
                            Fixed
                        </option>

                    </select>

                </p>

                <p>

                    <label>
                        Stone Charges (₹)
                    </label>

                    <input
                        type="number"
                        name="stone_charges"
                        min="0"
                        step="0.01"
                        value="0"
                    >

                </p>

                <p>

                    <label>
                        Discount (₹)
                    </label>

                    <input
                        type="number"
                        name="discount"
                        min="0"
                        step="0.01"
                        value="0"
                    >

                </p>

                <p>

                    <label>
                        GST (%)
                    </label>

                    <input
                        type="number"
                        name="gst_percent"
                        min="0"
                        step="0.01"
                        value="3"
                    >

                </p>

                <p>

                    <button
                        type="submit"
                        name="gi_jewellery_calculator_submit"
                        value="1"
                    >
                        Calculate Jewellery Price
                    </button>

                </p>

            </form>

            <?php if ( is_array( $result ) ) : ?>

                <?php
                $data = isset(
                    $result['data']
                )
                    ? $result['data']
                    : $result;
                ?>

                <div class="gi-result">

                    <h4>
                        Jewellery Calculation Result
                    </h4>

                    <?php
                    $fields = array(
                        'gross_weight_grams' => 'Gross Weight',
                        'stone_weight_grams' => 'Stone Weight',
                        'net_gold_weight_grams' => 'Net Gold Weight',
                        'wastage_weight_grams' => 'Wastage Weight',
                        'gold_value' => 'Gold Value',
                        'wastage_value' => 'Wastage Value',
                        'making_charge_value' => 'Making Charge',
                        'stone_charges' => 'Stone Charges',
                        'subtotal' => 'Subtotal',
                        'discount' => 'Discount',
                        'taxable_amount' => 'Taxable Amount',
                        'gst_amount' => 'GST',
                        'final_amount' => 'Final Amount',
                    );
                    ?>

                    <?php foreach (
                        $fields as $key => $label
                    ) : ?>

                        <?php
                        if ( ! isset( $data[ $key ] ) ) {
                            continue;
                        }
                        ?>

                        <p>

                            <strong>
                                <?php
                                echo esc_html(
                                    $label
                                );
                                ?>:
                            </strong>

                            <?php if (
                                false !== strpos(
                                    $key,
                                    'weight'
                                )
                            ) : ?>

                                <?php
                                echo esc_html(
                                    number_format(
                                        (float) $data[ $key ],
                                        3
                                    )
                                );
                                ?>
                                g

                            <?php else : ?>

                                ₹<?php
                                echo esc_html(
                                    number_format(
                                        (float) $data[ $key ],
                                        2
                                    )
                                );
                                ?>

                            <?php endif; ?>

                        </p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

        <?php

        return ob_get_clean();
    }
}
