<?php
/**
 * Plugin Name: Restrict Country Access
 * Plugin URI:  https://bhargavb.com/
 * Description: Resrict your site from specific Countries.
 * Version:     1.1.0
 * Author:      Bili Plugins
 * Text Domain: restrict-country
 * Author URI:  https://biliplugins.com/
 *
 * @package      Restrict_Country
 */

/**
 * Defining Constants.
 *
 * @package    Restrict_Country
 */
if ( ! defined( 'RCA_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'RCA_VERSION', '1.1.0' );
}

if ( ! defined( 'RCA_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'RCA_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'RCA_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'RCA_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'RCA_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'RCA_BASE_NAME', plugin_basename( __FILE__ ) );
}

/**
 * Setting link for plugin.
 *
 * @param  array $links Array of plugin setting link.
 * @return array
 */
function rca_setting_page_link( $links ) {

	$settings_link = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( admin_url( 'admin.php?page=rca-restrict-country' ) ),
		esc_html__( 'Settings', 'restrict-country' )
	);

	array_unshift( $links, $settings_link );
	return $links;
}

add_filter( 'plugin_action_links_' . RCA_BASE_NAME, 'rca_setting_page_link' );

// Include Function Files.
require RCA_PATH . '/includes/custom-settings.php';
require RCA_PATH . '/includes/block-country.php';
require RCA_PATH . '/includes/country-list.php';
