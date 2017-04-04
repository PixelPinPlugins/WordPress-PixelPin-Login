<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-pixelpin-login/ | https://github.com/miled/wordpress-pixelpin-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-pixelpin-login/
*/

/**
* WSL Tools
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_help_sidebar()
{
	// HOOKABLE: 
	do_action( "wsl_component_help_sidebar_start" );
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("About WordPress PixelPin Login", 'wordpress-pixelpin-login') ?> <?php echo wsl_get_version(); ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e('WordPress PixelPin Login is a free and open source plugin made by the community, for the community', 'wordpress-pixelpin-login') ?>.
			</p> 
			<p>
				<?php _wsl_e('For more information about WordPress PixelPin Login, refer to our online user guide', 'wordpress-pixelpin-login') ?>.
			</p> 
		</div> 
	</div> 
</div> 
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Thanks", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e('Big thanks to everyone who have contributed to WordPress PixelPin Login by submitting Patches, Ideas, Reviews and by Helping in the support forum', 'wordpress-pixelpin-login') ?>.
			</p> 
		</div> 
	</div> 
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_help_sidebar_end" );
}

// --------------------------------------------------------------------	
