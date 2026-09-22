<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gold_Intelligence_Admin {

    /**
     * API client.
     *
     * @var Gold_Intelligence_API_Client
     */
    private $api_client;

    /**
     * Constructor.
     *
     * @param Gold_Intelligence_API_Client $api_client API client.
     */
    public function __construct(
        Gold_Intelligence_API_Client $api_client
    ) {

        $this->api_client = $api_client;

        add_action(
            'admin_menu',
            array( $this, 'admin_menu' )
        );

        add_action(
            'admin_init',
            array( $this, 'register_settings' )
        );
    }

    /**
     * Register admin menu.
     */
    public function admin_menu() {

        add_menu_page(
            'Gold Intelligence',
            'Gold Intelligence',
            'manage_options',
            'gold-intelligence',
            array(
                $this,
                'dashboard_page',
            ),
            'dashicons-chart-line',
            30
        );

        add_submenu_page(
            'gold-intelligence',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'gold-intelligence',
            array(
                $this,
                'dashboard_page',
            )
        );

        add_submenu_page(
            'gold-intelligence',
            'API Settings',
            'API Settings',
            'manage_options',
            'gold-intelligence-settings',
            array(
                $this,
                'settings_page',
            )
        );
    }

    /**
     * Register plugin settings.
     */
    public function register_settings() {

        register_setting(
            'gi_settings_group',
            'gi_api_base_url',
            array(
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default'           => 'http://127.0.0.1:8001/api/v1',
            )
        );

        register_setting(
            'gi_settings_group',
            'gi_api_key',
            array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            )
        );
    }

    /**
     * Dashboard page.
     */
    public function dashboard_page() {

        $price = $this->api_client->get_price();

        $connected = ! is_wp_error( $price );

        ?>

        <div class="wrap">

            <h1>Gold Intelligence</h1>

            <p>
                Gold price, valuation and jewellery intelligence
                powered by the Gold Intelligence API.
            </p>

            <hr>

            <div
                style="
                    background:#fff;
                    border:1px solid #dcdcde;
                    padding:20px;
                    margin-top:20px;
                    max-width:900px;
                "
            >

                <h2>API Connection</h2>

                <?php if ( $connected ) : ?>

                    <p>
                        <strong>Status:</strong>

                        <span
                            style="
                                color:#008a20;
                                font-weight:600;
                            "
                        >
                            ● Connected
                        </span>
                    </p>

                    <?php
                    $purity = isset(
                        $price['data']['purity']
                    )
                        ? $price['data']['purity']
                        : array();
                    ?>

                    <table
                        class="widefat striped"
                        style="
                            max-width:650px;
                            margin-top:20px;
                        "
                    >

                        <thead>

                            <tr>
                                <th>Purity</th>
                                <th>Price / Gram</th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>
                                    <strong>24K</strong>
                                </td>

                                <td>
                                    <?php
                                    if ( isset( $purity['24K'] ) ) {
                                        echo '₹' . esc_html(
                                            number_format(
                                                (float) $purity['24K'],
                                                2
                                            )
                                        );
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </td>

                            </tr>

                            <tr>

                                <td>
                                    <strong>22K</strong>
                                </td>

                                <td>
                                    <?php
                                    if ( isset( $purity['22K'] ) ) {
                                        echo '₹' . esc_html(
                                            number_format(
                                                (float) $purity['22K'],
                                                2
                                            )
                                        );
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </td>

                            </tr>

                            <tr>

                                <td>
                                    <strong>18K</strong>
                                </td>

                                <td>
                                    <?php
                                    if ( isset( $purity['18K'] ) ) {
                                        echo '₹' . esc_html(
                                            number_format(
                                                (float) $purity['18K'],
                                                2
                                            )
                                        );
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                    <?php
                    if (
                        isset(
                            $price['data']['provider_timestamp']
                        )
                    ) :
                    ?>

                        <p
                            style="
                                margin-top:15px;
                                color:#646970;
                            "
                        >
                            Provider timestamp:
                            <?php
                            echo esc_html(
                                $price['data']['provider_timestamp']
                            );
                            ?>
                        </p>

                    <?php endif; ?>

                <?php else : ?>

                    <p>

                        <strong>Status:</strong>

                        <span
                            style="
                                color:#b32d2e;
                                font-weight:600;
                            "
                        >
                            ● Not Connected
                        </span>

                    </p>

                    <div
                        style="
                            background:#fcf0f1;
                            border-left:4px solid #d63638;
                            padding:12px;
                            max-width:650px;
                        "
                    >
                        <?php
                        echo esc_html(
                            $price->get_error_message()
                        );
                        ?>
                    </div>

                    <p>

                        <a
                            href="<?php
                            echo esc_url(
                                admin_url(
                                    'admin.php?page=gold-intelligence-settings'
                                )
                            );
                            ?>"
                            class="button button-primary"
                        >
                            Configure API
                        </a>

                    </p>

                <?php endif; ?>

            </div>

        </div>

        <?php
    }

    /**
     * API settings page.
     */
    public function settings_page() {

        ?>

        <div class="wrap">

            <h1>Gold Intelligence API Settings</h1>

            <p>
                Connect this WordPress plugin to your
                Gold Intelligence API.
            </p>

            <form
                method="post"
                action="options.php"
            >

                <?php
                settings_fields(
                    'gi_settings_group'
                );
                ?>

                <table class="form-table">

                    <tr>

                        <th scope="row">

                            <label for="gi_api_base_url">
                                API Base URL
                            </label>

                        </th>

                        <td>

                            <input
                                type="url"
                                id="gi_api_base_url"
                                name="gi_api_base_url"
                                value="<?php
                                echo esc_attr(
                                    get_option(
                                        'gi_api_base_url',
                                        'http://127.0.0.1:8001/api/v1'
                                    )
                                );
                                ?>"
                                class="regular-text"
                            >

                            <p class="description">
                                Example:
                                https://api.example.com/api/v1
                            </p>

                        </td>

                    </tr>

                    <tr>

                        <th scope="row">

                            <label for="gi_api_key">
                                Gold Intelligence API Key
                            </label>

                        </th>

                        <td>

                            <input
                                type="password"
                                id="gi_api_key"
                                name="gi_api_key"
                                value="<?php
                                echo esc_attr(
                                    get_option(
                                        'gi_api_key',
                                        ''
                                    )
                                );
                                ?>"
                                class="regular-text"
                                autocomplete="new-password"
                            >

                            <p class="description">
                                Enter your Gold Intelligence
                                API key. The key is stored in
                                WordPress options and is never
                                displayed on the dashboard.
                            </p>

                        </td>

                    </tr>

                </table>

                <?php
                submit_button(
                    'Save API Settings'
                );
                ?>

            </form>

        </div>

        <?php
    }
}
