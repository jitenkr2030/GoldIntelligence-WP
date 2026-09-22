=== Gold Intelligence ===
Contributors: jitenderkr
Tags: gold price, gold calculator, jewellery, gold rate, precious metals
Requires at least: 6.0
Requires PHP: 7.4
Tested up to: 7.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gold price, gold valuation and jewellery pricing tools powered by the Gold Intelligence API.

== Description ==

Gold Intelligence connects WordPress websites with the Gold Intelligence API.

The plugin provides tools for:

* Live gold prices
* Gold value calculation
* Jewellery pricing calculation
* Gold purity-based pricing
* Making charges
* Wastage calculation
* Stone weight and stone charges
* Discounts
* GST calculation
* API-based gold intelligence

The plugin is designed for jewellery businesses, gold retailers, developers and WordPress websites that need gold pricing functionality.

== Features ==

* Live 24K, 22K and 18K gold prices
* Gold value calculator
* Jewellery pricing calculator
* Gross and net gold weight calculation
* Stone weight deduction
* Wastage calculation
* Percentage making charges
* Per-gram making charges
* Fixed making charges
* Stone charges
* Discount calculation
* GST input
* Gold Intelligence API integration
* API key authentication
* WordPress admin dashboard
* Responsive frontend interface

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the WordPress Plugins screen.
3. Go to `Gold Intelligence > API Settings`.
4. Enter your Gold Intelligence API Base URL.
5. Enter your Gold Intelligence API Key.
6. Save the settings.
7. Use the available shortcodes on your WordPress pages.

== Configuration ==

Go to:

`WordPress Admin > Gold Intelligence > API Settings`

Default API Base URL:

`http://127.0.0.1:8001/api/v1`

For a production installation, use your HTTPS Gold Intelligence API URL.

Example:

`https://api.example.com/api/v1`

== Shortcodes ==

=== Live Gold Price ===

Use:

[gold_price]

This displays current 24K, 22K and 18K gold prices.

=== Gold Calculator ===

Use:

[gold_calculator]

This provides a gold value calculator based on purity and weight.

=== Jewellery Calculator ===

Use:

[gold_jewellery_calculator]

This provides jewellery pricing based on:

* Purity
* Gross weight
* Stone weight
* Wastage
* Making charges
* Making charge type
* Stone charges
* Discount
* GST

== API Security ==

The WordPress plugin communicates with the Gold Intelligence API using an API key.

The underlying metals data provider credentials should remain on the Gold Intelligence API server and should not be placed inside the WordPress plugin.

Recommended architecture:

WordPress
    |
    | Gold Intelligence API Key
    v
Gold Intelligence API
    |
    | Provider API
    v
Metals Data Provider

== Privacy ==

This plugin communicates with the configured Gold Intelligence API.

Depending on the features used, information entered into calculators may be transmitted to the configured API server for calculation.

Site administrators should review their own privacy and data-handling requirements before deploying the plugin.

== Frequently Asked Questions ==

= Does the plugin require an external API? =

Yes. The plugin is designed to communicate with the Gold Intelligence API.

= Can I use the plugin without exposing the Metals provider API key? =

Yes. The provider API key should remain on the Gold Intelligence API server.

= Can I use my own Gold Intelligence API server? =

Yes. The API Base URL can be configured from the WordPress admin settings.

= Can I use the calculators with WooCommerce? =

WooCommerce integration is planned for a future version.

== Changelog ==

= 1.0.0 =
* Initial release
* Gold Intelligence API integration
* Live gold price shortcode
* Gold calculator shortcode
* Jewellery calculator shortcode
* WordPress admin dashboard
* API settings page
* Responsive frontend styling

== Upgrade Notice ==

= 1.0.0 =
Initial release.
