<?php
/**
 * Register the required and recommended plugins for Campussian theme via TGMPA.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once WCBD_CAMPUSSIAN_DIR . 'inc/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'cmpsian_register_required_plugins' );

/**
 * Register plugins via TGM Plugin Activation.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_register_required_plugins() {
	$plugins = array(
		// Registered from the WordPress.org repository (no bundled zip - plugin
		// zips are not allowed inside themes). Publish campussian-core to the
		// plugin directory before this theme ships.
		array(
			'name'               => esc_html__( 'Campussian Core', 'campussian' ),
			'slug'               => 'campussian-core',
			'required'           => true,
			'version'            => '1.0.1',
			'force_activation'   => false,
			'force_deactivation' => false,
		),
	);

	$config = array(
		'id'           => 'campussian-tgmpa',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Recommended Plugins', 'campussian' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'campussian' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'campussian' ),
			'updating'                        => esc_html__( 'Updating Plugin: %s', 'campussian' ),
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'campussian' ),
			'notice_can_install_required'     => _n_noop(
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'campussian'
			),
			'notice_can_install_recommended'  => _n_noop(
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'campussian'
			),
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'campussian'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'campussian'
			),
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'campussian'
			),
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'campussian'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'campussian'
			),
			'update_link'                     => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'campussian'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'campussian'
			),
			'return'                          => esc_html__( 'Return to Recommended Plugins Installer', 'campussian' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'campussian' ),
			'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'campussian' ),
			'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'campussian' ),
			'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'campussian' ),
			/* translators: %s: dashboard link. */
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'campussian' ),
			'dismiss'                         => esc_html__( 'Dismiss this notice', 'campussian' ),
			'notice_cannot_install_activate'  => esc_html__( 'There are one or more required or recommended plugins to install, update or activate.', 'campussian' ),
			'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'campussian' ),
			'nag_type'                        => '',
		),
	);

	tgmpa( $plugins, $config );
}
