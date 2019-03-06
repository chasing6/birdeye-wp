<?php

/**
 *
 * @link              https://github.com/chasing6/
 * @since             1.0.0
 * @package           Birdeye_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       C6 Birdeye WP
 * Plugin URI:        https://github.com/chasing6/
 * Description:       Birdeye reviews api
 * Version:           0.1.2
 * Author:            Scott McCoy
 * Author URI:        https://github.com/chasing6/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       birdeye-wp
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/chasing6/birdeye-wp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-birdeye-wp-activator.php
 */
function activate_birdeye_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-birdeye-wp-activator.php';
	Birdeye_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-birdeye-wp-deactivator.php
 */
function deactivate_birdeye_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-birdeye-wp-deactivator.php';
	Birdeye_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_birdeye_wp' );
register_deactivation_hook( __FILE__, 'deactivate_birdeye_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-birdeye-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_birdeye_wp() {
    global $birdeye_wp;
	$birdeye_wp = new Birdeye_Wp();
	$birdeye_wp->run();

}
run_birdeye_wp();

//do_action('wp_rtm/add', plugin_dir_path( __FILE__ ) );
