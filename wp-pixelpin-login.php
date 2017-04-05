<?php
/*
Plugin Name: WordPress PixelPin Login
Plugin URI: http://developer.pixelpin.io/ppwordpress.php
Description: Allow your visitors to comment and login with pixelpin.
Version: 1.0.0
Author: PixelPin Plugins
Author URI: https://github.com/PixelPinPlugins
License: MIT License
Text Domain: wordpress-pixelpin-login
Domain Path: /languages
*/

/*
*
*  Hi and thanks for taking the time to check out WSL code.
*
*  Please, don't hesitate to:
*
*   - Report bugs and issues.
*   - Contribute: code, reviews, ideas and design.
*   - Point out stupidity, smells and inconsistencies in the code.
*   - Criticize.
*
*  If you want to contribute, please consider these general "guide lines":
*
*   - Small patches will be always welcome. Large changes should be discussed ahead of time.
*   - That said, don't hesitate to delete code that doesn't make sense or looks redundant.
*   - Feel free to create new functions and files when needed.
*   - Avoid over-commenting, unless you find it necessary.
*   - Avoid using 'switch' and 'for'. I hate those.
*
*  Coding Style :
*
*   - Readable code.
*   - Clear indentations (tabs: 8-char indents).
*   - Same name convention of WordPress: those long long and self-explanatory functions and variables.
*
*  To keep the code accessible to everyone and easy to maintain, WordPress PixelPin Login is programmed in
*  procedural PHP and will be kept that way.
*
*  If you have fixed, improved or translated something in WSL, Please consider contributing back to the project
*  by submitting a Pull Request at https://github.com/miled/wordpress-pixelpin-login
*
*  Grep's user, read below. Keywords stuffing:<add_action|do_action|add_filter|apply_filters>
*
*  If you are here just looking for the hooks, then refer to the online Developer API. If it wasn't possible to
*  achieve some required functionality in a proper way through the already available and documented WSL hooks,
*  please ask for support before resorting to hacks. WSL internals are not to be used.
*  http://miled.github.io/wordpress-pixelpin-login/documentation.html
*
*  If you want to translate this plugin into your language (or to improve the current translations), you can
*  join in the ongoing effort at https://www.transifex.com/projects/p/wordpress-pixelpin-login/
*
*  Peace.
*
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

session_id() or session_start();

global $WORDPRESS_PIXELPIN_LOGIN_VERSION;
global $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;
global $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;
global $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

$WORDPRESS_PIXELPIN_LOGIN_VERSION = "1.0.0";

$_SESSION["wpl::plugin"] = "WordPress PixelPin Login " . $WORDPRESS_PIXELPIN_LOGIN_VERSION;

// --------------------------------------------------------------------

/**
* This file might be used to :
*     1. Redefine WSL constants, so you can move WSL folder around.
*     2. Define WSL Pluggable PHP Functions. See http://miled.github.io/wordpress-pixelpin-login/developer-api-functions.html
*     5. Implement your WSL hooks.
*/
if( file_exists( WP_PLUGIN_DIR . '/wp-pixelpin-login-custom.php' ) )
{
	include_once( WP_PLUGIN_DIR . '/wp-pixelpin-login-custom.php' );
}

// --------------------------------------------------------------------

/**
* Define WSL constants, if not already defined
*/
defined( 'WORDPRESS_PIXELPIN_LOGIN_ABS_PATH' )
	|| define( 'WORDPRESS_PIXELPIN_LOGIN_ABS_PATH', plugin_dir_path( __FILE__ ) );

defined( 'WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL' )
	|| define( 'WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

defined( 'WORDPRESS_PIXELPIN_LOGIN_HYBRIDAUTH_ENDPOINT_URL' )
	|| define( 'WORDPRESS_PIXELPIN_LOGIN_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'hybridauth/' );

// --------------------------------------------------------------------

/**
* Check for Wordpress 3.0
*/
function wpl_activate()
{
	if( ! function_exists( 'register_post_status' ) )
	{
		deactivate_plugins( basename( dirname( __FILE__ ) ) . '/' . basename (__FILE__) );

		wp_die( __( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin.", 'wordpress-pixelpin-login' ) );
	}
}

register_activation_hook( __FILE__, 'wpl_activate' );

// --------------------------------------------------------------------

/**
* Attempt to install/migrate/repair WSL upon activation
*
* Create wpl tables
* Migrate old versions
* Register default components
*/
function wpl_install()
{
	wpl_database_install();

	wpl_update_compatibilities();

	wpl_register_components();
}

register_activation_hook( __FILE__, 'wpl_install' );

// --------------------------------------------------------------------

/**
* Add a settings to plugin_action_links
*/
function wpl_add_plugin_action_links( $links, $file )
{
	static $this_plugin;

	if( ! $this_plugin )
	{
		$this_plugin = plugin_basename( __FILE__ );
	}

	if( $file == $this_plugin )
	{
		$wpl_links  = '<a href="options-general.php?page=wordpress-pixelpin-login">' . __( "Settings" ) . '</a>';

		array_unshift( $links, $wpl_links );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'wpl_add_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------

/**
* Add faq and user guide links to plugin_row_meta
*/
function wpl_add_plugin_row_meta( $links, $file )
{
	static $this_plugin;

	if( ! $this_plugin )
	{
		$this_plugin = plugin_basename( __FILE__ );
	}

	if( $file == $this_plugin )
	{
		$wpl_links = array(

		);

		return array_merge( $links, $wpl_links );
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'wpl_add_plugin_row_meta', 10, 2 );

// --------------------------------------------------------------------

/**
* Loads the plugin's translated strings.
*
* http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
*/
if( ! function_exists( 'wpl_load_plugin_textdomain' ) )
{
	function wpl_load_plugin_textdomain()
	{
		load_plugin_textdomain( 'wordpress-pixelpin-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

add_action( 'plugins_loaded', 'wpl_load_plugin_textdomain' );

// --------------------------------------------------------------------

/**
* _e() wrapper
*/
function _wpl_e( $text, $domain )
{
	echo __( $text, $domain );
}

// --------------------------------------------------------------------

/**
* __() wrapper
*/
function _wpl__( $text, $domain )
{
	return __( $text, $domain );
}

// --------------------------------------------------------------------

/* includes */

# WSL Setup & Settings
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.providers.php'            ); // List of supported providers (mostly provided by hybridauth library)
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.database.php'             ); // Install/Uninstall WSL database tables
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.initialization.php'       ); // Check WSL requirements and register WSL settings
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.compatibilities.php'      ); // Check and upgrade WSL database/settings (for older versions)

# Services & Utilities
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.authentication.php'       ); // Authenticate users via pixelpin networks. <- that's the most important script
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.mail.notification.php'    ); // Emails and notifications
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.user.avatar.php'          ); // Display users avatar
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.user.data.php'            ); // User data functions (database related)
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.utilities.php'            ); // Unclassified functions & utilities
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.watchdog.php'             ); // WSL logging agent

# WSL Widgets & Front-end interfaces
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.auth.widgets.php'          ); // Authentication widget generators (where WSL widget/icons are displayed)
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.users.gateway.php'         ); // Accounts linking + Profile Completion
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.error.pages.php'           ); // Generate WSL notices end errors pages
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.loading.screens.php'       ); // Generate WSL loading screens

# WSL Admin interfaces
if( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
{
	require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/admin/wpl.admin.ui.php'        ); // The entry point to WSL Admin interfaces
}

// --------------------------------------------------------------------