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

function wpl_component_help()
{
	// HOOKABLE: 
	do_action( "wpl_component_help_start" );

	include "wpl.components.help.setup.php";
	include "wpl.components.help.gallery.php";

	wpl_component_components_setup();
	
	wpl_component_components_gallery();

	// HOOKABLE: 
	do_action( "wpl_component_help_end" );
}

wpl_component_help();

// --------------------------------------------------------------------	
