<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_help()
{
	// HOOKABLE: 
	do_action( "wsl_component_help_start" );

	include "wsl.components.help.setup.php";
	include "wsl.components.help.gallery.php";

	wsl_component_components_setup();
	
	wsl_component_components_gallery();

	// HOOKABLE: 
	do_action( "wsl_component_help_end" );
}

wsl_component_help();

// --------------------------------------------------------------------	
