<?php
/**
 * The plugin bootstrap file.
 *
 * @since 1.0.0
 * @package img-ses-wp
 *
 * @wordpress-plugin
 * Plugin Name:       AWS SES Plugin
 * Description:       AWS SES SDK plugin.
 * Version:           1.0.0
 * Author:            Arun Sharma
 * Author URI:        https://www.imarun.me/
 * Text Domain:       img-ses-wp
 */

declare( strict_types = 1 );

use Imarun\AwsSesSdkPlugin\Plugin;
use Imarun\AwsSesSdkPlugin\Api;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * API and Plugin version constants.
 */
define( 'IMG_SES_PLUGIN_VERSION', '1.0.0' );
define( 'IMG_SES_PLUGIN_PATH', __FILE__ );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once __DIR__ . '/vendor/autoload.php';
} else {
	throw new \Exception( 'Missing vendor/autoload.php. Please run composer install.' );
}

( new Plugin() )->init();

function get_aws_ses_api_instance() {
	return new Api();
}
