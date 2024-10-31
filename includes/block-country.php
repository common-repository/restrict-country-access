<?php
/**
 * Rectrict Country Function
 *
 * @package Restrict_Country
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function Which Restrict Countries.
 */
function rca_block_country() {

	// Get IP Address of user.
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP ) : '';

	if ( empty( $ip ) ) {
		return;
	}

	// API Used to Locate Country based on IP address.
	$query = 'http://ip-api.com/json/' . $ip;

	// Transient Key.
	$trans_key = wp_json_encode( 'cached_country' . $ip );

	// If Transeint does not exist than this block will Execute.
	if ( false === get_transient( $trans_key ) ) {

		// Fetch IP's details from API.
		$response = wp_remote_get( $query );

		if ( is_wp_error( $response ) ) {
			return;
		}
		// Retrieve Body of Remote Response.
		$response_body = wp_remote_retrieve_body( $response );
		$result        = json_decode( $response_body );
		$status        = isset( $result->status ) ? $result->status : '';

		// Get counryCode based on User's IP.
		$country = isset( $result->countryCode ) ? $result->countryCode : ''; //phpcs:ignore

		// Set Transient .
		set_transient( $trans_key, $country, 12 * HOUR_IN_SECONDS );

	} else {
		// If Transeint exist than this block will Execute.

		$country = get_transient( $trans_key );
		$status  = 'success';

	}

	if ( ! empty( $status ) && 'success' === $status ) {

		// Get Selected Country of Plugin From database.
		$selected_country = ! empty( get_option( 'rca_country' ) ) ? get_option( 'rca_country' ) : array();

		// Get Selected Page id of Plugin From database.
		$page_id = get_option( 'rca_page_id' );

		// Current page ID.
		$current_page_id = get_the_ID();

		// Get Permalink of page_id.
		$page_url = get_permalink( $page_id );

		if ( ! empty( $current_page_id ) && is_single() ) {
			$selected_country = ! empty( get_post_meta( $current_page_id, 'rca_selected_country', true ) ) ? get_post_meta( $current_page_id, 'rca_selected_country', true ) : array();
			if ( ! empty( $selected_country ) && ! empty( $country ) && in_array( $country, $selected_country ) ) { // phpcs:ignore
				if ( ! empty( $page_id ) ) {
					if ( $page_id != $current_page_id ) { // phpcs:ignore
						wp_safe_redirect( $page_url );
						exit;
					}
				} else {
					echo sprintf(
						'<h2 style="text-align:center;">%s</h2>',
						esc_html__( 'Site is Restricted in your Country.', 'restrict-country' )
					);
					exit;
				}
			}
		}

		if ( ! empty( $selected_country ) && ! empty( $country ) && in_array( $country, $selected_country ) ) { // phpcs:ignore

			if ( ! empty( $page_id ) && ! empty( $current_page_id ) ) {

				if ( $page_id != $current_page_id ) { // phpcs:ignore

					wp_safe_redirect( $page_url );
					exit;

				}
			} else {

				echo sprintf(
					'<h2 style="text-align:center;">%s</h2>',
					esc_html__( 'Site is Restricted in your Country.', 'restrict-country' )
				);
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'rca_block_country', 9999 );
