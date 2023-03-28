<?php
/**
 * Plugin Name: WPStartUp
 * Description: Does initial setup after WordPress installation. Create projects in Bugsnag and Pingdom..
 * Version: 0.0.1
 * Author: Dinamo
 * Author URI: https://smfb-dinamo.com
 * Tested up to: 6.1.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use WPD\WPStartUp;

define( 'WPSTARTUP_FILE', __FILE__ );
define( 'WPSTARTUP_VERSION', '0.0.1' );

( new WPStartUp\Plugin() )->run();

