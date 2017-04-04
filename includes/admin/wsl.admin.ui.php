<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* The LOC in charge of displaying WSL Admin GUInterfaces
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate wsl admin pages
*
* wp-admin/options-general.php?page=wordpress-pixelpin-login&..
*/
function wsl_admin_main()
{
	// HOOKABLE:
	do_action( "wsl_admin_main_start" );

	if ( ! current_user_can('manage_options') )
	{
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	if( ! wsl_check_requirements() )
	{
		wsl_admin_ui_fail();

		exit;
	}

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_VERSION;

	if( isset( $_REQUEST["enable"] ) && isset( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $_REQUEST["enable"] ] ) )
	{
		$component = $_REQUEST["enable"];

		$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

		update_option( "wsl_components_" . $component . "_enabled", 1 );

		wsl_register_components();
	}

	if( isset( $_REQUEST["disable"] ) && isset( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $_REQUEST["disable"] ] ) )
	{
		$component = $_REQUEST["disable"];

		$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = false;

		update_option( "wsl_components_" . $component . "_enabled", 2 );

		wsl_register_components();
	}

	$wslp            = "networks";
	$wsldwp          = 0;
	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	if( isset( $_REQUEST["wslp"] ) )
	{
		$wslp = trim( strtolower( strip_tags( $_REQUEST["wslp"] ) ) );
	}

	wsl_admin_ui_header( $wslp );

	if( isset( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wslp] ) && $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wslp]["enabled"] )
	{
		if( isset( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wslp]["action"] ) && $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wslp]["action"] )
		{
			do_action( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wslp]["action"] );
		}
		else
		{
			include "components/$wslp/index.php";
		}
	}
	else
	{
		wsl_admin_ui_error();
	}

	wsl_admin_ui_footer();

	// HOOKABLE:
	do_action( "wsl_admin_main_end" );
}

// --------------------------------------------------------------------

/**
* Render wsl admin pages header (label and tabs)
*/
function wsl_admin_ui_header( $wslp = null )
{
	// HOOKABLE:
	do_action( "wsl_admin_ui_header_start" );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_VERSION;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

?>
<a name="wsltop"></a>
<div class="wsl-container">

	<?php
		// nag

		if( in_array( $wslp, array( 'networks', 'login-widget' ) ) and ( isset( $_REQUEST['settings-updated'] ) or isset( $_REQUEST['enable'] ) ) )
		{
			$active_plugins = implode('', (array) get_option('active_plugins') );
			$cache_enabled  =
				strpos( $active_plugins, "w3-total-cache"   ) !== false |
				strpos( $active_plugins, "wp-super-cache"   ) !== false |
				strpos( $active_plugins, "quick-cache"      ) !== false |
				strpos( $active_plugins, "wp-fastest-cache" ) !== false |
				strpos( $active_plugins, "wp-widget-cache"  ) !== false |
				strpos( $active_plugins, "hyper-cache"      ) !== false;

			if( $cache_enabled )
			{
				?>
					<div class="fade updated" style="margin: 4px 0 20px;">
						<p>
							<?php _wsl_e("<b>Note:</b> WSL has detected that you are using a caching plugin. If the saved changes didn't take effect immediately then you might need to empty the cache", 'wordpress-pixelpin-login') ?>.
						</p>
					</div>
				<?php
			}
		}

		if( get_option( 'wsl_settings_development_mode_enabled' ) )
		{
			?>
				<div class="fade error wsl-error-dev-mode-on" style="margin: 4px 0 20px;">
					<p>
						<?php _wsl_e('<b>Warning:</b> You are now running WordPress PixelPin Login with DEVELOPMENT MODE enabled. This mode is not intend for live websites as it might raise serious security risks', 'wordpress-pixelpin-login') ?>.
					</p>
					<p>
						<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wslp=tools#dev-mode"><?php _wsl_e('Change this mode', 'wordpress-pixelpin-login') ?></a>
						<a class="button-secondary" href="http://miled.github.io/wordpress-pixelpin-login/troubleshooting-advanced.html" target="_blank"><?php _wsl_e('Read about the development mode', 'wordpress-pixelpin-login') ?></a>
					</p>
				</div>
			<?php
		}

		if( get_option( 'wsl_settings_debug_mode_enabled' ) )
		{
			?>
				<div class="fade updated wsl-error-debug-mode-on" style="margin: 4px 0 20px;">
					<p>
						<?php _wsl_e('<b>Note:</b> You are now running WordPress PixelPin Login with DEBUG MODE enabled. This mode is not intend for live websites as it might add to loading time and store unnecessary data on your server', 'wordpress-pixelpin-login') ?>.
					</p>
					<p>
						<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wslp=tools#debug-mode"><?php _wsl_e('Change this mode', 'wordpress-pixelpin-login') ?></a>
						<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wslp=watchdog"><?php _wsl_e('View WSL logs', 'wordpress-pixelpin-login') ?></a>
						<a class="button-secondary" href="http://miled.github.io/wordpress-pixelpin-login/troubleshooting-advanced.html" target="_blank"><?php _wsl_e('Read about the debug mode', 'wordpress-pixelpin-login') ?></a>
					</p>
				</div>
			<?php
		}
	?>

	<div class="alignright">
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="http://miled.github.io/wordpress-pixelpin-login/documentation.html"><?php _wsl_e('Docs', 'wordpress-pixelpin-login') ?></a> -
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="http://miled.github.io/wordpress-pixelpin-login/support.html"><?php _wsl_e('Support', 'wordpress-pixelpin-login') ?></a> -
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="https://github.com/miled/wordpress-pixelpin-login"><?php _wsl_e('Github', 'wordpress-pixelpin-login') ?></a>
	</div>

	<h1 <?php if( is_rtl() ) echo 'style="margin: 20px 0;"'; ?>>
		<?php _wsl_e( 'WordPress PixelPin Login', 'wordpress-pixelpin-login' ) ?>

		<small><?php echo $WORDPRESS_PIXELPIN_LOGIN_VERSION ?></small>
	</h1>

	<h2 class="nav-tab-wrapper">
		&nbsp;
		<?php
			$css_pull_right = "";

			foreach( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS as $name => $settings )
			{
				if( $settings["enabled"] && ( $settings["visible"] || $wslp == $name ) )
				{
					if( isset( $settings["pull-right"] ) && $settings["pull-right"] )
					{
						$css_pull_right = "float:right";

						if( is_rtl() )
						{
							$css_pull_right = "float:left";
						}
					}

					?><a class="nav-tab <?php if( $wslp == $name ) echo "nav-tab-active"; ?>" style="<?php echo $css_pull_right; ?>" href="options-general.php?page=wordpress-pixelpin-login&wslp=<?php echo $name ?>"><?php if( isset( $settings["ico"] ) ) echo '<img style="margin: 0px; padding: 0px; border: 0px none;width: 16px; height: 16px;" src="' . WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . '/assets/img/' . $settings["ico"] . '" />'; else _wsl_e( $settings["label"], 'wordpress-pixelpin-login' ); ?></a><?php
				}
			}
		?>
	</h2>

	<div id="wsl_admin_tab_content">
<?php
	// HOOKABLE:
	do_action( "wsl_admin_ui_header_end" );
}

// --------------------------------------------------------------------

/**
* Renders wsl admin pages footer
*/
function wsl_admin_ui_footer()
{
	// HOOKABLE:
	do_action( "wsl_admin_ui_footer_start" );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_VERSION;
?>
	</div> <!-- ./wsl_admin_tab_content -->

<div class="clear"></div>

<?php
	wsl_admin_help_us_localize_note();

	// HOOKABLE:
	do_action( "wsl_admin_ui_footer_end" );

	if( get_option( 'wsl_settings_development_mode_enabled' ) )
	{
		wsl_display_dev_mode_debugging_area();
 	}
}

// --------------------------------------------------------------------

/**
* Renders wsl admin error page
*/
function wsl_admin_ui_error()
{
	// HOOKABLE:
	do_action( "wsl_admin_ui_error_start" );
?>
<div id="wsl_div_warn">
	<h3 style="margin:0px;"><?php _wsl_e('Oops! We ran into an issue.', 'wordpress-pixelpin-login') ?></h3>

	<hr />

	<p>
		<?php _wsl_e('Unknown or Disabled <b>Component</b>! Check the list of enabled components or the typed URL', 'wordpress-pixelpin-login') ?> .
	</p>

	<p>
		<?php _wsl_e("If you believe you've found a problem with <b>WordPress PixelPin Login</b>, be sure to let us know so we can fix it", 'wordpress-pixelpin-login') ?>.
	</p>

	<hr />

	<div>
		<a class="button-secondary" href="http://miled.github.io/wordpress-pixelpin-login/support.html" target="_blank"><?php _wsl_e( "Report as bug", 'wordpress-pixelpin-login' ) ?></a>
		<a class="button-primary" href="options-general.php?page=wordpress-pixelpin-login&wslp=components" style="float:<?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>"><?php _wsl_e( "Check enabled components", 'wordpress-pixelpin-login' ) ?></a>
	</div>
</div>
<?php
	// HOOKABLE:
	do_action( "wsl_admin_ui_error_end" );
}

// --------------------------------------------------------------------

/**
* Renders WSL #FAIL page
*/
function wsl_admin_ui_fail()
{
	// HOOKABLE:
	do_action( "wsl_admin_ui_fail_start" );
?>
<div class="wsl-container">
		<div style="background: none repeat scroll 0 0 #fff;border: 1px solid #e5e5e5;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);padding:20px;">
			<h1><?php _e("WordPress PixelPin Login - FAIL!", 'wordpress-pixelpin-login') ?></h1>

			<hr />

			<p>
				<?php _e('Despite the efforts, put into <b>WordPress PixelPin Login</b> in terms of reliability, portability, and maintenance by the plugin <a href="http://profiles.wordpress.org/miled/" target="_blank">author</a> and <a href="https://github.com/hybridauth/WordPress-PixelPin-Login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-pixelpin-login') ?>.
				<b style="color:red;"><?php _e('Your server failed the requirements check for this plugin', 'wordpress-pixelpin-login') ?>:</b>
			</p>

			<p>
				<?php _e('These requirements are usually met by default by most "modern" web hosting providers, however some complications may occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-pixelpin-login') ?>.
			</p>

			<p>
				<?php _wsl_e("The minimum server requirements are", 'wordpress-pixelpin-login') ?>:
			</p>

			<ul style="margin-left:60px;">
				<li><?php _wsl_e("PHP >= 5.2.0 installed", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wsl_e("WSL Endpoint URLs reachable", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wsl_e("PHP's default SESSION handling", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wsl_e("PHP/CURL/SSL Extension enabled", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wsl_e("PHP/JSON Extension enabled", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wsl_e("PHP/REGISTER_GLOBALS Off", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wsl_e("jQuery installed on WordPress backoffice", 'wordpress-pixelpin-login') ?></li>
			</ul>
		</div>

<?php
	include_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/admin/components/tools/wsl.components.tools.actions.job.php' );

	wsl_component_tools_do_diagnostics();
?>
</div>
<style>.wsl-container .button-secondary { display:none; }</style>
<?php
	// HOOKABLE:
	do_action( "wsl_admin_ui_fail_end" );
}

// --------------------------------------------------------------------

/**
* Renders wsl admin welcome panel
*/
function wsl_admin_welcome_panel()
{
	if( isset( $_REQUEST["wsldwp"] ) && (int) $_REQUEST["wsldwp"] )
	{
		$wsldwp = (int) $_REQUEST["wsldwp"];

		update_option( "wsl_settings_welcome_panel_enabled", wsl_get_version() );

		return;
	}

	// if new user or wsl updated, then we display wsl welcome panel
	if( get_option( 'wsl_settings_welcome_panel_enabled' ) == wsl_get_version() )
	{
		return;
	}

	$wslp = "networks";

	if( isset( $_REQUEST["wslp"] ) )
	{
		$wslp = $_REQUEST["wslp"];
	}
?>
<!--
	if you want to know if a UI was made by developer, then here is a tip: he will always use tables

	//> wsl-w-panel is shamelessly borrowed and modified from wordpress welcome-panel
-->
<div id="wsl-w-panel">
	<a href="options-general.php?page=wordpress-pixelpin-login&wslp=<?php echo $wslp ?>&wsldwp=1" id="wsl-w-panel-dismiss" <?php if( is_rtl() ) echo 'style="left: 10px;right: auto;"'; ?>><?php _wsl_e("Dismiss", 'wordpress-pixelpin-login') ?></a>

	<table width="100%" border="0" style="margin:0;padding:0;">
		<tr>
			<td width="10" valign="top"></td>
			<td width="300" valign="top">
				<b style="font-size: 16px;"><?php _wsl_e("Welcome!", 'wordpress-pixelpin-login') ?></b>
				<p>
					<?php _wsl_e("If you are still new to WordPress PixelPin Login, we have provided a few walkthroughs to get you started", 'wordpress-pixelpin-login') ?>.
				</p>
			</td>
			<td width="40" valign="top"></td>
			<td width="300" valign="top">
				<br />
				<p>
					<b><?php _wsl_e("WordPress PixelPin Login - Get Started", 'wordpress-pixelpin-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><a href="http://developer.pixelpin.io/wordpresspp.php" target="_blank"><?php _wsl_e('WordPress PixelPin Login installation guide', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://developer.pixelpin.io/developeraccount.php" target="_blank"><?php _wsl_e('How to Create A PixelPin Developer Account', 'wordpress-pixelpin-login') ?></a></li>
				</ul>
			</td>
			<td width="260" valign="top">
				<br />
				<p>
					<b><?php _wsl_e("WordPress PixelPin Login - Get Started", 'wordpress-pixelpin-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><a href="http://miled.github.io/wordpress-pixelpin-login/overview.html" target="_blank"><?php _wsl_e('Plugin Overview', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-pixelpin-login/networks.html" target="_blank"><?php _wsl_e('Setup and Configuration', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-pixelpin-login/widget.html" target="_blank"><?php _wsl_e('Customize WSL Widgets', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-pixelpin-login/userdata.html" target="_blank"><?php _wsl_e('Manage users and contacts', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-pixelpin-login/documentation.html" target="_blank"><?php _wsl_e('WSL Developer API', 'wordpress-pixelpin-login') ?></a></li>
				</ul>
			</td>
			<td width="" valign="top">
				<br />
				<p>
					<b><?php echo sprintf( _wsl__( "What's new on WSL %s", 'wordpress-pixelpin-login'), wsl_get_version() ) ?></b>
				</p>

				<ul style="margin-left:25px;">
				</ul>
			</td>
		</tr>
		<tr id="wsl-w-panel-updates-tr">
			<td colspan="5" style="border-top:1px solid #ccc;" id="wsl-w-panel-updates-td">
				&nbsp;
			</td>
		</tr>
	</table>
</div>
<?php
}

// --------------------------------------------------------------------

/**
* Renders wsl localization note
*/
function wsl_admin_help_us_localize_note()
{
	return; // nothing, until I decide otherwise..

	$assets_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';

	?>
		<div id="l10n-footer">
			<br /><br />
			<img src="<?php echo $assets_url ?>flags.png">
			<a href="https://www.transifex.com/projects/p/wordpress-pixelpin-login/" target="_blank"><?php _wsl_e( "Help us translate WordPress PixelPin Login into your language", 'wordpress-pixelpin-login' ) ?></a>
		</div>
	<?php
}

// --------------------------------------------------------------------

/**
* Renders an editor in a page in the typical fashion used in Posts and Pages.
* wp_editor was implemented in wp 3.3. if not found we fallback to a regular textarea
*
* Utility.
*/
function wsl_render_wp_editor( $name, $content )
{
	if( ! function_exists( 'wp_editor' ) )
	{
		?>
			<textarea style="width:100%;height:100px;margin-top:6px;" name="<?php echo $name ?>"><?php echo htmlentities( $content ); ?></textarea>
		<?php
		return;
	}
?>
<div class="postbox">
	<div class="wp-editor-textarea" style="background-color: #FFFFFF;">
		<?php
			wp_editor(
				$content, $name,
				array( 'textarea_name' => $name, 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink' ) )
			);
		?>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

/**
* Display WordPress PixelPin Login on settings as submenu
*/
function wsl_admin_menu()
{
	add_options_page('WP PixelPin Login', 'WP PixelPin Login', 'manage_options', 'wordpress-pixelpin-login', 'wsl_admin_main' );

	add_action( 'admin_init', 'wsl_register_setting' );
}

add_action('admin_menu', 'wsl_admin_menu' );

// --------------------------------------------------------------------

/**
* Enqueue WSL admin CSS file
*/
function wsl_add_admin_stylesheets()
{
	if( ! wp_style_is( 'wsl-admin', 'registered' ) )
	{
		wp_register_style( "wsl-admin", WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . "assets/css/admin.css" );
	}

	wp_enqueue_style( "wsl-admin" );
}

add_action( 'admin_enqueue_scripts', 'wsl_add_admin_stylesheets' );

// --------------------------------------------------------------------
