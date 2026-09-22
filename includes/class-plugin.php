<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gold_Intelligence_Plugin {

    /**
     * Plugin instance.
     *
     * @var Gold_Intelligence_Plugin|null
     */
    private static $instance = null;

    /**
     * API client.
     *
     * @var Gold_Intelligence_API_Client
     */
    public $api_client;

    /**
     * Admin controller.
     *
     * @var Gold_Intelligence_Admin|null
     */
    public $admin;

    /**
     * Shortcodes controller.
     *
     * @var Gold_Intelligence_Shortcodes
     */
    public $shortcodes;

    /**
     * Get plugin instance.
     *
     * @return Gold_Intelligence_Plugin
     */
    public static function instance() {

        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {

        $this->api_client = new Gold_Intelligence_API_Client();

        $this->admin = null;

        if ( is_admin() ) {
            $this->admin = new Gold_Intelligence_Admin(
                $this->api_client
            );
        }

        $this->shortcodes = new Gold_Intelligence_Shortcodes(
            $this->api_client
        );
    }
}
