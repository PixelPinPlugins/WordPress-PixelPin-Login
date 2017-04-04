<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_contacts_settings_sidebar()
{
	$sections = array(
		'what_is_this' => 'wsl_component_contacts_settings_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wsl_component_contacts_settings_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_contacts_settings_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wsl_component_contacts_settings_sidebar_sections' );
}

// --------------------------------------------------------------------	


// --------------------------------------------------------------------	
