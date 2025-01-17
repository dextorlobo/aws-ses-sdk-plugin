<?php
/**
 * Main plugin class.
 *
 * @package img-ses-wp
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\AwsSesSdkPlugin;
use Imarun\AwsSesSdkPlugin\Admin\Settings as AdminSettings;

/**
 * The core plugin class.
 *
 * @since   0.1.0
 * @package img-ses-wp
 */
class Plugin {
	public function init() {
		/**
		 * Load hooks after setup theme.
		 */
		add_action( 'after_setup_theme', array( $this, 'ses_fire_after_setup_theme_methods' ) );
	}

	/**
	* Settings Page.
	*/
	public function ses_fire_after_setup_theme_methods() {
		( new AdminSettings() )->init();
	}
}
