<?php
/**
 * Plugin Name: Gold Intelligence
 * Plugin URI: https://jitenderkumar.in
 * Description: Gold price, valuation, jewellery pricing and intelligence tools powered by the Gold Intelligence API.
 * Version: 1.0.0
 * Author: Jitender Kumar
 * Author URI: https://jitenderkumar.in
 * License: GPL-2.0-or-later
 * Text Domain: gold-intelligence
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'GI_VERSION', '1.0.0' );
define( 'GI_PLUGIN_FILE', __FILE__ );
define( 'GI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once GI_PLUGIN_DIR . 'includes/class-api-client.php';
require_once GI_PLUGIN_DIR . 'includes/class-admin.php';
require_once GI_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once GI_PLUGIN_DIR . 'includes/class-plugin.php';

function gold_intelligence() {
    return Gold_Intelligence_Plugin::instance();
}

gold_intelligence();
