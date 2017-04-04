<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* Check WSL requirements and register WSL settings 
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Check WSL minimum requirements. Display fail page if they are not met.
*
* This function will only test the strict minimal
*/
function wsl_check_requirements()
{
	if
	(
		   ! version_compare( PHP_VERSION, '5.2.0', '>=' )
		|| ! isset( $_SESSION["wsl::plugin"] )
		|| ! function_exists('curl_init')
		|| ! function_exists('json_decode')
	)
	{
		return false;
	}

	$curl_version = curl_version();

	if( ! ( $curl_version['features'] & CURL_VERSION_SSL ) )
	{
		return false;
	}

	return true;
}

// --------------------------------------------------------------------

/** list of WSL components */
$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS = ARRAY(
	"core"           => array( "type" => "core"  , "label" => _wsl__("WSL Core"   , 'wordpress-pixelpin-login'), "description" => _wsl__("WordPress PixelPin Login core."                   , 'wordpress-pixelpin-login') ),
	"networks"       => array( "type" => "core"  , "label" => _wsl__("Networks"   , 'wordpress-pixelpin-login'), "description" => _wsl__("PixelPin networks setup."                         , 'wordpress-pixelpin-login') ),
	"login-widget"   => array( "type" => "core"  , "label" => _wsl__("Widget"     , 'wordpress-pixelpin-login'), "description" => _wsl__("Authentication widget customization."           , 'wordpress-pixelpin-login') ),
	"bouncer"        => array( "type" => "core"  , "label" => _wsl__("Bouncer"    , 'wordpress-pixelpin-login'), "description" => _wsl__("WordPress PixelPin Login advanced configuration." , 'wordpress-pixelpin-login') ),
	"users"          => array( "type" => "addon" , "label" => _wsl__("Users"      , 'wordpress-pixelpin-login'), "description" => _wsl__("WordPress PixelPin Login users manager."          , 'wordpress-pixelpin-login') ),
	"contacts"       => array( "type" => "addon" , "label" => _wsl__("Contacts"   , 'wordpress-pixelpin-login'), "description" => _wsl__("WordPress PixelPin Login users contacts manager"  , 'wordpress-pixelpin-login') ),
	"buddypress"     => array( "type" => "addon" , "label" => _wsl__("BuddyPress" , 'wordpress-pixelpin-login'), "description" => _wsl__("Makes WordPress PixelPin Login compatible with BuddyPress: Widget integration, Users avatars and xProfiles mapping.", 'wordpress-pixelpin-login') ),
);

/** list of WSL admin tabs */
$WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS = ARRAY(  
	"networks"     => array( "label" => _wsl__("Networks"      , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "networks"       , "default" => true ),
	"login-widget" => array( "label" => _wsl__("Widget"        , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "login-widget"   ),
	"bouncer"      => array( "label" => _wsl__("Bouncer"       , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "bouncer"        ),

	"users"        => array( "label" => _wsl__("Users"         , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "users"         ),
	"contacts"     => array( "label" => _wsl__("Contacts"      , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "contacts"      ),
	"buddypress"   => array( "label" => _wsl__("BuddyPress"    , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "buddypress"    ),

	"help"         => array( "label" => _wsl__('Help'          , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "core"           , "pull-right" => true , 'ico' => 'info.png'       ),
	"tools"        => array( "label" => _wsl__("Tools"         , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "core"           , "pull-right" => true , 'ico' => 'tools.png'      ),
	"watchdog"     => array( "label" => _wsl__("Log viewer"    , 'wordpress-pixelpin-login') , "visible" => false , "component" => "core"           , "pull-right" => true , 'ico' => 'debug.png'      ),
	"auth-paly"    => array( "label" => _wsl__("Auth test"     , 'wordpress-pixelpin-login') , "visible" => false , "component" => "core"           , "pull-right" => true , 'ico' => 'magic.png'      ),
	"components"   => array( "label" => _wsl__("Components"    , 'wordpress-pixelpin-login') , "visible" => true  , "component" => "core"           , "pull-right" => true , 'ico' => 'components.png' ),
);

// --------------------------------------------------------------------

/**
* Register a new WSL component 
*/
function wsl_register_component( $component, $label, $description, $version, $author, $author_url, $component_url )
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;

	$config = array();

	$config["type"]          = "addon"; // < force to addon
	$config["label"]         = $label;
	$config["description"]   = $description;
	$config["version"]       = $version;
	$config["author"]        = $author;
	$config["author_url"]    = $author_url;
	$config["component_url"] = $component_url;

	$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ] = $config;
}

// --------------------------------------------------------------------

/**
* Register new WSL admin tab
*/
function wsl_register_admin_tab( $component, $tab, $label, $action, $visible = false, $pull_right = false ) 
{ 
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

	$config = array();

	$config["component"]  = $component;
	$config["label"]      = $label;
	$config["visible"]    = $visible;
	$config["action"]     = $action;
	$config["pull_right"] = $pull_right;

	$WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[ $tab ] = $config;
}

// --------------------------------------------------------------------

/**
* Check if a component is enabled
*/
function wsl_is_component_enabled( $component )
{ 
	if( get_option( "wsl_components_" . $component . "_enabled" ) == 1 )
	{
		return true;
	}

	return false;
}

// --------------------------------------------------------------------

/**
* Register WSL components (Bulk action)
*/
function wsl_register_components()
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

	// HOOKABLE:
	do_action( 'wsl_register_components' );

	foreach( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS as $tab => $config )
	{
		$WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[ $tab ][ "enabled" ] = false; 
	}

	foreach( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS as $component => $config )
	{
		$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = false;

		$is_component_enabled = get_option( "wsl_components_" . $component . "_enabled" );
		
		if( $is_component_enabled == 1 )
		{
			$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;
		}

		if( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "type" ] == "core" )
		{
			$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

			if( $is_component_enabled != 1 )
			{
				update_option( "wsl_components_" . $component . "_enabled", 1 );
			}
		}
	}

	foreach( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS as $tab => $config )
	{
		$component = $config[ "component" ] ;

		if( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] )
		{
			$WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[ $tab ][ "enabled" ] = true;
		}
	}
}

// --------------------------------------------------------------------

/**
* Register WSL core settings ( options; components )
*/
function wsl_register_setting()
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

	// HOOKABLE:
	do_action( 'wsl_register_setting' );

	wsl_register_components();

	// idps credentials
	foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item )
	{
		$provider_id          = isset( $item["provider_id"]       ) ? $item["provider_id"]       : null;
		$require_client_id    = isset( $item["require_client_id"] ) ? $item["require_client_id"] : null;
		$require_registration = isset( $item["new_app_link"]      ) ? $item["new_app_link"]      : null;
		$default_api_scope    = isset( $item["default_api_scope"] ) ? $item["default_api_scope"] : null;

		/**
		* @fixme
		*
		* Here we should only register enabled providers settings. postponed. patches are welcome.
		***
			$default_network = isset( $item["default_network"] ) ? $item["default_network"] : null;

			if( ! $default_network || get_option( 'wsl_settings_' . $provider_id . '_enabled' ) != 1 .. )
			{
				..
			}
		*/

		register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_enabled' );

		// require application?
		if( $require_registration )
		{
			// api key or id ?
			if( $require_client_id )
			{
				register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_id' ); 
			}
			else
			{
				register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_key' ); 
			}

			// api secret
			register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_secret' ); 

			// api scope?
			if( $default_api_scope )
			{
				if( ! get_option( 'wsl_settings_' . $provider_id . '_app_scope' ) )
				{
					update_option( 'wsl_settings_' . $provider_id . '_app_scope', $default_api_scope );
				}

				register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_scope' );
			}
		}
	}

	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_connect_with_label'                               ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_pixelpin_icon_set'                                  ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_users_avatars'                                    ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_use_popup'                                        ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_widget_display'                                   ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_redirect_url'                                     ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_force_redirect_url'                               ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_users_notification'                               ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_authentication_widget_css'                        ); 

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_registration_enabled'                     ); 
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_authentication_enabled'                   ); 

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_accounts_linking_enabled'                 );

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_profile_completion_require_email'         );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_profile_completion_change_username'       );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_profile_completion_hook_extra_fields'     );

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_moderation_level'               );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_membership_default_role'        );

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_domain_enabled'        );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_domain_list'           );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce'    );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_email_enabled'         );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_email_list'            );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_email_text_bounce'     );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_profile_enabled'       );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_profile_list'          );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce'   );

	register_setting( 'wsl-settings-group-buddypress'       , 'wsl_settings_buddypress_enable_mapping' ); 
	register_setting( 'wsl-settings-group-buddypress'       , 'wsl_settings_buddypress_xprofile_map' ); 

	register_setting( 'wsl-settings-group-debug'            , 'wsl_settings_debug_mode_enabled' ); 
	register_setting( 'wsl-settings-group-development'      , 'wsl_settings_development_mode_enabled' ); 
}

// --------------------------------------------------------------------
