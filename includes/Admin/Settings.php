<?php
/**
 * Settings class.
 *
 * @package img-ses-wp
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\AwsSesSdkPlugin\Admin;

class Settings {

	/**
	 * Settings constructor.
	 *
	 * @since   0.1.0
	 */
	public function init() {
		/**
		 * Register our wp_ses_general_settings_init to the admin_init action hook.
		 */
		add_action( 'admin_init', array( $this, 'wp_ses_general_settings_init' ) );

		/**
		 * Register our wp_ses_general_options_page to the admin_menu action hook.
		 */
		add_action( 'admin_menu', array( $this, 'wp_ses_general_options_page' ) );

		$plugin = 'aws-ses-sdk-plugin/aws-ses-sdk-plugin.php';
		add_filter( "plugin_action_links_$plugin", array( $this, 'wp_ses_settings_link' ) );
	}
	
	/**
	 * custom option and settings
	 */
	public function wp_ses_general_settings_init() {
		// Register a new setting for "wp_ses_general" page.
		register_setting( 'wp_ses_general', 'wp_ses_general_options' );
	
		// Register a new section in the "wp_ses_general" page.
		add_settings_section(
			'wp_ses_general_section_developers',
			__( '', 'wp_ses_general' ), array( $this, 'wp_ses_general_section_developers_callback' ),
			'wp_ses_general'
		);

		// Register a new field in the "wp_ses_general_section_developers" section, inside the "wp_ses_general" page.
		add_settings_field(
			'wp_ses_general_access_key', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Access Key', 'wp_ses_general' ),
			array( $this, 'wp_ses_general_access_key_cb' ),
			'wp_ses_general',
			'wp_ses_general_section_developers',
			array(
				'label_for' => 'wp_ses_general_access_key',
				'class'     => 'wp_ses_general_row regular-text',
			)
		);

		// Register a new field in the "wp_ses_general_section_developers" section, inside the "wp_ses_general" page.
		add_settings_field(
			'wp_ses_general_secret_key', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Secret Key', 'wp_ses_general' ),
			array( $this, 'wp_ses_general_secret_key_cb' ),
			'wp_ses_general',
			'wp_ses_general_section_developers',
			array(
				'label_for' => 'wp_ses_general_secret_key',
				'class'     => 'wp_ses_general_row regular-text',
			)
		);

		// Register a new field in the "wp_ses_general_section_developers" section, inside the "wp_ses_general" page.
		add_settings_field(
			'wp_ses_general_sender_email', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Sender Email', 'wp_ses_general' ),
			array( $this, 'wp_ses_general_sender_email_cb' ),
			'wp_ses_general',
			'wp_ses_general_section_developers',
			array(
				'label_for' => 'wp_ses_general_sender_email',
				'class'     => 'wp_ses_general_row regular-text',
			)
		);
	}

	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	public function wp_ses_general_section_developers_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'AWS SES API Credentials.', 'wp_ses_general' ); ?></p>
		<?php
	}

	/**
	 * Access key callback function.
	 *
	 * @param array $args
	 */
	public function wp_ses_general_access_key_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_ses_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_ses_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<p class="description" id="tagline-description">Enter Access key.</p>
		<?php
	}

	/**
	 * Secret key callback function.
	 *
	 * @param array $args
	 */
	public function wp_ses_general_secret_key_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_ses_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_ses_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<p class="description" id="tagline-description">Enter Secret Key.</p>
		<?php
	}

	/**
	 * Sender email callback function.
	 *
	 * @param array $args
	 */
	public function wp_ses_general_sender_email_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_ses_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_ses_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<p class="description" id="tagline-description">Enter Sender email.</p>
		<?php
	}

	/**
	 * Add the top level menu page.
	 */
	public function wp_ses_general_options_page() {
		add_submenu_page(
			'options-general.php',
			'AWS SES Settings',
			'AWS SES Settings',
			'manage_options',
			'wp_ses_general',
			array( $this, 'wp_ses_general_options_page_html' )
		);
	}

	/**
	 * Top level menu callback function
	 */
	public function wp_ses_general_options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	
		// add error/update messages
	
		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		/* if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'wp_ses_general_messages', 'wp_ses_general_message', __( 'Settings Saved', 'wp_ses_general' ), 'updated' );
		} */
	
		// show error/update messages
		settings_errors( 'wp_ses_general_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "wp_ses_general"
				settings_fields( 'wp_ses_general' );
				// output setting sections and their fields
				// (sections are registered for "wp_ses_general", each field is registered to a specific section)
				do_settings_sections( 'wp_ses_general' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function wp_ses_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=wp_ses_general">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}