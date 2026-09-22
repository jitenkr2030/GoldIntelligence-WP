<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gold_Intelligence_API_Client {

    /**
     * API base URL.
     *
     * @var string
     */
    private $base_url;

    /**
     * Gold Intelligence API key.
     *
     * @var string
     */
    private $api_key;

    /**
     * Constructor.
     */
    public function __construct() {

        $this->base_url = get_option(
            'gi_api_base_url',
            'http://127.0.0.1:8001/api/v1'
        );

        $this->api_key = get_option(
            'gi_api_key',
            ''
        );

        $this->base_url = untrailingslashit(
            $this->base_url
        );
    }

    /**
     * Make API request.
     *
     * @param string     $endpoint API endpoint.
     * @param string     $method   HTTP method.
     * @param array|null $body     Request body.
     *
     * @return array|WP_Error
     */
    private function request(
        $endpoint,
        $method = 'GET',
        $body = null
    ) {

        if ( empty( $this->api_key ) ) {

            return new WP_Error(
                'gi_missing_api_key',
                'Gold Intelligence API key is not configured.'
            );
        }

        $url = $this->base_url . '/' . ltrim(
            $endpoint,
            '/'
        );

        $args = array(
            'method'  => $method,
            'timeout' => 15,
            'headers' => array(
                'X-API-Key'    => $this->api_key,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ),
        );

        if ( null !== $body ) {

            $args['body'] = wp_json_encode(
                $body
            );
        }

        $response = wp_remote_request(
            $url,
            $args
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $status_code = wp_remote_retrieve_response_code(
            $response
        );

        $response_body = wp_remote_retrieve_body(
            $response
        );

        $data = json_decode(
            $response_body,
            true
        );

        if (
            $status_code < 200 ||
            $status_code >= 300
        ) {

            $message = 'Gold Intelligence API request failed.';

            if (
                is_array( $data ) &&
                isset( $data['detail'] )
            ) {

                if ( is_string( $data['detail'] ) ) {

                    $message = $data['detail'];

                } else {

                    $message = wp_json_encode(
                        $data['detail']
                    );
                }
            }

            return new WP_Error(
                'gi_api_error',
                $message,
                array(
                    'status_code' => $status_code,
                    'response'    => $data,
                )
            );
        }

        if ( ! is_array( $data ) ) {

            return new WP_Error(
                'gi_invalid_response',
                'Invalid response received from Gold Intelligence API.'
            );
        }

        return $data;
    }

    /**
     * Get current gold price.
     *
     * @return array|WP_Error
     */
    public function get_price() {

        return $this->request(
            'gold/price'
        );
    }

    /**
     * Get gold price history.
     *
     * @param int $days Number of days.
     *
     * @return array|WP_Error
     */
    public function get_history( $days = 7 ) {

        $days = absint( $days );

        if ( $days < 1 ) {
            $days = 1;
        }

        if ( $days > 365 ) {
            $days = 365;
        }

        return $this->request(
            'gold/history?days=' . $days
        );
    }

    /**
     * Calculate gold value.
     *
     * @param int   $purity       Gold purity.
     * @param float $weight_grams Weight in grams.
     *
     * @return array|WP_Error
     */
    public function calculate_gold(
        $purity,
        $weight_grams
    ) {

        return $this->request(
            'gold/calculate',
            'POST',
            array(
                'purity'       => absint( $purity ),
                'weight_grams' => floatval( $weight_grams ),
            )
        );
    }

    /**
     * Calculate jewellery pricing V2.
     *
     * @param array $data Jewellery calculation data.
     *
     * @return array|WP_Error
     */
    public function calculate_jewellery(
        $data
    ) {

        return $this->request(
            'gold/jewellery/v2/calculate',
            'POST',
            $data
        );
    }
}
